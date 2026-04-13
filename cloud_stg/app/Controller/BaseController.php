<?php
App::uses('AppController', 'Controller');
App::uses('Cache', 'Cache');

class BaseController extends AppController
{

    public $uses = array('Project', 'Setting', 'Report', 'Admin');
    public $helpers = array('Setting');
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow(array('index'));
    }

    /**
     * トップページ
     */
    public function index()
    {
        //ピックアッププロジェクトの取得
        $pickup_pj = null;
        if(!empty($this->setting['toppage_pickup_project_id'])){
            $pickup_pj = $this->Project->findById($this->setting['toppage_pickup_project_id']);
            if(empty($pickup_pj['Project']['stop'])){
                $this->set(compact('pickup_pj'));
            }
        }
        //優先プロジェクトの取得
        $top_pj_ids = json_decode($this->setting['toppage_projects_ids'], true);
        $top_pj = null;
        if(!empty($top_pj_ids)){
            $top_pj = $this->Project->get_toppage_top_pj($top_pj_ids, $this->setting['toppage_pickup_project_id']);
            $this->set(compact('top_pj'));
        }
        //当該サイトのプロジェクト一覧の取得
        $projects = $this->Project->get_toppage_pj($this->setting['toppage_pickup_project_id'], $top_pj_ids, 30);
        if(!empty($top_pj_ids)){
            $projects = array_merge($top_pj, $projects);
        }
        $this->set(compact('projects'));
        //活動報告の取得
        $reports = $this->Report->get_new_reports_in_site($this->setting['id']);
        $this->set(compact('reports'));
        $this->set('categories', $this->Project->Category->find('list'));
        $this->set('top_title', $this->setting['site_title']);
        $this->set('description', $this->setting['site_description']);
        $this->render('crowd_top', 'default_top');
    }

}
