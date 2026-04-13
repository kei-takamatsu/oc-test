<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class UsersController extends LogiAuthAppController
{
    public $uses = array('User', 'MailAuth', 'Setting');
    public $components = array('LogiAuth.Fb', 'LogiAuth.Login', 'Mail');
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('login', 'forgot_pass', 'reset_pass', 'register',
            'confirm_mail', 'send_confirm_mail', 'mail_auth', 'reset_account_lock', 'reset_lock');
    }

    /**
     * ログイン
     */
    public function login()
    {
        $this->set('title', 'ログイン');
        $this->set('login_page', 1);
        if($this->Auth->user('id')){
            return $this->Login->redirect_referer();
        }
        $this->Fb->set_facebook_login_url();
        if(!$this->request->is('post')){
            return $this->Login->set_referer();
        }
        if(!$this->Auth->login()){ //ログイン失敗
            $count = $this->User->up_login_try_count($this->request->data['User']['email']);
            if($count == 10){ //ログイン試行回数が10回のときのみ、アカウントロックされてるよメールを自動送信する
                $user = $this->User->get_user_from_email($this->request->data['User']['email']);
                $token = $this->User->make_token();
                $url = h(Router::url('/', true)).'reset_lock/'.$token;
                $this->User->id = $user['User']['id'];
                if($this->User->saveField('token', $token)){
                    if($this->Mail->reset_lock($user['User']['email'], $url)){
                        return $this->Session->setFlash('アカウントがロックされました。メールをご確認いただき、アカウントロックを解除してください。');
                    }
                }
            }
            return $this->Session->setFlash('ログイン情報に誤りがあります！');
        }else{ //ログイン成功
            //ログイン回数（アカウントロック）チェック
            if($this->Auth->user('login_try_count') > 9){
                $this->Auth->logout();
                $this->set('account_lock', true);
                return $this->Session->setFlash(__('アカウントがロックされています。メールアドレスに解除URLを送付しておりますので、ロックを解除をしてください。'));
            }else{
                //ログイン試行回数のリセット
                $this->User->reset_login_try_count($this->Auth->user('id'));
                //DEBUG
                //$this->log('ログインしました！');
                return $this->Login->redirect_referer();
            }
        }
    }

    /**
     * ログアウト
     */
    public function logout()
    {
        $this->redirect($this->Auth->logout());
    }

    /**
     * パスワード再設定依頼画面
     */
    public function forgot_pass()
    {
        $this->set('login_page', 1);
        if($this->Auth->user('id')){
            return $this->Login->redirect_referer();
        }
        if($this->request->is('post') || $this->request->is('put')){
            if(empty($this->request->data['User']['email'])){
                return $this->redirect('/login');
            }
            $user = $this->User->get_user_from_email($this->request->data['User']['email']);
            if(!$user || !$user['User']['active']){
                return $this->Session->setFlash('このメールアドレスは登録されておりません');
            }
            $token = $this->User->make_token();
            $url = h(Router::url('/', true)).'reset_pass/'.$token;
            $this->User->id = $user['User']['id'];
            if($this->User->saveField('token', $token)){
                if($this->Mail->forgot_pass($user, $url)){
                    $this->Session->setFlash('パスワード再設定についてメールにてご連絡いたしました。');
                    $this->redirect(array('action' => 'login'));
                }
            }
            $this->Session->setFlash('パスワード再発行処理に失敗しました。');
        }
    }

    /**
     * パスワード再設定画面
     */
    public function reset_pass($token = null)
    {
        $this->set('login_page', 1);
        if($this->Auth->user('id')){
            return $this->Login->redirect_referer();
        }
        if(empty($token)) $this->redirect('/');
        $user = $this->User->get_user_from_token($token);
        if(!$user) $this->redirect('/');
        if($this->request->is('post') || $this->request->is('put')){
            $this->request->data['User']['token'] = null;
            $this->User->id = $user['User']['id'];
            if($this->User->save($this->request->data, true, array('token', 'password'))){
                $this->Session->setFlash(__('パスワードを再設定しました。'));
                $this->Mail->reset_pass_complete($user);
                $this->redirect(array('action' => 'login'));
            }else{
                $this->Session->setFlash(__('パスワードが設定できませんでした。恐れ入りますが再度お試しください。'));
            }
        }
    }

    /**
     * アカウントロック解除　申請画面
     */
    public function reset_account_lock()
    {
        $this->set('login_page', 1);
        if($this->Auth->user('id')){
            return $this->Login->redirect_referer();
        }
        if($this->request->is('post') || $this->request->is('put')){
            if(empty($this->request->data['User']['email'])){
                return $this->redirect('/login');
            }
            $user = $this->User->get_user_from_email($this->request->data['User']['email']);
            if(!$user || $user['User']['login_try_count'] < 10 || !$user['User']['active']){
                return $this->Session->setFlash('メールアドレスが登録されていないか、あるいは、アカウントがロックされていません');
            }
            $token = $this->User->make_token();
            $url = h(Router::url('/', true)).'reset_lock/'.$token;
            $this->User->id = $user['User']['id'];
            if($this->User->saveField('token', $token)){
                if($this->Mail->reset_lock($user['User']['email'], $url)){
                    $this->Session->setFlash('アカウントロック解除についてメールにてご連絡いたしました。');
                    $this->redirect(array('action' => 'login'));
                }
            }
            $this->Session->setFlash('アカウントロック解除処理に失敗しました。恐れ入りますが、再度お試しください。');
        }
    }

    /**
     * アカウントロック解除
     */
    public function reset_lock($token = null)
    {
        if($this->Auth->user('id')){
            return $this->Login->redirect_referer();
        }
        if(empty($token)) $this->redirect('/');
        $user = $this->User->get_user_from_token($token);
        if(!$user) $this->redirect('/');
        if($this->User->reset_login_try_count($user['User']['id'])){
            $this->Session->setFlash(__('アカウントロックを解除しました。'));
        }else{
            $this->Session->setFlash(__('アカウントロック解除に失敗しました。恐れ入りますが、再度お試しください。'));
        }
        return $this->redirect('/login');
    }

    /**
     * メール登録画面（ユーザ新規登録時）
     */
    public function mail_auth()
    {
        $this->set('title', 'アカウント登録');
        $this->set('login_page', 1);
        if($this->Auth->user('id')){
            return $this->Login->redirect_referer();
        }
        $this->Fb->set_facebook_login_url();
        if($this->request->is('post') || $this->request->is('put')){
            $email = $this->request->data['User']['email'];
            if(empty($email)){
                return $this->Session->setFlash('メールアドレスを入力してください');
            }
            $user = $this->User->get_user_from_email($email);
            if($user && $user['User']['active']){
                return $this->Session->setFlash('このメールアドレスは既に登録されています');
            }
            $this->MailAuth->begin();
            $token = $this->MailAuth->make_token();
            if($this->MailAuth->make_mail_auth($token, $email)){
                $url = $this->MailAuth->make_confirm_url($token);
                if($this->Mail->confirm_mail_address($email, $url)){
                    $this->MailAuth->commit();
                    $this->Session->setFlash('新規登録用のメールをお送りしました');
                    return $this->redirect('/mail_auth');
                }
            }
            $this->MailAuth->rollback();
            $this->Session->setFlash('メールアドレスが登録できませんでした。恐れ入りますが再度お試しください。');
        }
    }

    /**
     * メール認証画面（新規登録かメール変更完了）
     * mail_authにuser_idがあればメール変更。なければ新規登録
     */
    public function confirm_mail($token = null)
    {
        $this->set('login_page', 1);
        if(!$token) return $this->redirect('/login');
        $mail_auth = $this->MailAuth->get_mail_auth_from_token($token);
        if(!$mail_auth) return $this->redirect('/login');
        if(!empty($mail_auth['MailAuth']['user_id'])){
            $this->change_email_complete($mail_auth);
        }else{
            $this->register($mail_auth);
        }
    }

    /**
     * メール認証後のメールアドレス変更処理
     * @param $mail_auth
     */
    private function change_email_complete($mail_auth)
    {
        $this->set('login_page', 1);
        //メールアドレス変更
        switch($this->MailAuth->change_email($mail_auth)){
            case 1: //成功
                $this->Session->setFlash('メールアドレスを登録しました');
                break;
            case 2: //Error
                $this->Session->setFlash('メールアドレスが登録できませんでした。恐れ入りますが再度お試しください');
                break;
            case 3: //他ユーザで登録済み
                $this->Session->setFlash('このメールアドレスは登録されています');
        }
        return $this->redirect('/');
    }

    /**
     * メール認証後の新規登録
     * @param $mail_auth
     */
    private function register($mail_auth)
    {
        $this->set('login_page', 1);
        if($this->Auth->user('id')){
            return $this->Login->redirect_referer();
        }
        if($this->request->is('post') || $this->request->is('put')){
            $this->request->data['User']['group_id']  = USER_ROLE;
            $this->request->data['User']['email']     = $mail_auth['MailAuth']['email'];
            $this->request->data['User']['password2'] = $this->request->data['User']['password'];
            unset($this->request->data['User']['id']);
            $this->User->create();
            $this->User->begin();
            if($this->User->save($this->request->data, true, array(
                'id', 'group_id', 'nick_name', 'email', 'password', 'created', 'modified')))
            {
                if($this->MailAuth->delete_mail_auth($mail_auth)){
                    $this->User->commit();
                    $user = $this->User->read();
                    $this->Mail->registered($user, 'user');
                    $this->Mail->registered($user, 'admin');
                    $this->Session->setFlash('登録が完了しました！');
                    //手動ログイン
                    $user = $this->User->read();
                    $this->Auth->login($user['User']);
                    return $this->redirect('/');
                }
            }
            $this->User->rollback();
        }
        $this->render('register');
    }

    /**
     * ソーシャルアカウント連携画面
     */
    public function social()
    {
        $this->layout = 'mypage';
        $this->set('user', $this->auth_user);
        $this->Fb->set_facebook_login_url();
    }

    /**
     * ソーシャル連携解除・退会関数
     * @param string $action ('withdraw', 'unlinkFacebook', 'unlinkTwitter')
     * @return void
     */
    public function deactive($action = null)
    {
        $this->set('login_page', 1);
        if(!$this->request->is('post')) $this->redirect('/');
        $this->User->id = $this->Auth->user('id');
        $flag = true;
        if($action == 'withdraw'){ //退会
            if(!$this->User->saveField('active', 0)) $flag = false;
            $this->Session->setFlash('退会しました');
        }elseif($action == 'unlinkFacebook'){ //Facebook解除
            if(!$this->User->saveField('facebook_id', '')) $flag = false;
            $this->Session->setFlash('Facebookアカウントの連携を解除しました。');
        }elseif($action == 'unlinkTwitter'){ //Twitter解除
            if(!$this->User->saveField('twitter_id', '')) $flag = false;
            $this->Session->setFlash('Twitterアカウントの連携を解除しました。');
        }
        if($flag){
            if($action == 'withdraw') $this->Auth->logout();
            $this->redirect('/social');
        }else{
            $this->Session->setFlash(__('処理に失敗しました。恐れ入りますが再度お試しください。'));
            return $this->Login->redirect_referer();
        }
    }

    /**
     * 管理者ログイン画面
     */
    public function admin_login()
    {
        $this->layout = 'admin_login';
        $this->set('login_page', 1);
        if($this->request->is('post')){
            $this->Auth->logout();
            if($this->Auth->login()){
                if($this->Auth->user('group_id') == ADMIN_ROLE){
                    //ログイン回数（アカウントロック）チェック
                    if($this->Auth->user('login_try_count') > 9){
                        $this->Auth->logout();
                        $this->set('account_lock', true);
                        return $this->Session->setFlash(__('アカウントがロックされています。メールアドレスに解除URLを送付しておりますので、ロックを解除をしてください。'));
                    }else{
                        //ログイン試行回数のリセット
                        $this->User->reset_login_try_count($this->Auth->user('id'));
                        return $this->redirect(array(
                                'controller' => 'admin_projects', 'action' => 'admin_index', 'plugin' => false));
                    }
                }
            }
            $count = $this->User->up_login_try_count($this->request->data['User']['email']);
            if($count == 10){ //ログイン試行回数が10回のときのみ、アカウントロックされてるよメールを自動送信する
                $user = $this->User->get_user_from_email($this->request->data['User']['email']);
                $token = $this->User->make_token();
                $url = h(Router::url('/', true)).'reset_lock/'.$token;
                $this->User->id = $user['User']['id'];
                if($this->User->saveField('token', $token)){
                    if($this->Mail->reset_lock($user['User']['email'], $url)){
                        return $this->Session->setFlash('アカウントがロックされました。メールをご確認いただき、アカウントロックを解除してください。');
                    }
                }
            }
            $this->Session->setFlash(__('ログイン情報に誤りがあります！'));
        }
    }

    /**
     * 管理者ログアウト
     */
    public function admin_logout()
    {
        $this->Auth->logout();
        $this->redirect('/');
    }

}
