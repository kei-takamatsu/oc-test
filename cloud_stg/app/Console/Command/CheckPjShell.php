<?php
App::import('Core', 'Controller');
App::import('Controller', 'App');
App::uses('CakeEmail', 'Network/Email');
$vendor = App::path('Vendor');
$path   = $vendor[0].'gpay_client'.DS;
set_include_path(get_include_path().PATH_SEPARATOR.$path);
App::import('Vendor', 'gpay_client/com/gmo_pg/client/tran/AlterTran');
App::import('Vendor', 'gpay_client/com/gmo_pg/client/input/AlterTranInput');

class CheckPjShell extends AppShell
{
    var $uses = array('Project', 'BackedProject', 'User', 'Setting');
    var $setting = null;

    public function startup()
    {
        $this->setting = $this->Setting->findById(1);
    }

    public function main()
    {
        $this->log('start');
        $project = $this->get_end_projects();
        if(!$project){
            $this->log('project is empty');
            return;
        }
        $project = $project[0];
        if($this->check_ok_ng($project)){
            $this->log('ok');
            if($this->exec_pay($project)){
                $this->mail_fin_to_owner($project);
            }
        }else{
            if($this->cancel_pay($project)){
                $this->mail_fin_to_owner($project);
            }
        }
    }

    /**
     * 募集期間が過ぎていて、Activeになっているプロジェクトを一つ返す関数
     */
    private function get_end_projects()
    {
        $options   = array(
            'conditions' => array(
                'Project.collection_end_date <=' => date('Y-m-d 0:0:0'),
                'Project.active' => 'yes',
                'Project.opened' => 'yes'
            ),
            'limit' => 1,
            'recursive' => -1
        );
        return $this->Project->find('all', $options);
    }

    /**
     * 該当プロジェクトが成功したか失敗したかをチェックする関数
     * @param array $project
     * @return boolean
     */
    private function check_ok_ng($project)
    {
        $goal_amount      = $project['Project']['goal_amount'];
        $collected_amount = $project['Project']['collected_amount'];
        if($goal_amount <= $collected_amount){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 成功したプロジェクトの支援全てに対して決済を実行する関数
     * @param array $project
     * @return boolean
     */
    private function exec_pay($project)
    {
        $this->BackedProject->recursive = -1;
        $backed_projects = $this->BackedProject->findAllByProjectId($project['Project']['id']);
        $result = true;
        foreach($backed_projects as $backed_project){
            $code = $this->_exec_pay($backed_project);
            if($code == 3){ //失敗
                $result = false;
            }elseif($code == 1){ //成功
                //支援者にプロジェクト成功・支援完了のお知らせメール送信
                $this->mail_exec_to_backer($project, $backed_project);
            }
        }
        //決済実行が全て完遂している場合は処理を続行
        //途中でエラーになった場合は次回残りを処理できるようにする
        if($result){
            //Projectにactive=noと、各参加者が貰える金額、入金予定日を登録する
            $all_amount = $project['Project']['collected_amount'];
            $site_fee   = $project['Project']['site_fee'];
            //各参加者の入金予定額の登録
            $project_owner_price = round($all_amount * (100 - $site_fee) / 100);
            $site_price = $all_amount - $project_owner_price;
            $data = array(
                'Project' => array(
                    'active' => 'no',
                    'project_owner_price' => $project_owner_price,
                    'site_price' => $site_price,
                )
            );
            $this->Project->id = $project['Project']['id'];
            if($this->Project->save($data, true, array(
                'active', 'project_owner_price', 'site_price'
            ))){
                return true;
            }
        }
        return false;
    }

    /**
     * GMOペイメントの決済売上確定処理
     * @param array $backed_project
     * @return int $result
     *  1 -> 処理成功 -> mailして次に進む
     *  2 -> 処理不要 -> mailせずに次に進む
     *  3 -> 処理失敗 -> mailせずに次に進まない
     */
    private function _exec_pay($backed_project)
    {
        //ステータスが、仮売上登録状態のもののみ実施
        if($backed_project['BackedProject']['status'] != '仮売上完了'){
            return 2;
        }
        if(! $backed_project['BackedProject']['manual_flag']){
            $AlterTranInput = new AlterTranInput();
            $AlterTranInput->setAccessId($backed_project['BackedProject']['accessId']);
            $AlterTranInput->setAccessPass($backed_project['BackedProject']['accessPass']);
            $AlterTranInput->setJobCd('SALES');
            $AlterTranInput->setAmount($backed_project['BackedProject']['invest_amount']);
            $AlterTranInput->setShopId($this->setting['Setting']['gmo_id']);
            $AlterTranInput->setShopPass($this->setting['Setting']['gmo_password']);
            $AlterTran = new AlterTran();
            $alter_tran_output = $AlterTran->exec($AlterTranInput);
        }else{
            $alter_tran_output = null;
        }
        if(!empty($alter_tran_output->getErrList())){
            return 3;
        }else{
            if($this->put_exec_info_to_backed_project('exec', $backed_project, $alter_tran_output)){
                return 1;
            }
            return 3;
        }
    }

    /**
     * 決済実行結果情報をbacked_projectsテーブルに登録する関数
     * @param str   $exec_or_fail (成功か失敗か)
     * @param array $backed_project
     * @param obj   $alter_tran_output
     * @return boolean
     */
    private function put_exec_info_to_backed_project($exec_or_fail, $backed_project, $alter_tran_output)
    {
        if(!empty($alter_tran_output)){
            $backed_project['BackedProject']['forward']       = $alter_tran_output->getForward();
            $backed_project['BackedProject']['approve']       = $alter_tran_output->getApprovalNo();
            $backed_project['BackedProject']['transactionId'] = $alter_tran_output->getTranId();
            $backed_project['BackedProject']['tranDate']      = $alter_tran_output->getTranDate();
        }
        $backed_project['BackedProject']['checkString']   = null;
        if($exec_or_fail == 'exec'){ //プロジェクトが成功し、売上確定した状態
            $backed_project['BackedProject']['status'] = '売上確定';
        }else{ //プロジェクトが失敗し、売上キャンセルした状態
            $backed_project['BackedProject']['status'] = 'プロジェクト失敗';
        }
        if($this->BackedProject->save($backed_project)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 支援者にプロジェクト成功の連絡メールを送信する関数
     * @param array $project
     * @param array $backed_project
     * @return boolean
     */
    private function mail_exec_to_backer($project, $backed_project)
    {
        if(! $backed_project['BackedProject']['manual_flag']){
            $user = $this->User->findById($backed_project['BackedProject']['user_id']);
            $subject = $this->setting['Setting']['site_name'].' - '.EXEC_COMPLETE_SUBJECT;
            try{
                $Email = new CakeEmail('default');
                $Email->to($user['User']['email']);
                $Email->template('exec_complete');
                $Email->from($this->setting['Setting']['from_mail_address']);
                $Email->subject($subject);
                $setting = $this->setting['Setting'];
                $Email->viewVars(compact('user', 'project', 'backed_project', 'setting'));
                $Email->send();
            }catch(Exception $e){
                return false;
            }
        }
        return true;
    }

    /**
     * プロジェクトオーナーと管理者にプロジェクト終了の連絡メールを送信する関数
     * @param array $project
     * @return boolean
     */
    private function mail_fin_to_owner($project)
    {
        $user  = $this->User->findById($project['Project']['user_id']);
        $ok_ng = $this->check_ok_ng($project);
        $subject = $this->setting['Setting']['site_name'].' - '.PROJECT_FIN_SUBJECT;
        try{ //管理者向け
            $Email = new CakeEmail('default');
            $Email->to($this->setting['Setting']['admin_mail_address']);
            $Email->template('project_fin');
            $Email->from($this->setting['Setting']['from_mail_address']);
            $Email->subject('[管理]'.$subject);
            $setting = $this->setting['Setting'];
            $Email->viewVars(compact('user', 'project', 'ok_ng', 'setting'));
            $Email->send();
        }catch(Exception $e){
            //return false;
        }
        try{ //プロジェクトオーナー向け
            $Email = new CakeEmail('default');
            $Email->to($user['User']['email']);
            $Email->template('project_fin');
            $Email->from($this->setting['Setting']['from_mail_address']);
            $Email->subject($subject);
            $setting = $this->setting['Setting'];
            $Email->viewVars(compact('user', 'ok_ng', 'project', 'setting'));
            $Email->send();
        }catch(Exception $e){
            return false;
        }
        return true;
    }

    /**
     * 失敗したプロジェクトの支援全てに対して決済をキャンセルする関数
     * @param array $project
     * @return boolean
     */
    private function cancel_pay($project)
    {
        $backed_projects = $this->BackedProject->findAllByProjectId($project['Project']['id']);
        $result = true;
        foreach($backed_projects as $backed_project){
            $code = $this->_cancel_pay($backed_project);
            if($code == 3){ //失敗
                $result = false;
            }elseif($code == 1){ //成功
                //支援者にプロジェクト成功・支援完了のお知らせメール送信
                $this->mail_cancel_to_backer($project, $backed_project);
            }
        }
        //決済キャンセルが全て完遂している場合は処理を続行
        //途中でエラーになった場合は次回残りを処理できるようにする
        if($result){
            //ノンアクティブにする
            $this->Project->id = $project['Project']['id'];
            if($this->Project->saveField('active', 'no')){
                return true;
            }
        }
        return false;
    }

    /**
     * GMOペイメントの決済キャンセル処理
     * @param array $backed_project
     * @return int $result
     *  1 -> 処理成功 -> mailして次に進む
     *  2 -> 処理不要 -> mailせずに次に進む
     *  3 -> 処理失敗 -> mailせずに次に進まない
     */
    private function _cancel_pay($backed_project)
    {
        //ステータスが、仮売上登録状態のもののみ実施
        if($backed_project['BackedProject']['status'] != '仮売上完了'){
            return 2;
        }
        if(! $backed_project['BackedProject']['manual_flag']){
            $AlterTranInput = new AlterTranInput();
            $AlterTranInput->setAccessId($backed_project['BackedProject']['accessId']);
            $AlterTranInput->setAccessPass($backed_project['BackedProject']['accessPass']);
            $AlterTranInput->setJobCd('VOID');
            $AlterTranInput->setShopId($this->setting['Setting']['gmo_id']);
            $AlterTranInput->setShopPass($this->setting['Setting']['gmo_password']);
            $AlterTran = new AlterTran();
            $alter_tran_output = $AlterTran->exec($AlterTranInput);
        }else{
            $alter_tran_output = null;
        }
        if(!empty($alter_tran_output->getErrList())){
            return 3;
        }else{
            if($this->put_exec_info_to_backed_project('fail', $backed_project, $alter_tran_output)){
                return 1;
            }
            return 3;
        }
    }

    /**
     * 支援者にプロジェクト失敗の連絡メールを送信する関数
     * @param array $project
     * @param array $backed_project
     * @return boolean
     */
    private function mail_cancel_to_backer($project, $backed_project)
    {
        if(! $backed_project['BackedProject']['manual_flag']){
            $user = $this->User->findById($backed_project['BackedProject']['user_id']);
            $subject = $this->setting['Setting']['site_name'].' - '.CANCEL_COMPLETE_SUBJECT;
            try{
                $Email = new CakeEmail('default');
                $Email->to($user['User']['email']);
                $Email->template('cancel_complete');
                $Email->from($this->setting['Setting']['from_mail_address']);
                $Email->subject($subject);
                $setting = $this->setting['Setting'];
                $Email->viewVars(compact('user', 'project', 'backed_project', 'setting'));
                $Email->send();
            }catch(Exception $e){
                return false;
            }
        }
        return true;
    }

}
