<?php
App::uses('AppController', 'Controller');

class BackingLevelsController extends AppController
{
    public $uses = array(
            'Project', 'BackedProject', 'BackingLevel'
    );
    public $components = array(
            'Acl', 'Session', 'Filebinder.Ring'
    );
    public $helpers = array('Project');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('hoge');
    }

    /**
     * 支援画面（支援パターンの選択）
     * 支援パターン選択後、支援金額と支援コメントの入力画面に移行
     * @param null $project_id
     */
    public function index($project_id = null)
    {
        $project = $this->Project->check_backing_enable($project_id);
        if(!$project){
            $this->redirect('/');
        }
        $this->BackingLevel->recursive = 0;
        $conditions                    = array('conditions' => array('BackingLevel.project_id' => $project_id));
        $backingLevels                 = $this->BackingLevel->find('all', $conditions);
        $this->set(compact('backingLevels', 'project'));
    }

}
