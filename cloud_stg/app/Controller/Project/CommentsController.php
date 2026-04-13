<?php
App::uses('AppController', 'Controller');

class CommentsController extends AppController
{
    public $uses = array(
            'Comment', 'Project'
    );

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('hoge');
    }

    /**
     * マイページ（コメント一覧画面）
     */
    public function index()
    {
        $this->layout   = 'mypage';
        $this->paginate = $this->Comment->get_by_user_id($this->auth_user['User']['id'], true, 15, 0);
        $this->set('comments', $this->paginate('Comment'));
    }

    /**
     * コメント投稿
     * @return void
     */
    public function add()
    {
        if($this->request->is('post') || $this->request->is('put')){
            $this->Comment->create();
            $this->Comment->begin();
            $this->request->data['Comment']['user_id'] = $this->auth_user['User']['id'];
            if(empty($this->request->data['Comment']['comment']) || preg_match("/^[\s　]*$/u", $this->request->data['Comment']['comment'])){
                $this->Session->setFlash(__('コメントを入力してください'));
                $this->Comment->rollback();
                return $this->redirect($this->referer());
            }
            if($this->Comment->save($this->request->data)){
                //コメント数をProjectテーブルに登録
                $comment_cnt = $this->Comment->get_comment_number_of_project($this->request->data['Comment']['project_id']);
                if($this->Project->save_comment_cnt($this->request->data['Comment']['project_id'], $comment_cnt)){
                    $this->Comment->commit();
                    $this->Session->setFlash(__('コメントを投稿しました'));
                    return $this->redirect($this->referer());
                }
            }
            $this->Session->setFlash(__('コメントが投稿できませんでした。恐れ入りますが再度お試しください。'));
            $this->Comment->rollback();
            return $this->redirect($this->referer());
        }else{
            $this->redirect('/');
        }
    }

    /**
     * コメントの削除
     * @param string $comment_id
     * @return void
     */
    public function delete($comment_id = null)
    {
        if(!$this->request->is('post')){
            $this->redirect('/');
        }
        $comment = $this->Comment->check_role($comment_id, $this->auth_user['User']['id']);
        if(!$comment){
            $this->redirect('/');
        }
        if($this->Comment->delete_comment($comment_id)){
            $this->Session->setFlash(__('コメントを削除しました。'));
            return $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('コメントが削除できませんでした。恐れ入りますが再度お試しください。'));
        return $this->redirect(array('action' => 'index'));
    }

}
