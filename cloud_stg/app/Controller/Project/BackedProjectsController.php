<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
$vendor = App::path('Vendor');
$path   = $vendor[0].'gpay_client'.DS;
set_include_path(get_include_path().PATH_SEPARATOR.$path);
App::import('Vendor', 'gpay_client/com/gmo_pg/client/input/EntryTranInput');
App::import('Vendor', 'gpay_client/com/gmo_pg/client/input/ExecTranInput');
App::import('Vendor', 'gpay_client/com/gmo_pg/client/input/EntryExecTranInput');
App::import('Vendor', 'gpay_client/com/gmo_pg/client/tran/EntryExecTran');

class BackedProjectsController extends AppController
{
    public $uses = array(
            'Project', 'BackedProject', 'BackingLevel'
    );
    public $components = array('Mail');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('hoge');
    }

    /**
     * 1.決済画面
     * どのプロジェクトにどの支援パターンで決済するかという情報を引数に取る
     * 本画面では、実際の支援金額を入力し、決済会社のクレジットカード情報入力画面に移動する
     * @param int $backing_level_id
     * @param     int project_id
     */
    public function add($backing_level_id = null, $project_id = null)
    {
        list($pj, $bl) = $this->_init_check($project_id, $backing_level_id);
        if(!$pj || !$bl){
            return $this->redirect('/');
        }
        if($this->request->is('post') || $this->request->is('put')){
            if(!$this->_check_invest_amount_etc($bl)){
                return;
            }
            if($bl['BackingLevel']['delivery'] == 2){
                $this->User->id = $this->Auth->user('id');
                if(!$this->User->save($this->request->data, true, array('receive_address', 'name'))){
                    return $this->Session->setFlash('登録できませんでした。恐れ入りますが再度お試しください。');
                }
            }
            $this->_set_backed_info_to_session($project_id, $backing_level_id);
            $this->redirect(array('action' => 'card'));
        }else{
            $this->request->data['User']['receive_address'] = $this->auth_user['User']['receive_address'];
            $this->request->data['User']['name'] = $this->auth_user['User']['name'];
        }
    }

    /**
     * project、backingLevelの有効性チェック
     */
    private function _init_check($project_id, $backing_level_id)
    {
        if(empty($this->auth_user['User']['email'])){
            $this->Session->setFlash('支援には事前にメールアドレス認証を完了いただく必要がございます。');
            return false;
        }
        $project = $this->Project->check_backing_enable($project_id);
        if(!$project){
            return false;
        }
        $backing_level = $this->BackingLevel->check_backing_level($backing_level_id, $project_id);
        if(!$backing_level){
            return false;
        }
        $this->set(compact('backing_level', 'project'));
        return array(
                $project, $backing_level
        );
    }

    /**
     * 支援金額やリターン配送先住所の入力チェック
     */
    private function _check_invest_amount_etc($backing_level)
    {
        //支援金額のチェック
        $data = $this->request->data;
        if(empty($data['BackedProject']['invest_amount'])){
            $this->set('error', '支援金額を入力してください。');
            return false;
        }
        $invest_amount = $data['BackedProject']['invest_amount'];
        if(!$this->BackingLevel->check_invest_amount($backing_level, $invest_amount)){
            $this->set('error', '最低支援金額以上の金額を入力してください');
            return false;
        }
        if(!$this->BackingLevel->check_max_count($backing_level)){
            $this->set('error', 'OUT OF STOCK!');
            return false;
        }
        //配送方法が郵送で、配送先が空の場合はエラー
        $delivery = $backing_level['BackingLevel']['delivery'];
        if($delivery == 2){
            if(empty($data['User']['receive_address'])){
                $this->Session->setFlash('リターン配送先住所を入力してください');
                return false;
            }
            if(empty($data['User']['name'])){
                $this->Session->setFlash('氏名を入力してください');
                return false;
            }
        }
        return true;
    }

    /**
     * 支援情報をセッションに保存
     */
    private function _set_backed_info_to_session($project_id, $backing_level_id)
    {
        $bp = array(
                'user_id' => $this->Auth->user('id'), 'pj_id' => $project_id, 'bl_id' => $backing_level_id,
                'amount' => $this->request->data['BackedProject']['invest_amount'],
                'comment' => $this->request->data['BackedProject']['comment']
        );
        $this->Session->write('backed_project', $bp); //支援情報をセッションに登録
    }

    /**
     * 2.カード入力画面
     */
    public function card()
    {
        $bp = $this->Session->read('backed_project');
        if(empty($bp)){
            $this->redirect('/');
        }
        $this->set('backed_project', $bp);
        list($pj, $bl) = $this->_init_check($bp['pj_id'], $bp['bl_id']);
        if(!$pj || !$bl){
            $this->redirect('/');
        }
        //カードの有効期限のset
        $this->_set_date_for_card();
        //決済実行
        if($this->request->is('post') || $this->request->is('put')){
            //カード情報のバリデーションチェック
            if(!$this->_validation_card_info($this->request->data)){
                return;
            }
            $this->Project->begin();
            //プロジェクトの支援金額・支援人数を更新
            if($pj = $this->Project->add_backed_to_project($bp, $pj)){
                //支援パターンの購入数を更新
                if($this->BackingLevel->put_backing_level_now_count($bl)){
                    //GMOのINPUTにセット
                    $data  = $this->request->data['BackedProject'];
                    $input = $this->_set_gmo_input($data, $bp);
                    //決済実行
                    $output = $this->_exe_gmo($input);
                    if($output){
                        if($backed_project = $this->_put_info_of_exec_tran($bp, $output)){
                            $this->_mail_back_complete($backed_project, $pj);
                            $this->Project->commit();
                            $this->Session->setFlash('ありがとうございます！支援が完了しました！');
                            $this->redirect('/mypage');
                        }
                    }
                }
            }
            $this->Project->rollback();
            $this->Session->setFlash('決済登録に失敗しました。恐れ入りますが、再度お試しください。');
            $this->log('決済登録失敗：'.date('Y/m/d H:i'));
        }
    }

    /**
     * カードの有効期限のset
     */
    private function _set_date_for_card()
    {
        //カードの有効期限の年月セット
        $card_months = array(
                '01' => 1, '02' => 2, '03' => 3, '04' => 4, '05' => 5, '06' => 6, '07' => 7, '08' => 8, '09' => 9,
                '10' => 10, '11' => 11, '12' => 12
        );
        $card_years  = array();
        $now_year    = date('Y');
        for($i = 0; $i < 30; $i++){
            $card_years[substr($now_year, -2)] = $now_year;
            $now_year += 1;
        }
        $this->set(compact('card_months', 'card_years'));
    }

    /**
     * カード情報のバリデーションチェック関数
     * エラーがあったら$errorをセットする
     * @param array $card_info
     * @return boolean
     */
    private function _validation_card_info($card_info)
    {
        $error = array();
        if(empty($card_info['BackedProject']['card_no'])){
            $error[] = 'カード番号を入力してください';
        }
        if(empty($card_info['BackedProject']['code'])){
            $error[] = 'セキュリティーコードを入力してください';
        }
        if(empty($card_info['BackedProject']['month']) || empty($card_info['BackedProject']['year'])){
            $error[] = '有効期限を入力してください';
        }
        if(!empty($error)){
            $this->set(compact('error'));
            return false;
        }else{
            return true;
        }
    }

    /**
     * GMOのEntryExecTranInputを作成し返す
     * @param $data
     * @param $bp
     * @return EntryExecTranInput
     */
    private function _set_gmo_input($data, $bp)
    {
        $order_id   = $this->BackedProject->get_order_id($bp);
        $entryInput = new  EntryTranInput();
        $entryInput->setAmount($bp['amount']);
        $entryInput->setOrderId($order_id);
        $entryInput->setJobCd('AUTH');
        $entryInput->setShopId($this->setting['gmo_id']);
        $entryInput->setShopPass($this->setting['gmo_password']);
        $execInput = new ExecTranInput();
        $execInput->setOrderId($order_id);
        $execInput->setMethod('1');
        $execInput->setCardNo($data['card_no']);
        $execInput->setExpire($data['year'].$data['month']);
        $execInput->setSecurityCode($data['code']);
        $input = new EntryExecTranInput();
        $input->setEntryTranInput($entryInput);
        $input->setExecTranInput($execInput);
        return $input;
    }

    /**
     * GMOで決済実行（取引登録＋仮売上登録）
     */
    private function _exe_gmo($input)
    {
        $exe    = new EntryExecTran();
        $output = $exe->exec($input);
        //取引の処理そのものがうまくいかない（通信エラー等）場合
        if($exe->isExceptionOccured()){
            $this->set('error', array('決済サービスとの通信に失敗しました。大変恐れ入りますが、しばらく経ってから再度お試しください。'));
            return false;
        }else{
            //出力パラメータにエラーコードが含まれていないかチェック
            if(!empty($output->entryTranOutput->errList) || !empty($output->execTranOutput->errList)){
                $this->set('error', array('決済エラーが発生しました。恐れ入りますが再度お試しください。'));
                return false;
            }else{
                return $output;
            }
        }
    }

    /**
     * 決済完了時（仮売上完了時）のBackedProjectへのデータ登録関数
     * @param array  $backed_project
     * @param object $output
     * @return boolean
     */
    private function _put_info_of_exec_tran($backed_project, $output)
    {
        $bp = array(
                'BackedProject' => array(
                        'accessId' => $output->getAccessId(),
                        'accessPass' => $output->getAccessPass(),
                        'forward' => $output->getForward(),
						'approve' => $output->getApprovalNo(),
                        'transactionId' => $output->getTranId(),
                        'tranDate' => $output->getTranDate(),
                        'checkString' => $output->getCheckString(),
                        'orderId' => $output->getOrderId(),
						'user_id' => $backed_project['user_id'],
                        'project_id' => $backed_project['pj_id'],
						'backing_level_id' => $backed_project['bl_id'],
                        'invest_amount' => $backed_project['amount'],
						'comment' => $backed_project['comment'],
                        'status' => STATUS_KARIURIAGE
                )
        );
        $this->BackedProject->create();
        if($this->BackedProject->save($bp)){
            $this->Session->delete('backed_project');
            return $this->BackedProject->read();
        }else{
            $this->log('仮売上登録完了後のBackedProjectへのデータ登録が失敗しました。');
            $this->log($backed_project);
            return false;
        }
    }

    /**
     * プロジェクトオーナーと管理者と支援者に支援完了の連絡メールを送信する関数
     * @param array $backed_project
     * @param array $project
     * @return boolean
     */
    private function _mail_back_complete($backed_project, $project)
    {
        $owner  = $this->User->findById($project['Project']['user_id']);
        $backer = $this->User->findById($backed_project['BackedProject']['user_id']);
        $this->Mail->back_complete_owner($owner, $backer, $project, $backed_project, 'admin');
        $this->Mail->back_complete_owner($owner, $backer, $project, $backed_project, 'user');
        $this->Mail->back_complete_backer($backer, $project, $backed_project);
        return true;
    }

}
