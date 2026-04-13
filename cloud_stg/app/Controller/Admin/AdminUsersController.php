<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class AdminUsersController extends AppController
{
    public $uses = array('User', 'MailAuth');
    public $components = array('Mail');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow(array('hoge'));
        $this->layout = 'admin';
        if($this->action === 'edit'){
            //ajaxフォーム対策（token複数回利用可など）
            $this->Security->validatePost = false;
            $this->Security->csrfUseOnce  = false;
        }
    }

    /**
     * 管理 - ユーザー一覧
     */
    public function admin_index()
    {
        if($this->request->is('post') || $this->request->is('put')){
            //ユーザー検索
            $email = !empty($this->request->data['User']['email_like']) ? $this->request->data['User']['email_like'] : null;
            $this->paginate = $this->User->get_users_by_email($email, true, 30);
            $users = $this->paginate('User');
            return $this->set(compact('users'));
        }else{
            $this->paginate = $this->User->get_all_users(true, 50);
            $users = $this->paginate('User');
            $this->set(compact('users'));
        }
    }

    /**
     * 管理 - ユーザー詳細
     */
    public function admin_view($user_id)
    {
        $user = $this->User->findById($user_id);
        $this->set('u', $user['User']);
        if(! $user) $this->redirect('/');
    }

    /**
     * 管理 - ユーザ編集
     */
    public function admin_edit($user_id)
    {
        $user = $this->User->findById($user_id);
        $this->set('u', $user['User']);
        if(! $user || $user['User']['group_id'] == ADMIN_ROLE) $this->redirect('/');
        if($this->request->is('post') || $this->request->is('put')){
            $this->User->id = $user_id;
            if($this->User->save(
                $this->request->data,
                true,
                array('nick_name', 'name', 'email',
                      'address', 'url1', 'url2', 'url3',
                      'self_description', 'receive_address')
            )){
                $this->Session->setFlash('ユーザ情報を更新しました。');
                $this->redirect('/admin/admin_users/');
            }else{
                $this->Session->setFlash('ユーザ情報の登録に失敗しました。恐れ入りますが再度お試しください。');
            }
        }else{
            $this->request->data = $user;
        }
    }

    /**
     * 管理 - ユーザ削除
     */
    public function admin_delete($user_id)
    {
        $user = $this->User->findById($user_id);
        if(! $user || $user['User']['group_id'] == ADMIN_ROLE) $this->redirect('/');
        if($this->User->withdraw($user_id)){
            $this->Session->setFlash('ユーザを削除しました');
        }else{
            $this->Session->setFlash('ユーザが削除できませんでした。恐れ入りますが再度お試しください。');
        }
        $this->redirect('/admin/admin_users');
    }

    /**
     * 管理者の編集
     */
    public function admin_owner_edit()
    {
        if($this->request->is('post') || $this->request->is('put')){
            $fields = array('nick_name', 'email');
            if(!empty($this->request->data['User']['password'])){
                $fields[] = 'password';
            }
            $this->User->id = $this->Auth->user('id');
            $this->request->data['User']['id'] = $this->Auth->user('id');
            if($this->User->save($this->request->data, true, $fields)){
                $this->Session->setFlash('保存しました');
            }else{
                $this->Session->setFlash('保存できませんでした');
            }
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
        if($this->User->get_user_from_email($email)) return 3;
        $url = $this->MailAuth->make_mail_auth_and_url_for_change($email, $user_id);
        if($url){
            if($this->Mail->confirm_mail_address($email, $url)){
                return 1;
            }
        }
        return 2;
    }

}