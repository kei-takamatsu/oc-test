<?php
App::uses('AppController', 'Controller');

class AdminProjectsController extends AppController
{
    public $uses = array('Project', 'Area');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('hoge');
        //Ajax時の対応
        if($this->action === 'edit_design'
           || $this->action === 'edit_design_top'
           || $this->action === 'admin_edit_thumb'
           || $this->action === 'admin_edit_detail'
           || $this->action === 'admin_create'
        ){
            $this->Security->validatePost = false;
            $this->Security->csrfUseOnce  = false;
            $this->Security->csrfCheck    = false;
        }
        if($this->action === 'admin_edit_level'){
            $this->Security->validatePost = false;
            $this->Security->csrfCheck    = false;
        }
        $this->layout = 'admin';
    }

    /**
     * サイト管理 - プロジェクト一覧
     */
    public function admin_index()
    {
        $this->set('categories', $this->Project->Category->find('list'));
        $conditions = array();
        if(isset($this->request->query['search_id'])){
            if(!empty($this->request->query['search_id'])){
                $conditions[]['AND'] = array('Project.id' => $this->request->query['search_id']);
            }
        }
        $this->Project->recursive = 0;
        $this->paginate           = array(
                'order' => 'Project.created desc', 'limit' => '20'
        );
        $this->set('projects', $this->paginate('Project', $conditions));
    }

    /**
     * プロジェクトの作成関数（ユーザは自分になる）
     * 画像必須（プロジェクト一覧でgrid表示するときに崩れるから）
     */
    public function admin_create()
    {
        //メアド登録チェック
        if(empty($this->auth_user['User']['email'])){
            $this->Session->setFlash('プロジェクトの作成は、メール認証を完了させていただく必要がございます。');
            $this->redirect($this->referer());
        }
        if($this->request->is('post') || $this->request->is('put')){
            $this->layout                              = 'ajax';
            $this->autoRender                          = false;
            $this->request->data['Project']['user_id'] = $this->auth_user['User']['id'];
            $this->request->data['Project']['rule']    = '1';
            $this->request->data['Project']['contact'] = 'ご自分で作成したプロジェクトです';
            $this->request->data['Project']['return']  = 'ご自分で作成したプロジェクトです';
            if(!empty($this->request->params['form']['pic'])){
                $this->request->data['Project']['pic'] = $this->request->params['form']['pic'];
            }else{
                $this->request->data['Project']['pic'] = null;
            }
            //全項目入力されているかチェック
            if(empty($this->request->data['Project']['project_name'])
               || empty($this->request->data['Project']['pic'])
               || empty($this->request->data['Project']['category_id'])
               || empty($this->request->data['Project']['goal_amount'])
               || empty($this->request->data['Project']['description'])
            ){
                return json_encode(array(
                        'status' => 0, 'msg' => '必須項目を全て入力してください'
                ));
            }
            //目標金額が数値かチェック
            if(!is_numeric($this->request->data['Project']['goal_amount'])){
                return json_encode(array(
                        'status' => 0, 'msg' => '目標金額は数値を入力してください'
                ));
            }
            //目標金額が10万円以上かチェック
            if($this->request->data['Project']['goal_amount'] < 100000){
                return json_encode(array(
                        'status' => 0, 'msg' => '目標金額は10万円以上に設定してください'
                ));
            }
            $this->Ring->bindUp('Project');
            $this->Project->create();
            if($this->Project->save($this->request->data, true, array(
                    'user_id', 'rule', 'contact', 'return', 'pic', 'category_id', 'area_id',
                    'goal_amount', 'project_name', 'description'
            ))
            ){
                $this->Session->setFlash('プロジェクトを作成しました');
                return json_encode(array(
                        'status' => 1, 'msg' => 'プロジェクトを作成しました'
                ));
            }else{
                return json_encode(array(
                        'status' => 0, 'msg' => 'プロジェクトが作成できませんでした。恐れ入りますが再度お試しください。'
                ));
            }
        }
        $this->set('categories', $this->Project->Category->get_list());
        $this->set('areas', $this->Area->get_list());
    }

    /**
     * 管理者プロジェクト編集画面（基本情報）
     * @param string $id
     * @throws NotFoundException
     * @return void
     */
    public function admin_edit($id = null)
    {
        $this->Project->recursive = 0;
        $project                  = $this->Project->findById($id);
        if(!$project){
            $this->redirect('/');
        }
        $this->set(compact('project'));
        if($this->request->is('post') || $this->request->is('put')){
            $this->request->data['Project']['id'] = $id;
            $fields                               = array(
                    'project_name', 'category_id', 'area_id', 'description',
                    'goal_amount', 'collection_term', 'modified', 'return', 'contact'
            );
            $this->Project->begin();
            $this->Project->id = $id;
            if($this->Project->save($this->request->data, true, $fields)){
                $this->Project->commit();
                $this->Session->setFlash('プロジェクトを更新しました');
                $this->redirect(array(
                        'action' => 'admin_edit', $id
                ));
            }else{
                $this->Session->setFlash('プロジェクトが更新できませんでした。恐れ入りますが再度お試しください。');
                $this->_set_project_option();
            }
            $this->Project->rollback();
        }else{
            $this->request->data = $project;
            $this->_set_project_option();
        }
    }

    /**
     * プロジェクト追加・編集時のオプションのセット関数
     */
    private function _set_project_option()
    {
        $this->set('categories', $this->Project->Category->get_list());
        $this->set('areas', $this->Area->get_list());
        $this->_set_time_options();
    }

    /**
     * プロジェクト開始・終了日時のタイムオプション
     * @param bool $value
     */
    private function _set_time_options($value = true)
    {
        $start_option = array(
                'minYear' => date('Y'), 'maxYear' => date('Y'), 'separator' => array(
                        '年', '月', '日'
                ), 'monthNames' => false, 'required' => true
        );
        $end_option   = array(
                'minYear' => date('Y'), 'maxYear' => date('Y') + 1, 'separator' => array(
                        '年', '月', '日'
                ), 'monthNames' => false, 'required' => true
        );
        //第一引数がfalseの場合はvalue設定を削除
        if(!$value){
            unset($start_option['value']);
            unset($end_option['value']);
        }
        $this->set(compact('start_option', 'end_option'));
    }

    /**
     * プロジェクトの作成ユーザーの変更
     */
    public function admin_change_user($pj_id, $user_id = null)
    {
        $this->Project->recursive = 0;
        $project                  = $this->Project->findById($pj_id);
        if(!$project){
            $this->redirect('/');
        }
        $this->set(compact('project'));
        if($this->request->is('post') || $this->request->is('put')){
            if(!empty($user_id)){
                //ユーザの変更
                if($this->Project->change_user($pj_id, $user_id)){
                    $this->Session->setFlash('ユーザーを変更しました。');
                    //リダイレクト
                    return $this->redirect('/admin/admin_projects/edit/'.$pj_id);
                }else{
                    return $this->Session->setFlash('登録に失敗しました。恐れ入りますが、再度お試しください。');
                }
            }else{
                //ユーザー検索
                $email          = !empty($this->request->data['User']['email_like']) ? $this->request->data['User']['email_like'] : null;
                $this->paginate = $this->Project->User->get_users_by_email($email, true, 30);
                $users          = $this->paginate('User');
                return $this->set(compact('users'));
            }
        }else{
            //ユーザ一覧の取得
            $this->paginate = $this->Project->User->get_all_users(true, 30);
            $users          = $this->paginate('User');
            return $this->set(compact('users'));
        }
    }

    /**
     * 管理者プロジェクト編集画面（サムネイル画像）
     */
    public function admin_edit_thumb($id = null)
    {
        $project = $this->Project->findById($id);
        if(empty($project)){
            $this->redirect('/');
        }
        $this->set(compact('project'));
        if($this->request->is('post') || $this->request->is('put')){
            if(!$project){
                $this->set('result', 'ERROR');
                return $this->render('edit_ajax');
            }
            $this->layout     = 'ajax';
            $this->autoRender = false;
            if(!empty($this->request->params['form']['pic'])){
                $this->request->data['Project']['pic'] = $this->request->params['form']['pic'];
            }else{
                $this->request->data['Project']['pic'] = null;
            }
            $this->Project->id                    = $id;
            $this->request->data['Project']['id'] = $id;
            $this->Ring->bindUp('Project');
            if($this->Project->save($this->request->data, true, array(
                    'thumbnail_type', 'thumbnail_movie_code', 'thumbnail_movie_type', 'pic'
            ))
            ){
                return json_encode(array(
                        'status' => 1, 'msg' => 'プロジェクトを更新しました'
                ));
            }else{
                return json_encode(array(
                        'status' => 0, 'msg' => '更新に失敗しました。恐れ入りますが再度お試しください。'
                ));
            }
        }else{
            if(!$project){
                $this->redirect('/');
            }
            $this->request->data = $project;
        }
    }

    /**
     * 管理者プロジェクト編集画面（プロジェクト詳細）
     */
    public function admin_edit_detail($project_id = null)
    {
        $project = $this->Project->findById($project_id);
        if(!$project){
            $this->redirect('/');
        }
        $this->set(compact('project'));
        //project_contentの取得
        $project_contents = $this->Project->ProjectContent->get_contents($project_id);
        $this->set(compact('project_contents'));
    }

    /**
     * 管理者プロジェクト編集画面（支援パターン）
     */
    public function admin_edit_level($id = null)
    {
        $project = $this->Project->get_pj_by_id($id, array('BackingLevel'));
        if(!$project){
            $this->redirect('/');
        }
        $this->set(compact('project'));
        if($this->request->is('post') || $this->request->is('put')){
            $max_level = $this->request->data['Project']['max_back_level'];
            $this->Project->begin();
            $this->Project->id = $id;
            if($this->Project->saveField('max_back_level', $max_level)){
                if($this->Project->BackingLevel->edit_backing_level($this->request->data['BackingLevel'], $id)){
                    $this->Project->commit();
                    $this->Session->setFlash('プロジェクトを保存しました');
                    return $this->redirect('/admin/admin_projects/edit_level/'.$id);
                }
            }
            $this->Project->rollback();
            $this->Session->setFlash('プロジェクトを保存できませんでした。');
        }else{
            $this->request->data = $project;
        }
    }

    /**
     * プロジェクト削除（管理者画面）
     * - opened yesは削除できない
     * - backers が1以上は削除できない
     * @param string $project_id
     */
    public function admin_delete($project_id = null)
    {
        $result = $this->Project->delete_project($project_id);
        switch($result[0]){
            case 1:
                $this->Session->setFlash('プロジェクトを削除しました');
                break;
            case 2:
                $this->Session->setFlash('プロジェクトが削除できませんでした');
                break;
            case 3:
                $this->Session->setFlash($result[1]);
        }
        $this->redirect('/admin/admin_projects/');
    }

    /**
     * テストプロジェクト削除（管理者画面）
     *  - 条件問わず削除可能
     *  - 関連するお気に入り、支援パターン、支援データ、PJ詳細コンテンツ、活動報告もすべて削除される
     */
    public function admin_delete_test_pj($id = null)
    {
        if(! $this->request->is('post')) $this->redirect('/');
        $pj = $this->Project->findById($id);
        if(! $pj) $this->redirect('/');
        $this->Project->begin();
        if($this->Project->delete_test_pj($id)){
            $this->Project->commit();
            $this->Session->setFlash('プロジェクトを削除しました');
            return $this->redirect('/admin/admin_projects');
        }
        $this->Project->rollback();
        $this->Session->setFlash('プロジェクトが削除できませんでした。');
        return $this->redirect('/admin/admin_projects');
    }

    /**
     * プロジェクトのトップページ表示に関する設定画面
     */
    public function admin_toppage()
    {
        if($this->request->is('post') || $this->request->is('put')){
            if(!empty($this->request->data['Project']['project_name'])){
                $this->paginate = $this->Project->get_pj_by_name($this->request->data['Project']['project_name']);
                return $this->set('projects', $this->paginate('Project'));
            }
        }
        $this->paginate = $this->Project->get_pj('options', 'open', 10, -1);
        $this->set('projects', $this->paginate('Project'));
    }

    /**
     * トップページのピックアッププロジェクトに設定する関数
     */
    public function admin_set_pickup($project_id = null)
    {
        $result = $this->Setting->set_pickup_project($project_id);
        if($result){
            $this->Session->setFlash('ピックアッププロジェクトに設定しました。');
        }else{
            $this->Session->setFlash('ピックアッププロジェクトの設定に失敗しました。恐れ入りますが、再度お試しください。');
        }
        $this->redirect(array('action' => 'admin_toppage'));
    }

    /**
     * トップページのピックアッププロジェクトを解除する関数
     */
    public function admin_unset_pickup()
    {
        $result = $this->Setting->unset_pickup_project();
        if($result){
            $this->Session->setFlash('ピックアッププロジェクトを解除しました。');
        }else{
            $this->Session->setFlash('ピックアッププロジェクトの解除に失敗しました。恐れ入りますが、再度お試しください。');
        }
        $this->redirect(array('action' => 'admin_toppage'));
    }

    /**
     * トップページの優先表示プロジェクトに設定する関数
     */
    public function admin_set_top($project_id = null)
    {
        $result = $this->Setting->set_top_project($project_id);
        if($result){
            $this->Session->setFlash('優先表示プロジェクトに設定しました。');
        }else{
            $this->Session->setFlash('優先表示プロジェクトの設定に失敗しました。恐れ入りますが、再度お試しください。');
        }
        $this->redirect(array('action' => 'admin_toppage'));
    }

    /**
     * トップページの優先表示プロジェクトを解除する関数
     */
    public function admin_unset_top($project_id = null)
    {
        $result = $this->Setting->unset_top_project($project_id);
        if($result){
            $this->Session->setFlash('優先表示プロジェクトを解除しました。');
        }else{
            $this->Session->setFlash('優先表示プロジェクトの解除に失敗しました。恐れ入りますが、再度お試しください。');
        }
        $this->redirect(array('action' => 'admin_toppage'));
    }

    /**
     * プロジェクト公開設定（管理画面）
     * @param int $project_id
     */
    public function admin_open($project_id = null)
    {
        $result = $this->Project->project_open($project_id, $this->setting);
        switch($result[0]){
            case 1:
                $this->Session->setFlash('公開しました');
                break;
            case 2:
                $this->Session->setFlash('公開できませんでした。恐れ入りますが再度お試しください。');
                break;
            case 3:
                $this->Session->setFlash($result[1]);
        }
        return $this->redirect($this->referer());
    }

    /**
     * プロジェクトの停止
     */
    public function admin_stop($project_id)
    {
        if($this->Project->project_stop($project_id)){
            $this->Session->setFlash('公開を停止しました');
        }else{
            $this->Session->setFlash('公開を停止できませんでした。恐れ入りますが再度お試しください');
        }
        return $this->redirect($this->referer());
    }

    /**
     * プロジェクトの再開
     */
    public function admin_restart($project_id)
    {
        if($this->Project->project_restart($project_id)){
            $this->Session->setFlash('公開を再開しました');
        }else{
            $this->Session->setFlash('公開を再開できませんでした。恐れ入りますが再度お試しください');
        }
        return $this->redirect($this->referer());
    }

    /**
     * 支援の管理（募集中 or 成功プロジェクトの一覧）
     * $mode引数の切り替えにより、募集中プロジェクト、成功プロジェクトの表示が切り替えられる
     * @param string $mode (wanted, success)
     */
    public function admin_open_projects($mode = 'wanted')
    {
        if($mode == 'success'){
            $this->paginate = $this->Project->get_pj('options', 'all', 30, -1, 'success');
            $title          = '成功プロジェクト';
        }else{
            $this->paginate = $this->Project->get_wanted_pj('options', 30, -1);
            $title          = '募集中プロジェクト';
        }
        $projects = $this->paginate('Project');
        $this->set(compact('projects', 'title', 'mode'));
    }

    /**
     * プロジェクトの支援者一覧を表示する
     */
    public function admin_backers($project_id)
    {
        $project = $this->Project->findById($project_id);
        if(!$project){
            $this->redict('/');
        }
        $this->paginate = $this->Project->BackedProject->get_backers_of_project($project_id, 'options', 30);
        $backers        = $this->paginate('User');
        $this->set(compact('backers', 'project'));
    }

    /**
     * 成功したプロジェクトの支援者一覧をCSVダウンロードする
     */
    public function admin_csv_backers($project_id)
    {
        $project = $this->Project->findById($project_id);
        if(!$project){
            $this->redict('/');
        }
        $backers      = $this->Project->BackedProject->get_backers_of_project($project_id, 'all', 10000);
        $this->layout = false;
        $filename     = $project['Project']['project_name'].'支援者一覧_'.date('Ymd');
        // 表の一行目を作成
        $th = array(
                'ユーザー名', '氏名', 'メールアドレス', '住所', '支援日', '支援金額', '支援パターン', 'リターン内容', '支援方法', '支援コメント'
        );
        $this->set(compact('filename', 'th', 'backers'));
    }

}
