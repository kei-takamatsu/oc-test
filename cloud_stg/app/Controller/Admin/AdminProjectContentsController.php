<?php
App::uses('AppController', 'Controller');

class AdminProjectContentsController extends AppController
{
    public $uses = array(
            'Project', 'ProjectContent', 'ProjectContentOrder'
    );
    public $helpers = array('Project');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('hoge');
        $this->Security->validatePost = false;
        $this->Security->csrfUseOnce  = false;
        $this->Security->csrfCheck    = false;
        $this->layout                 = 'ajax';
    }

    /**
     * Movieの追加(Ajax)
     * プロジェクトが自分のサイトのプロジェクトか？
     * @param int $project_id
     * @param int $order_no
     * @throws BadRequestException
     */
    public function admin_movie_add($project_id = null, $order_no = null)
    {
        if($this->request->is('post') || $this->request->is('put')){
            $project = $this->Project->get_pj_by_id($project_id, array('ProjectContent'));
            if(!$project){
                throw new BadRequestException('bad request');
            }
            $this->request->data['ProjectContent']['project_id'] = $project_id;
            $content                                             = $this->ProjectContent->item_add($this->request->data, $order_no);
            if(!$content){
                throw new BadRequestException('bad request');
            }
            //project_contentのキャッシュ削除
            Cache::delete('project_contents_'.$project_id);
            return $this->set(compact('content'));
        }
        throw new BadRequestException('bad request');
    }

    /**
     * テキストの追加（Ajax）
     * @param int $project_id
     * @param int $order_no
     * @throws BadRequestException
     */
    public function admin_text_add($project_id = null, $order_no = null)
    {
        if($this->request->is('post') || $this->request->is('put')){
            $project = $this->Project->get_pj_by_id($project_id, array('ProjectContent'));
            if(!$project){
                throw new BadRequestException('bad request');
            }
            $this->request->data['ProjectContent']['project_id'] = $project_id;
            $content                                             = $this->ProjectContent->item_add($this->request->data, $order_no);
            if(!$content){
                throw new BadRequestException('bad request');
            }
            return $this->set(compact('content'));
        }
        throw new BadRequestException('bad request');
    }

    /**
     * 画像の追加（Ajax）
     * @param int $project_id
     * @param int $order_no
     * @throws BadRequestException
     */
    public function admin_img_add($project_id = null, $order_no = null)
    {
        if($this->request->is('post') || $this->request->is('put')){
            $project = $this->Project->get_pj_by_id($project_id, array('ProjectContent'));
            if(!$project){
                throw new BadRequestException('bad request');
            }
            if(!empty($this->request->params['form']['img'])){
                $this->request->data['ProjectContent']['img'] = $this->request->params['form']['img'];
            }else{
                $this->request->data['ProjectContent']['img'] = null;
            }
            $this->Ring->bindUp('ProjectContent');
            $this->request->data['ProjectContent']['project_id'] = $project_id;
            $content                                             = $this->ProjectContent->item_add($this->request->data, $order_no);
            if(!$content){
                throw new BadRequestException('bad request');
            }
            //project_contentのキャッシュ削除
            Cache::delete('project_contents_'.$project_id);
            return $this->set(compact('content'));
        }
        throw new BadRequestException('bad request');
    }

    /**
     * Movieの編集(Ajax)
     * @param int $project_content_id
     * @throws BadRequestException
     */
    public function admin_movie_edit($project_content_id = null)
    {
        $type = 'movie';
        if($this->request->is('post') || $this->request->is('put')){
            $content = $this->ProjectContent->findById($project_content_id);
            if(!$content){
                throw new BadRequestException('bad request');
            }
            $this->request->data['ProjectContent']['id']         = $project_content_id;
            $this->request->data['ProjectContent']['project_id'] = $content['ProjectContent']['id'];
            $content                                             = $this->ProjectContent->{$type
                                                                                           .'_edit'}($this->request->data);
            if(!$content){
                throw new BadRequestException('bad request');
            }
            //project_contentのキャッシュ削除
            Cache::delete('project_contents_'.$content['ProjectContent']['project_id']);
            return $this->set(compact('content'));
        }
        throw new BadRequestException('bad request');
    }

    /**
     * テキストの編集(Ajax)
     */
    public function admin_text_edit($project_content_id = null)
    {
        $type = 'text';
        if($this->request->is('post') || $this->request->is('put')){
            $content = $this->ProjectContent->findById($project_content_id);
            if(!$content){
                throw new BadRequestException('bad request');
            }
            $this->request->data['ProjectContent']['id']         = $project_content_id;
            $this->request->data['ProjectContent']['project_id'] = $content['ProjectContent']['id'];
            $content                                             = $this->ProjectContent->{$type
                                                                                           .'_edit'}($this->request->data);
            if(!$content){
                throw new BadRequestException('bad request');
            }
            //project_contentのキャッシュ削除
            Cache::delete('project_contents_'.$content['ProjectContent']['project_id']);
            return $this->set(compact('content'));
        }
        throw new BadRequestException('bad request');
    }

    /**
     * 画像の編集(Ajax)
     */
    public function admin_img_edit($project_content_id = null)
    {
        $type = 'img';
        if($this->request->is('post') || $this->request->is('put')){
            $content = $this->ProjectContent->findById($project_content_id);
            if(!$content){
                throw new BadRequestException('bad request');
            }
            if(!empty($this->request->params['form']['img'])){
                $this->request->data['ProjectContent']['img'] = $this->request->params['form']['img'];
            }else{
                $this->request->data['ProjectContent']['img'] = null;
            }
            $this->Ring->bindUp('ProjectContent');
            $this->request->data['ProjectContent']['id']         = $project_content_id;
            $this->request->data['ProjectContent']['project_id'] = $content['ProjectContent']['id'];
            $content                                             = $this->ProjectContent->{$type
                                                                                           .'_edit'}($this->request->data);
            if(!$content){
                throw new BadRequestException('bad request');
            }
            //project_contentのキャッシュ削除
            Cache::delete('project_contents_'.$content['ProjectContent']['project_id']);
            return $this->set(compact('content'));
        }
        throw new BadRequestException('bad request');
    }

    /**
     * ProjectContentの削除(Ajax)
     * @throws BadRequestException
     */
    public function admin_delete()
    {
        if($this->request->is('post')){
            if(!empty($this->request->data['id'])){
                $content = $this->ProjectContent->findById($this->request->data['id']);
                if(!empty($content)){
                    if($this->ProjectContent->item_delete($content)){
                        //project_contentのキャッシュ削除
                        Cache::delete('project_contents_'.$content['ProjectContent']['project_id']);
                        return;
                    }
                }
            }
        }
        throw new BadRequestException('bad request');
    }

    /**
     * 並び順の変更関数（Ajax）
     * @param int $project_id
     * @throws BadRequestException
     * @return int 1
     */
    public function admin_sort($project_id = null)
    {
        $this->autoRender = false;
        $project          = $this->Project->get_pj_by_id($project_id, array('ProjectContent'));
        if(!$project){
            throw new BadRequestException('bad request');
        }
        if($this->request->is('post') && !empty($project_id)){
            if($this->ProjectContentOrder->sort($project_id, $this->request->data)){
                //project_contentのキャッシュ削除
                Cache::delete('project_contents_'.$project_id);
                return 1;
            }
        }
        throw new BadRequestException('bad request');
    }

    /**
     * 上に移動する関数(Ajax)
     * @param int $project_id
     * @throws BadRequestException
     * @return int 1
     */
    public function admin_move_up($project_id = null)
    {
        $this->autoRender = false;
        if($this->request->is('post')){
            $project = $this->Project->get_pj_by_id($project_id, array('ProjectContent'));
            if(!$project){
                throw new BadRequestException('bad request');
            }
            if($this->ProjectContentOrder->move_up($project_id, $this->request->data)){
                //project_contentのキャッシュ削除
                Cache::delete('project_contents_'.$project_id);
                return 1;
            }
        }
        throw new BadRequestException('bad request');
    }

    /**
     * 下に移動する関数(Ajax)
     * @param int $project_id
     * @throws BadRequestException
     * @return int 1
     */
    public function admin_move_down($project_id = null)
    {
        $this->autoRender = false;
        if($this->request->is('post')){
            $project = $this->Project->get_pj_by_id($project_id, array('ProjectContent'));
            if(!$project){
                throw new BadRequestException('bad request');
            }
            if($this->ProjectContentOrder->move_down($project_id, $this->request->data)){
                //project_contentのキャッシュ削除
                Cache::delete('project_contents_'.$project_id);
                return 1;
            }
        }
        throw new BadRequestException('bad request');
    }

    /**
     * コンテンツを公開する関数（Ajax）
     */
    public function admin_content_open()
    {
        $this->autoRender = false;
        if($this->request->is('post')){
            $content = $this->ProjectContent->findById($this->request->data['id']);
            if(!$content){
                throw new BadRequestException('bad request');
            }
            if($this->ProjectContent->content_open($this->request->data['id'])){
                //project_contentのキャッシュ削除
                Cache::delete('project_contents_'.$content['ProjectContent']['project_id']);
                return 1;
            }
        }
        throw new BadRequestException('bad request');
    }

    /**
     * コンテンツを全て公開する関数
     */
    public function admin_open_all($project_id = null)
    {
        if(!$project_id){
            $this->redirecet('/');
        }
        if($this->ProjectContent->open_all($project_id)){
            $this->Session->setFlash('コンテンツを公開しました');
            //project_contentのキャッシュ削除
            Cache::delete('project_contents_'.$project_id);
        }else{
            $this->Session->setFlash('一部のコンテンツが公開できませんでした。恐れ入りますが、再度お試しください');
        }
        $this->redirect('/admin/admin_projects/edit_detail/'.$project_id);
    }

    /**
     * コンテンツを非公開にする関数（Ajax）
     */
    public function admin_content_close()
    {
        $this->autoRender = false;
        if($this->request->is('post')){
            $content = $this->ProjectContent->findById($this->request->data['id']);
            if(!$content){
                throw new BadRequestException('bad request');
            }
            if($this->ProjectContent->content_close($this->request->data['id'])){
                //project_contentのキャッシュ削除
                Cache::delete('project_contents_'.$content['ProjectContent']['project_id']);
                return 1;
            }
        }
        throw new BadRequestException('bad request');
    }

}
