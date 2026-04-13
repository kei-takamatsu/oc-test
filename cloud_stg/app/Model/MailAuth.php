<?php
App::uses('AppModel', 'Model');

class MailAuth extends AppModel
{

    public $validate = array(
            'email' => array(
                    'email' => array(
                            'rule' => array('email'), 'message' => '正しいメールアドレスを入力してください。',
                    ), 'notblank' => array(
                            'rule' => array('notblank'), 'message' => 'メールアドレスを入力してください。',
                    ),
            ),
    );

    /**
     * mail_authの新規作成
     * 既にメールアドレスが登録されている場合は上書き
     * user_idがあるmail_authは上書きしない
     */
    public function make_mail_auth($token, $email)
    {
        $mail_auth = $this->get_mail_auth_from_email($email);
        if($mail_auth && empty($mail_auth['MailAuth']['user_id'])){
            $this->id = $mail_auth['MailAuth']['id'];
            if($this->saveField('token', $token)){
                return true;
            }
        }else{
            $data = array(
                    'MailAuth' => array(
                            'token' => $token, 'email' => $email
                    )
            );
            $this->create();
            if($this->save($data)){
                return true;
            }
        }
        return false;
    }

    /**
     * メールアドレスからmail_authを取得する関数
     */
    public function get_mail_auth_from_email($email)
    {
        return $this->findByEmail($email);
    }

    /**
     * パスワード変更時のタスク（mail_auth作成〜URL作成まで）
     * @param $email
     * @param $user_id
     * @return null|string
     */
    public function make_mail_auth_and_url_for_change($email, $user_id)
    {
        $token = $this->make_token();
        if($this->make_mail_auth_for_change_email($token, $email, $user_id)){
            return $this->make_confirm_url($token);
        }
        return null;
    }

    /**
     * Token作成関数
     */
    public function make_token()
    {
        while(true){
            $token     = Security::hash(rand(100000, 999999), 'sha1', true);
            $mail_auth = $this->findByToken($token);
            if(empty($mail_auth)){
                return $token;
            }
        }
    }

    /**
     * メールアドレス変更のためのmail_auth登録
     * 既にメールアドレスが登録されている場合は上書き
     * user_idがないmail_authは上書きしない
     * user_idが合致しないmail_authは上書きしない
     */
    public function make_mail_auth_for_change_email($token, $email, $user_id)
    {
        $mail_auth = $this->get_mail_auth_from_email($email);
        if($mail_auth && !empty($mail_auth['MailAuth']['user_id']) && $user_id == $mail_auth['MailAuth']['user_id']){
            $this->id = $mail_auth['MailAuth']['id'];
            if($this->saveField('token', $token)){
                return true;
            }
        }else{
            $data = array(
                    'MailAuth' => array(
                            'token' => $token, 'email' => $email, 'user_id' => $user_id
                    )
            );
            $this->create();
            if($this->save($data)){
                return true;
            }
        }
        return false;
    }

    /**
     * 認証用URL作成関数
     */
    public function make_confirm_url($token)
    {
        return h(Router::url('/', true)).'confirm_mail/'.$token;
    }

    /**
     * メールアドレス変更処理
     * @param array $mail_auth
     * @return int $result (1 => OK、2 => Error、3 => 他ユーザで登録済み）
     */
    public function change_email($mail_auth)
    {
        $email   = $mail_auth['MailAuth']['email'];
        $user_id = $mail_auth['MailAuth']['user_id'];
        $User    = ClassRegistry::init('User');
        if($User->get_user_from_email($email)){
            $this->delete($mail_auth['MailAuth']['id']);
            return 3;
        }
        $this->begin();
        if($this->delete($mail_auth['MailAuth']['id'])){
            $User->id = $user_id;
            if($User->saveField('email', $email)){
                $this->commit();
                return 1;
            }
        }
        $this->rollback();
        return 2;
    }

    /**
     * tokenからmail_authを取得する関数
     */
    public function get_mail_auth_from_token($token)
    {
        return $this->findByToken($token);
    }

    /**
     * メールアドレスのチェック関数
     * @param str   $email
     * @param array $mail_auth
     * @return bool
     */
    public function check_email($email, $mail_auth)
    {
        return $email == $mail_auth['MailAuth']['email'];
    }

    /**
     * mail_authの削除
     */
    public function delete_mail_auth($mail_auth)
    {
        if($mail_auth){
            if($this->delete($mail_auth['MailAuth']['id'])){
                return true;
            }
        }
        return false;
    }

}
