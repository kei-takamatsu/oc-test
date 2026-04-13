<?php
App::uses('AppController', 'Controller');
App::uses('Component', 'Controller');
class FacebookController extends LogiAuthAppController
{
    public $uses = array('User');
    public $components = array('LogiAuth.Fb', 'LogiAuth.Login', 'Mail');
    public function beforeFilter(){
        parent::beforeFilter();
        $this->Auth->allow('callback', 'password');
    }

    /**
     * Facebookコールバック
     */
    public function callback()
    {
        $this->log('fb callback');
        if(!isset($_REQUEST["code"])) $this->redirect('/login');
        $fb = $this->Fb->get_facebook();
        $helper = $fb->getRedirectLoginHelper();
        $accessToken = $this->_get_access_token($fb, $helper);
        if(!$accessToken) return $this->_err_redirect();

        try {
            $response = $fb->get('/me?fields=id,name,email', (string) $accessToken);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            $this->log('Graph returned an error: ' . $e->getMessage());
            return $this->_err_redirect();
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            $this->log('Facebook SDK returned an error: ' . $e->getMessage());
            return $this->_err_redirect();
        }

        $user = $response->getGraphUser();
        $this->_login($user);
    }

    private function _get_access_token($fb, $helper)
    {
        try {
            $accessToken = $helper->getAccessToken();
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            $this->log('Graph returned an error: '. $e->getMessage());
            return null;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            $this->log('Facebook SDK returned an error: '.$e->getMessage());
            return null;
        }

        if (! isset($accessToken)){
            if($helper->getError()){
                $this->log('Error Code: '.$helper->getErrorCode());
                $this->log('Error Description: '.$helper->getErrorDescription());
                return null;
            }else{
                $this->log('HTTP/1.0 400 Bad Request');
                return null;
            }
        }

        $oAuth2Client = $fb->getOAuth2Client();
        if (! $accessToken->isLongLived()) {
            try {
                $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
            } catch (Facebook\Exceptions\FacebookSDKException $e) {
                $this->log('Error getting long-lived access token:'.$helper->getMessage());
                return null;
            }
        }
        return $accessToken;
    }

    private function _login($fb_user)
    {
        //現在ログインしていない場合
        if(!$auth = $this->Auth->user()){
            //usersにfacebookIDが登録済の場合
            if($user = $this->User->findByFacebookId($fb_user['id'])){
                //ログイン
                $this->Auth->login($user['User']);
                return $this->Login->redirect_referer();
            }
            //facebookアカウントのメールと同じメールがusersに登録されている場合
            if($user = $this->User->findByEmail($fb_user['email'])){
                //連携してログイン
                $this->User->id = $user['User']['id'];
                if($this->User->saveField('facebook_id', $fb_user['id'])){
                    $this->Session->setFlash(__('Facebookアカウントと連携しました。'));
                    $this->Auth->login($user['User']);
                }else{
                    $this->Session->setFlash('連携に失敗しました。');
                }
                return $this->Login->redirect_referer();
            }
            //新規登録
            $this->Session->write('facebook_user_info', $fb_user);
            return $this->redirect(array('action' => 'password'));
        }//現在ログイン中の場合
        else{
            //usersにfacebookIDが登録済の場
            //他ユーザに登録されていることになるため、エラー表示
            if($user = $this->User->findByFacebookId($fb_user['id'])){
                $this->Session->setFlash('このFacebookアカウントは他ユーザに連携されています。');
                return $this->Login->redirect_referer();
            }
            //連携する
            $this->User->id = $auth['id'];
            if($this->User->saveField('facebook_id', $fb_user['id'])){
                $this->Session->setFlash(__('Facebookアカウントと連携しました。'));
            }else{
                $this->Session->setFlash('連携に失敗しました。');
            }
            return $this->Login->redirect_referer();
        }
    }

    private function _err_redirect()
    {
        $this->Session->setFlash(__('Facebookに接続できません'));
        return $this->Login->redirect_referer();
    }

    /**
     * facebookログイン時のパスワード入力画面
     */
    public function password()
    {
        $this->set('login_page', 1);
        $facebook_info = $this->Session->read('facebook_user_info');
        if(!$facebook_info) $this->redirect('/');
        if($this->request->is('post') || $this->request->is('put')){
            if(empty($this->request->data['User']['password'])){
                $this->Session->setFlash('パスワードを入力してください');
                return;
            }
            $user['User']['email'] = $facebook_info['email'];
            $user['User']['name'] = $facebook_info['name'];
            $user['User']['nick_name'] = $this->request->data['User']['nick_name'];
            $user['User']['facebook_id'] = $facebook_info['id'];
            $user['User']['password'] = $this->request->data['User']['password'];
            $user['User']['group_id'] = USER_ROLE;
            $this->User->create();
            if($this->User->save($user)){
                $this->Session->setFlash('ユーザ登録が完了しました。');
                $this->Mail->registered($user, 'user');
                $this->Mail->registered($user, 'admin');
                $this->Session->delete('facebook_user_info');
                $user = $this->User->read();
                $this->Auth->login($user['User']);
                $this->Login->redirect_referer();
            }else{
                $this->Session->setFlash(__('ユーザー登録に失敗しました。恐れ入りますが再度お試しください。'));
            }
        }
    }

}
