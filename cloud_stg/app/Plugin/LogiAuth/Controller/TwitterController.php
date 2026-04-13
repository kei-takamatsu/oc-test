<?php
App::uses('AppController', 'Controller');
App::import('Vendor', 'Twitter/twitteroauth');

class TwitterController extends LogiAuthAppController
{

    public $uses = array(
            'User', 'MailAuth'
    );
    public $components = array(
            'LogiAuth.Tw', 'LogiAuth.Login', 'Mail'
    );

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('login', 'callback', 'password');
    }

    /**
     * Twitterログイン
     */
    public function login()
    {
        list($api_key, $api_secret, $callback_url) = $this->Tw->get_api_info();
        $Twitter       = new TwitterOAuth($api_key, $api_secret);
        $request_token = $Twitter->getRequestToken($callback_url);
        $this->Session->write('Twitter.request_token', $request_token);
        switch($Twitter->http_code){
            case 200:
                return $this->redirect($Twitter->getAuthorizeURL($request_token));
            default:
                $this->Session->setFlash('Twitterに接続できません。恐れ入りますが再度お試しください。');
                return $this->redirect('/login');
        }
    }

    /**
     * twitterコールバック
     */
    public function callback()
    {
        if(empty($_GET['oauth_token'])){
            return $this->redirect('/login');
        }
        $request_token = $this->Session->read('Twitter.request_token');
        if(!$request_token){
            return $this->redirect('/login');
        }
        list($api_key, $api_secret, $callback_url) = $this->Tw->get_api_info();
        $access_token = $this->Tw->get_access_token($api_key, $api_secret, $request_token);
        $content      = $this->Tw->get_user_info($api_key, $api_secret, $access_token);
        $user         = $this->User->findByTwitterId($content->id);
        $auth_id      = $this->Auth->user('id');
        //twitterが既に登録されている
        if($user){
            //他のアカウントと連携されている
            if($auth_id && $auth_id != $user['User']['id']){
                $this->Session->setFlash(__('このTwitterアカウントは他のユーザと連携されています。'));
                return $this->redirect('/social');
                //他のアカウントと連携されていない -> ログイン
            }else{
                if(!$auth_id){
                    $this->Auth->login($user['User']);
                }
                return $this->Login->redirect_referer();
            }
            //twitterは未登録
        }else{
            //ログインしている -> twitterを連携する
            if($auth_id){
                $this->User->id = $auth_id;
                if($this->User->saveField('twitter_id', $content->id)){
                    $this->User->get_twitter_profile_img($content->profile_image_url, $auth_id);
                    $this->Session->setFlash(__('Twitterアカウントと連携しました。'));
                }else{
                    $this->Session->setFlash(__('Twitterアカウントとの連携に失敗しました。恐れ入りますが再度お試しください。'));
                }
                return $this->redirect('/social');
                //ログインしていない -> twitterを登録する
            }else{
                $this->Session->write('twitter_user_info', $content);
                return $this->redirect(array('action' => 'password'));
            }
        }
    }

    /**
     * Twitterログイン時のメールアドレス・パスワード入力画面
     */
    public function password()
    {
        $this->set('login_page', 1);
        if($this->request->is('post') || $this->request->is('put')){
            $twitter_info = $this->Session->read('twitter_user_info');
            if(!$twitter_info){
                return $this->redirect('/login');
            }
            //二重登録防止！
            if($this->User->get_user_by_twitter_id($twitter_info->id)){
                $this->Session->delete('twitter_user_info');
                $this->redirect('/');
            }
            if(empty($this->request->data['User']['email']) || empty($this->request->data['User']['password']) //||
                //empty( $this->request->data[ 'User' ][ 'password2' ] )
            ){
                return $this->Session->setFlash('全ての項目を入力してください');
            }
            //if($this->request->data['User']['password'] != $this->request->data['User']['password2']){
            //	return $this->Session->setFlash( 'パスワードが一致しません' );
            //}
            $user = $this->User->findByEmail($this->request->data['User']['email']);
            if(!empty($user)){
                //既に入力したメールアドレスをもつユーザが存在する場合、入力情報でログインしてみる
                if($this->Auth->login()){
                    //ログインできたら連携する
                    $this->User->id = $user['User']['id'];
                    if($this->User->saveField('twitter_id', $twitter_info->id)){
                        //twitterプロフィール画像の取得
                        $this->User->get_twitter_profile_img($twitter_info->profile_image_url, $user['User']['id']);
                        $this->Session->setFlash('Twitterと連携しました');
                        return $this->Login->redirect_referer();
                    }else{
                        return $this->Session->setFlash('Twitterとの連携に失敗しました。恐れ入りますが再度お試しください。');
                    }
                }else{
                    return $this->Session->setFlash(__('このメールアドレスは既に登録済みで、入力されたパスワードではログインできません'));
                }
            }else{
                //$user[ 'User' ][ 'email' ]      = $this->request->data[ 'User' ][ 'email' ];
                $user['User']['password'] = $this->request->data['User']['password'];
                //$user[ 'User' ][ 'password2' ]  = $this->request->data[ 'User' ][ 'password2' ];
                $user['User']['group_id']   = USER_ROLE;
                $user['User']['twitter_id'] = $twitter_info->id;
                $user['User']['name']       = $twitter_info->name;
                $user['User']['nick_name']  = $twitter_info->screen_name;
                //$token = $this->User->make_token();
                $this->User->begin();
                $this->User->create();
                $fields = array(
                        'nick_name', 'name', 'twitter_id', 'group_id', 'password', 'created', 'modified', 'id'
                );
                if($this->User->save($user, true, $fields)){
                    $email = $this->request->data['User']['email'];
                    $url   = $this->MailAuth->make_mail_auth_and_url_for_change($email, $this->User->id);
                    if($this->Mail->confirm_mail_address($email, $url)){
                        $this->User->commit();
                        //twitterプロフィール画像の取得
                        $this->User->get_twitter_profile_img($twitter_info->profile_image_url, $this->User->id);
                        $this->Session->delete('twitter_user_info');
                        $this->Session->setFlash(__('メール登録用のURLをお送りしました。'));
                        if($this->Auth->login()){
                            return $this->Login->redirect_referer();
                        }else{
                            return $this->redirect(array('action' => 'login'));
                        }
                    }
                }
                $this->User->rollback();
                $this->Session->setFlash(__('ユーザー登録に失敗しました。恐れ入りますが再度お試しください。'));
                return;
            }
        }
    }

}
