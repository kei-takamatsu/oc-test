<?php
App::uses('AppController', 'Controller');

class AdminDesignController extends AppController
{
    public $uses = array('Setting');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow(array('hoge'));
        $this->layout = 'admin';
        $this->Security->validatePost = false;
        $this->Security->csrfUseOnce  = false;
        $this->Security->csrfCheck    = false;
    }

    /**
     * デザイン編集画面
     */
    public function admin_edit()
    {
        if(!$this->setting){
            $this->redirect('/');
        }
        if($this->request->is('post') || $this->request->is('put')){
            $this->layout     = 'ajax';
            $this->autoRender = false;
            if(!empty($this->request->params['form']['logo'])){
                $this->request->data['Setting']['logo'] = $this->request->params['form']['logo'];
            }else{
                $this->request->data['Setting']['logo'] = null;
            }
            $this->Setting->id                    = 1;
            $this->request->data['Setting']['id'] = 1;
            $this->Ring->bindUp('Setting');
            if($this->Setting->save($this->request->data, true, array(
                    'logo', 'link_color', 'back1', 'back2', 'border_color', 'top_back', 'top_color', 'top_alpha',
                    'footer_back', 'footer_color', 'graph_back'
            ))
            ){
                return json_encode(array(
                        'status' => 1, 'msg' => 'デザインを更新しました'
                ));
            }else{
                return json_encode(array(
                        'status' => 0, 'msg' => '登録に失敗しました'
                ));
            }
        }else{
            $this->request->data['Setting'] = $this->setting;
        }
    }

    /**
     * デザイン編集画面（トップページ）
     */
    public function admin_edit_top()
    {
        if(!$this->setting){
            $this->redirect('/');
        }
        if($this->request->is('post') || $this->request->is('put')){
            $this->layout     = 'ajax';
            $this->autoRender = false;
            if(!empty($this->request->params['form']['top_box_sm'])){
                $this->request->data['Setting']['top_box_sm'] = $this->request->params['form']['top_box_sm'];
            }else{
                $this->request->data['Setting']['top_box_sm'] = null;
            }
            if(!empty($this->request->params['form']['top_box_pc'])){
                $this->request->data['Setting']['top_box_pc'] = $this->request->params['form']['top_box_pc'];
            }else{
                $this->request->data['Setting']['top_box_pc'] = null;
            }
            if(!empty($this->request->params['form']['top_box_img1'])){
                $this->request->data['Setting']['top_box_img1'] = $this->request->params['form']['top_box_img1'];
            }else{
                $this->request->data['Setting']['top_box_img1'] = null;
            }
            if(!empty($this->request->params['form']['top_box_img2'])){
                $this->request->data['Setting']['top_box_img2'] = $this->request->params['form']['top_box_img2'];
            }else{
                $this->request->data['Setting']['top_box_img2'] = null;
            }
            $this->Setting->id                    = 1;
            $this->request->data['Setting']['id'] = 1;
            $this->Ring->bindUp('Setting');
            if($this->Setting->save($this->request->data, true, array(
                    'top_box_sm', 'top_box_pc', 'top_box_height', 'top_box_color', 'top_box_back', 'top_box_black',
                    'top_box_content_num', 'content_position1', 'content_type1', 'txt_content1', 'movie_type1',
                    'movie_code1', 'content_position2', 'content_type2', 'txt_content2', 'movie_type2', 'movie_code2',
                    'top_box_img1', 'top_box_img2'
            ))
            ){
                return json_encode(array(
                        'status' => 1, 'msg' => 'デザインを更新しました'
                ));
            }else{
                return json_encode(array(
                        'status' => 0, 'msg' => '登録に失敗しました'
                ));
            }
        }else{
            $this->request->data['Setting'] = $this->setting;
            $this->set('setting', $this->setting);
        }
    }

}