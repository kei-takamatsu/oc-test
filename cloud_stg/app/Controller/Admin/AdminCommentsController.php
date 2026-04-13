<?php
App::uses('AppController', 'Controller');

class AdminCommentsController extends AppController
{
    public $uses = array(
            'Comment', 'Project'
    );

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('hoge');
        $this->layout = 'admin';
    }

    /**
     * プロジェクトコメント一覧
     */
    public function admin_index()
    {
        $this->paginate = $this->Comment->search(true, $this->request->data, 30);
        $comments       = $this->paginate('Comment');
        $this->set(compact('comments'));
    }

    /**
     * コメントの削除
     */
    public function admin_delete($id)
    {
        if($this->request->is('post')){
            if($this->Comment->delete_comment($id)){
                $this->Session->setFlash('削除しました');
            }else{
                $this->Session->setFlash('削除できませんでした');
            }
        }
        $this->redirect('/admin/admin_comments');
    }

}
