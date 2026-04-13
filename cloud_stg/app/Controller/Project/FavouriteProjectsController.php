<?php
App::uses('AppController', 'Controller');

/**
 * FavouriteProjects Controller
 * @property FavouriteProject $FavouriteProject
 * @property aclComponent     $acl
 */
class FavouriteProjectsController extends AppController
{

    public $uses = array(
            'Project', 'FavouriteProject', 'Category'
    );

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('hoge');
    }

    /**
     * マイページ　お気に入り一覧画面
     * index method
     * @return void
     */
    public function index()
    {
        $this->layout   = 'mypage';
        $this->paginate = $this->FavouriteProject->get_favourite_project_options($this->Auth->user('id'));
        $this->set('projects', $this->paginate('Project'));
        $this->set('categories', $this->Category->find('list'));
        $this->User->id = $this->Auth->user('id');
        $this->set('user', $this->User->read());
        //マイページのサブメニュー用
        $this->set('menu_mode', 'favourite');
    }

    /**
     * お気に入りの追加
     * add method
     * @param null $project_id
     * @param int  $mode
     * @return void
     */
    public function add($project_id = null, $mode = 1)
    {
        if($project_id != null){
            $fav_project['FavouriteProject']['project_id'] = $project_id;
            $fav_project['FavouriteProject']['user_id']    = $this->Auth->user('id');
            $this->FavouriteProject->create();
            if($this->FavouriteProject->save($fav_project)){
                $this->Session->setFlash(__('お気に入りに登録しました。'));
            }else{
                $this->Session->setFlash(__('%sできませんでした。恐れ入りますが再度お試しください。', __('お気に入りを登録')));
            }
            if($mode == 1){
                $this->redirect('/projects/view/'.$project_id);
            }
        }
    }

    /**
     * delete method
     * @param null $project_id
     * @throws MethodNotAllowedException
     * @internal param string $id
     * @return void
     */
    public function delete($project_id = null)
    {
        if($this->FavouriteProject->deleteAll(array('project_id' => $project_id))){
            $this->Session->setFlash(__('お気に入りを解除しました'));
        }else{
            $this->Session->setFlash(__('お気に入りを解除できませんでした。恐れ入りますが再度お試しください。'));
        }
        $this->redirect($this->request->referer());
    }

}
