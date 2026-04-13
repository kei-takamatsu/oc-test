<?php
App::uses('AppController', 'Controller');

class AdminSettingsController extends AppController
{
    public $uses = array('Setting');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow(array('hoge'));
        $this->layout = 'admin';
    }

    /**
     * サイト情報設定画面
     */
    public function admin_edit_info()
    {
        if(!$this->setting){
            $this->redirect('/');
        }
        if($this->request->is('post') || $this->request->is('put')){
            $this->Setting->id                    = $this->setting['id'];
            $this->request->data['Setting']['id'] = $this->setting['id'];
            $this->Ring->bindUp('Setting');
            //サイト名、サイト紹介文、運営者情報の入力チェック
            if(empty($this->request->data['Setting']['site_name'])
               || empty($this->request->data['Setting']['company_name'])
               || empty($this->request->data['Setting']['company_type'])
               || empty($this->request->data['Setting']['company_url'])
               || empty($this->request->data['Setting']['company_ceo'])
               || empty($this->request->data['Setting']['copy_right'])
               || empty($this->request->data['Setting']['company_address'])
            ){
                return $this->Session->setFlash('サイト名・運営者情報は必須入力項目です');
            }
            if($this->Setting->save($this->request->data, true, array(
                'site_name', 'fee', 'site_title', 'site_description', 'site_keywords', 'from_mail_address',
                'admin_mail_address', 'mail_signature', 'facebook_img', 'about', 'company_name', 'company_url',
                'company_ceo', 'company_address', 'company_type', 'copy_right', 'gmo_id', 'gmo_password',
                'twitter_api_key', 'twitter_api_secret', 'facebook_api_key', 'facebook_api_secret',
                'google_analytics', 'company_tel'
            )))
            {
                $this->Session->setFlash('サイト情報を保存しました');
                $this->redirect($this->request->referer());
            }else{
                $this->Session->setFlash('サイト情報が保存できませんでした');
            }
        }else{
            $this->request->data['Setting'] = $this->setting;
        }
    }

}