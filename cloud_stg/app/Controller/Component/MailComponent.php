<?php
App::uses('Component', 'Controller');
App::uses('CakeEmail', 'Network/Email');

/**
 * Class MailComponent
 * Mail共有コンポーネント
 */
class MailComponent extends Component
{

    public function initialize(Controller $controller)
    {
        $this->controller = $controller;
    }

    /**
     * お問い合わせメール 送信関数
     */
    public function contact($data, $type)
    {
        return $this->mail('contact', compact('data'), $type, $data['Contact']['mail']);
    }

    /**
     * メール作成送信関数
     * @param string $template  メールテンプレート名（定数、フィールド名も同じ）小文字
     * @param array  $vars      (viewに渡す変数)
     * @param string $mail_type ('admin' or 'user')
     * @param string $email     ('typeがadminの場合不要')
     * @return bool
     */
    public function mail($template, $vars, $mail_type, $email = null)
    {
        $setting = $this->controller->setting;
        if($setting){
            $from    = $setting['from_mail_address'];
            $subject = $setting['site_name'].' - '.constant(strtoupper($template).'_SUBJECT');
            if($mail_type == 'admin'){
                $email   = $setting['admin_mail_address'];
                $subject = '[管理] '.$subject;
            }
            $vars['setting'] = $setting;
            if($this->send_mail($email, $template, $from, $subject, $vars)){
                return true;
            }
        }
        return false;
    }

    /**
     * メール送信関数
     */
    public function send_mail($email, $template, $from, $subject, $viewVars)
    {
        Configure::write('debug', 0);
        try{ //ユーザ向け
            $Email = new CakeEmail('default');
            $Email->to($email);
            $Email->template($template);
            $Email->from($from);
            $Email->subject($subject);
            $Email->viewVars($viewVars);
            $Email->send();
        }catch(Exception $e){
            $this->log('error : send_mail');
            $this->log($e->getMessage());
            return false;
        }
        return true;
    }

    /**
     * メールアドレス認証用メール 送信関数
     */
    public function confirm_mail_address($email, $url)
    {
        return $this->mail('confirm_mail_address', compact('url'), 'user', $email);
    }

    /**
     * ユーザ登録完了メール　送信関数
     */
    public function registered($user, $type)
    {
        return $this->mail('registered', compact('user'), $type, $user['User']['email']);
    }

    /**
     * パスワード忘れメール　送信関数
     */
    public function forgot_pass($user, $url)
    {
        return $this->mail('forgot_pass', compact('user', 'url'), 'user', $user['User']['email']);
    }

    /**
     * アカウントロック解除メール　送信関数
     */
    public function reset_lock($email, $url)
    {
        return $this->mail('reset_lock', compact('email', 'url'), 'user', $email);
    }

    /**
     * パスワード再設定完了メール　送信関数
     */
    public function reset_pass_complete($user)
    {
        return $this->mail('reset_pass_complete', compact('user'), 'user', $user['User']['email']);
    }

    /**
     * 支援完了メール（管理者・プロジェクトオーナー向け）　送信関数
     */
    public function back_complete_owner($owner, $backer, $project, $backed_project, $type)
    {
        return $this->mail('back_complete_owner', compact('owner', 'backer', 'project', 'backed_project'), $type, $owner['User']['email']);
    }

    /**
     * 支援完了メール（支援者向け）　送信関数
     */
    public function back_complete_backer($backer, $project, $backed_project)
    {
        return $this->mail('back_complete_backer', compact('backer', 'project', 'backed_project'), 'user', $backer['User']['email']);
    }

    /**
     * メッセージ送信通知関数
     */
    public function messaged($to_user, $from_user, $msg, $url)
    {
        return $this->mail('messaged', compact('to_user', 'from_user', 'msg', 'url'), 'user', $to_user['email']);
    }

    /**
     * プロジェクト作成申し込み通知
     */
    public function pj_create($user, $pj, $type)
    {
        return $this->mail('pj_create', compact('user', 'pj'), $type, $user['User']['email']);
    }
}