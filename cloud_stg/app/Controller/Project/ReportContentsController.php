<?php
App::uses('AppController', 'Controller');

class ReportContentsController extends AppController
{
    public $uses = array(
            'Report', 'ReportContent', 'ReportContentOrder'
    );

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('hoge');
        $this->layout                 = 'ajax';
        $this->Security->validatePost = false;
        $this->Security->csrfUseOnce  = false;
        $this->Security->csrfCheck    = false;
    }

    /**
     * Movieの追加(Ajax)
     * 活動報告が自分のプロジェクトの活動報告か？
     * @param int $report_id
     * @param int $order_no
     * @throws BadRequestException
     */
    public function movie_add($report_id = null, $order_no = null)
    {
        if($this->request->is('post') || $this->request->is('put')){
            $report = $this->Report->edit_roll_check($report_id, $this->auth_user['User']['id']);
            if(!$report){
                throw new BadRequestException('bad request');
            }
            $this->request->data['ReportContent']['report_id'] = $report_id;
            $content                                           = $this->ReportContent->item_add($this->request->data, $order_no);
            if(!$content){
                throw new BadRequestException('bad request');
            }
            return $this->set(compact('content'));
        }
        throw new BadRequestException('bad request');
    }

    /**
     * テキストの追加（Ajax）
     * @param int $report_id
     * @param int $order_no
     * @throws BadRequestException
     */
    public function text_add($report_id = null, $order_no = null)
    {
        if($this->request->is('post') || $this->request->is('put')){
            $report = $this->Report->edit_roll_check($report_id, $this->auth_user['User']['id']);
            if(!$report){
                throw new BadRequestException('bad request');
            }
            $this->request->data['ReportContent']['report_id'] = $report_id;
            $content                                           = $this->ReportContent->item_add($this->request->data, $order_no);
            if(!$content){
                throw new BadRequestException('bad request');
            }
            return $this->set(compact('content'));
        }
        throw new BadRequestException('bad request');
    }

    /**
     * 画像の追加（Ajax）
     * @param int $report_id
     * @param int $order_no
     * @throws BadRequestException
     */
    public function img_add($report_id = null, $order_no = null)
    {
        if($this->request->is('post') || $this->request->is('put')){
            $report = $this->Report->edit_roll_check($report_id, $this->auth_user['User']['id']);
            if(!$report){
                throw new BadRequestException('bad request');
            }
            if(!empty($this->request->params['form']['img'])){
                $this->request->data['ReportContent']['img'] = $this->request->params['form']['img'];
            }else{
                $this->request->data['ReportContent']['img'] = null;
            }
            $this->Ring->bindUp('ReportContent');
            $this->request->data['ReportContent']['report_id'] = $report_id;
            $content                                           = $this->ReportContent->item_add($this->request->data, $order_no);
            if(!$content){
                throw new BadRequestException('bad request');
            }
            return $this->set(compact('content'));
        }
        throw new BadRequestException('bad request');
    }

    /**
     * Movieの編集(Ajax)
     * @param int $report_content_id
     * @throws BadRequestException
     */
    public function movie_edit($report_content_id = null)
    {
        $type = 'movie';
        if($this->request->is('post') || $this->request->is('put')){
            $content = $this->ReportContent->check_content($report_content_id, $this->auth_user['User']['id']);
            if(!$content){
                throw new BadRequestException('bad request');
            }
            $this->request->data['ReportContent']['id']        = $report_content_id;
            $this->request->data['ReportContent']['report_id'] = $content['ReportContent']['report_id'];
            $content                                           = $this->ReportContent->{$type
                                                                                        .'_edit'}($this->request->data);
            if(!$content){
                throw new BadRequestException('bad request');
            }
            return $this->set(compact('content'));
        }
        throw new BadRequestException('bad request');
    }

    /**
     * テキストの編集(Ajax)
     */
    public function text_edit($report_content_id = null)
    {
        $type = 'text';
        if($this->request->is('post') || $this->request->is('put')){
            $content = $this->ReportContent->check_content($report_content_id, $this->auth_user['User']['id']);
            if(!$content){
                throw new BadRequestException('bad request');
            }
            $this->request->data['ReportContent']['id']        = $report_content_id;
            $this->request->data['ReportContent']['report_id'] = $content['ReportContent']['report_id'];
            $content                                           = $this->ReportContent->{$type
                                                                                        .'_edit'}($this->request->data);
            if(!$content){
                throw new BadRequestException('bad request');
            }
            return $this->set(compact('content'));
        }
        throw new BadRequestException('bad request');
    }

    /**
     * 画像の編集(Ajax)
     */
    public function img_edit($report_content_id = null)
    {
        $type = 'img';
        if($this->request->is('post') || $this->request->is('put')){
            $content = $this->ReportContent->check_content($report_content_id, $this->auth_user['User']['id']);
            if(!$content){
                throw new BadRequestException('bad request');
            }
            if(!empty($this->request->params['form']['img'])){
                $this->request->data['ReportContent']['img'] = $this->request->params['form']['img'];
            }else{
                $this->request->data['ReportContent']['img'] = null;
            }
            $this->Ring->bindUp('ReportContent');
            $this->request->data['ReportContent']['id']        = $report_content_id;
            $this->request->data['ReportContent']['report_id'] = $content['ReportContent']['report_id'];
            $content                                           = $this->ReportContent->{$type
                                                                                        .'_edit'}($this->request->data);
            if(!$content){
                throw new BadRequestException('bad request');
            }
            return $this->set(compact('content'));
        }
        throw new BadRequestException('bad request');
    }

    /**
     * ReportContentの削除(Ajax)
     * @throws BadRequestException
     */
    public function delete()
    {
        if($this->request->is('post')){
            $content_id = $this->request->data['id'];
            if(!$content_id){
                throw new BadRequestException('bad request');
            }
            $content = $this->ReportContent->check_content($content_id, $this->auth_user['User']['id']);
            if(!$content){
                throw new BadRequestException('bad request');
            }
            if($this->ReportContent->item_delete($content)){
                return;
            }
        }
        throw new BadRequestException('bad request');
    }

    /**
     * 並び順の変更関数（Ajax）
     * @param int $report_id
     * @throws BadRequestException
     * @return int 1
     */
    public function sort($report_id = null)
    {
        $this->autoRender = false;
        $report           = $this->Report->edit_roll_check($report_id, $this->auth_user['User']['id']);
        if(!$report){
            throw new BadRequestException('bad request');
        }
        if($this->request->is('post') && !empty($report_id)){
            if($this->ReportContentOrder->sort($report_id, $this->request->data)){
                return 1;
            }
        }
        throw new BadRequestException('bad request');
    }

    /**
     * 上に移動する関数(Ajax)
     * @param int $report_id
     * @throws BadRequestException
     * @return int 1
     */
    public function move_up($report_id = null)
    {
        $this->autoRender = false;
        if($this->request->is('post')){
            $report = $this->Report->edit_roll_check($report_id, $this->auth_user['User']['id']);
            if(!$report){
                throw new BadRequestException('bad request');
            }
            if($this->ReportContentOrder->move_up($report_id, $this->request->data)){
                return 1;
            }
        }
        throw new BadRequestException('bad request');
    }

    /**
     * 下に移動する関数(Ajax)
     * @param int $report_id
     * @throws BadRequestException
     * @return int 1
     */
    public function move_down($report_id = null)
    {
        $this->autoRender = false;
        if($this->request->is('post')){
            $report = $this->Report->edit_roll_check($report_id, $this->auth_user['User']['id']);
            if(!$report){
                throw new BadRequestException('bad request');
            }
            if($this->ReportContentOrder->move_down($report_id, $this->request->data)){
                return 1;
            }
        }
        throw new BadRequestException('bad request');
    }

}
