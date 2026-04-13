<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class UsersController extends AppController
{

    public $uses = array(
        'User', 'Project', 'BackedProject', 'MailAuth'
    );
    public $components = array('Mail');
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow(array('view'));
        if($this->action === 'edit'){
            //ajaxフォーム対策（token複数回利用可など）
            $this->Security->validatePost = false;
            $this->Security->csrfUseOnce  = false;
        }
    }

    /**
     * プロフィール画面
     * @param int    $user_id
     * @param string $mode (backed or registered) ※backedはnullでもよい
     * @return \CakeResponse
     */
    public function view($user_id = null, $mode = null)
    {
        $user = $this->User->findById($user_id);
        if(!$user || !$user['User']['active']){
            $this->redirect('/');
        }
        $this->set(compact('user'));
        $this->set('categories', $this->Project->Category->find('list'));
        if(!$mode || $mode == 'backed'){ //ユーザーが特定サイトで支援したプロジェクト一覧
            //サブメニュー用変数
            $this->set('menu_mode', 'backed');
            //プロジェクト取得
            $this->paginate = $this->Project->get_back_pj_of_user($user_id, 'options', 'all', 10);
            $this->set('projects', $this->paginate('Project'));
            return $this->render('view', 'profile');
        }else{
            //サブメニュー用変数
            $this->set('menu_mode', 'registered');
            //作成したプロジェクト一覧取得
            $this->paginate = $this->Project->get_pj_of_user($user_id, 'options', 'all', 10);
            $this->set('projects', $this->paginate('Project'));
            return $this->render('registered', 'profile');
        }
    }

    /**
     * プロフィール編集画面
     * @throws NotFoundException
     * @return void
     */
    public function edit()
    {
        $this->layout = 'mypage';
        //ajaxアクセス
        if($this->request->is('post') || $this->request->is('put')){
            $this->layout                      = 'ajax';
            $this->autoRender                  = false;
            $this->request->data['User']['id'] = $this->auth_user['User']['id'];
            if(!empty($this->request->params['form']['img'])){
                $this->request->data['User']['img'] = $this->request->params['form']['img'];
            }else{
                $this->request->data['User']['img'] = null;
            }
            $this->Ring->bindUp('User');
            $this->User->begin();
            if($this->User->save($this->request->data, true, array(
                    'name', 'nick_name', 'address', 'self_description', 'url1', 'url2', 'url3', 'receive_address', 'img'
            ))
            ){
                $change_mail = false;
                if($this->auth_user['User']['email'] != $this->request->data['User']['email']){
                    if(empty($this->request->data['User']['email'])){
                        return json_encode(array(
                                'status' => 0, 'msg' => 'メールアドレスを入力してください'
                        ));
                        //return $this->Session->setFlash( 'メールアドレスを入力してください' );
                    }
                    $change_mail = true;
                    $result      = $this->change_email($this->request->data['User']['email'], $this->Auth->user('id'));
                    if($result > 1){
                        if($result == 3){
                            $this->User->rollback();
                            return json_encode(array(
                                    'status' => 0, 'msg' => 'このメールアドレスは既に登録されています'
                            ));
                            //$this->set('result', 'このメールアドレスは既に登録されています');
                        }elseif($result == 2){
                            $this->User->rollback();
                            return json_encode(array(
                                    'status' => 0, 'msg' => 'メールの登録ができませんでした。恐れ入りますが再度お試しください。'
                            ));
                            //$this->set('result', 'メールの登録ができませんでした。恐れ入りますが再度お試しください。');
                        }
                    }
                }
                $this->User->commit();
                $this->Session->write('Auth', $this->User->read(null, $this->Auth->User('id')));
                if($change_mail){
                    return json_encode(array(
                            'status' => 0, 'msg' => 'メールアドレス認証用URLをお送りいたしました'
                    ));
                    //$this->set('result', 'メールアドレス認証用URLをお送りいたしました');
                }else{
                    return json_encode(array(
                            'status' => 0, 'msg' => 'プロフィールを更新しました。'
                    ));
                    //$this->set('result', 'プロフィールを更新しました。');
                }
                //return $this->render('edit_ajax');
            }
            $this->User->rollback();
            //$this->set('result', 'プロフィールが保存できませんでした。恐れ入りますが、再度お試しください。');
            return json_encode(array(
                    'status' => 0, 'msg' => 'プロフィールが保存できませんでした。恐れ入りますが、再度お試しください。'
            ));
            //return $this->render('edit_ajax');
        }else{
            $this->request->data = $this->auth_user;
        }
    }

    /**
     * メールアドレス変更時の処理
     * @param str $email
     * @param int $user_id
     * @return int 1 -> ok, 2 -> ng, 3 -> 他ユーザ登録済み
     */
    private function change_email($email, $user_id)
    {
        if($this->User->get_user_from_email($email)){
            return 3;
        }
        $url = $this->MailAuth->make_mail_auth_and_url_for_change($email, $user_id);
        if($url){
            if($this->Mail->confirm_mail_address($email, $url)){
                return 1;
            }
        }
        return 2;
    }

    /**
     * マイページ
     * 支援したプロジェクト一覧も表示（ページネーション）
     */
    public function mypage()
    {
        $this->layout = 'mypage';
        $this->set('mypage_top', true);
        $this->set('menu_mode', 'backed'); //マイページのサブメニュー用
        //支援したプロジェクト一覧
        $this->paginate = $this->Project->get_back_pj_of_user($this->Auth->user('id'), 'options', 'all', 10);
        $projects       = $this->paginate('Project');
        $this->set(compact('projects'));
        $this->set('categories', $this->Project->Category->find('list'));
    }

    /**
     * パスワード変更画面
     */
    public function change_password()
    {
        $this->layout = 'mypage';
        if($this->request->is('post') || $this->request->is('put')){
            $this->User->id = $this->Auth->user('id');
            if($this->User->save($this->request->data, true, array('password'))){
                $this->Session->setFlash(__('パスワードを変更しました。'));
                $this->redirect(array('action' => 'edit'));
            }else{
                $this->Session->setFlash(__('パスワードが保存できませんでした。恐れ入りますが、再度お試しください。'));
            }
        }
        $user = $this->User->findById($this->Auth->user('id'));
        $this->set(compact('user'));
    }

    /**
     * 退会
     */
    public function delete($user_id)
    {
        if(!$this->request->is('post') || $this->Auth->user('id') != $user_id){
            return $this->redirect('/');
        }
        if($this->_chk_user_status($user_id)){
            if($this->User->withdraw($user_id)){
                $this->Auth->logout();
                $this->Session->setFlash('退会処理が完了しました。ご利用ありがとうございました。');
                return $this->redirect('/');
            }else{
                $this->Session->setFlash('退会処理に失敗しました。恐れ入りますが、再度お試しください。');
                return $this->redirect(array('action' => 'edit'));
            }
        }else{
            $this->Session->setFlash('プロジェクト作成や支援をされている場合、退会できません。');
            return $this->redirect(array('action' => 'edit'));
        }
    }

    /**
     * ユーザ状態チェック
     * プロジェクトを公開していないか？
     * プロジェクトに支援していないか？
     * @params int $user_id
     * @return bool (trueなら削除可能)
     */
    private function _chk_user_status($user_id)
    {
        if($this->Project->chk_user_opened_pj($user_id)){
            if(!$this->Project->BackedProject->findByUserId($user_id)){
                return true;
            }
        }
        return false;
    }

}
