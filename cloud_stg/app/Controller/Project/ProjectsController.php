<?php
App::uses('AppController', 'Controller');

class ProjectsController extends AppController
{
    public $uses = array(
            'Project', 'Partner', 'Toppage', 'Partner', 'Area',
            'FavouriteProject', 'Report', 'Setting', 'ReportContent'
    );

    public $components = array('Mail');

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('view', 'index');
        //Ajax時の対応
        if($this->action === 'add'){
            $this->Security->validatePost = false;
            $this->Security->csrfUseOnce  = false;
            $this->Security->csrfCheck    = false;
        }
        //ajaxでログインしてない場合スルーさせる（authComponentのajaxLoginまでいかずに、下記のリダイレクトさせてしまうから）
        if(!($this->request->is('ajax') && !$this->auth_user)){
            //subdomainなしの場合リダイレクト
            if(!$this->setting){
                $this->redirect(Configure::read('url_full_path'));
            }
        }
    }

    /**
     * プロジェクト検索画面
     */
    public function index()
    {
        $this->set('categories', $this->Project->Category->get_list());
        $this->set('areas', $this->Area->get_list());
        if($this->request->is('post') || $this->request->is('put')){
            $d = $this->request->data['Project'];
            $area_id = ($this->setting['cat_type_num'] == 2) ? $d['area_id'] : null;
            $this->paginate = $this->Project->search_projects($d['category_id'], $area_id, $d['order']);
        }else{
            $this->paginate = $this->Project->search_projects(null, null, null);
        }
        $this->set('title', 'プロジェクト検索');
        $this->set('projects', $this->paginate('Project'));
    }

    /**
     * 投稿済みプロジェクト一覧画面（マイページ）
     */
    public function registered()
    {
        $this->layout   = 'mypage';
        $this->paginate = $this->Project->get_pj_of_user($this->Auth->user('id'), 'options', 'all', 10, 0);
        $this->set('projects', $this->paginate('Project'));
        //マイページのサブメニュー用
        $this->set('menu_mode', 'registered');
        $this->set('categories', $this->Project->Category->get_list());
    }

    /**
     * プロジェクト詳細画面
     * @param string $id
     * @param null   $mode
     * @param int    $report_id
     * @return void
     */
    public function view($id = null, $mode = null, $report_id = null)
    {
        $project = $this->Project->get_project_or_project_url($id);
        if(!$project){
            $this->redirect('/');
        }
        //自分のサイトのプロジェクトではない場合、正しいURLにリダイレクト
        if(!empty($project['url'])){
            $this->redirect($project['url']);
        }
        $this->set('title', $project['Project']['project_name']);
        $this->set(compact('mode', 'project'));
        //ログインしている場合、お気に入り登録済みかチェック
        if($this->Auth->user('id')){
            $this->set('favourite', $this->FavouriteProject->check_favourite($this->Auth->user('id'), $id));
        }
        //プロジェクト詳細、コメント、支援者一覧、経過報告毎にviewを分ける
        if($mode){
            if($mode == 'comment'){
                $this->paginate = $this->Project->Comment->get_by_pj_id($id, true);
                $this->set('comments', $this->paginate('User'));
                if($this->smart_phone){
                    return $this->render('sp/view_comment_sp', 'project_view_sp');
                }else{
                    return $this->render('view_comment', 'project_view');
                }
            }elseif($mode == 'backers'){
                $this->paginate = $this->Project->User->get_backers_options_except_for_manual($id);
                $this->set('backers', $this->paginate('User'));
                if($this->smart_phone){
                    return $this->render('sp/view_bakers_sp', 'project_view_sp');
                }else{
                    return $this->render('view_bakers', 'project_view');
                }
            }elseif($mode == 'report'){
                $this->paginate = $this->Report->get_open_reports_options_of_project($id, 15, -1);
                $this->set('reports', $this->paginate('Report'));
                if($this->smart_phone){
                    return $this->render('sp/view_report_sp', 'project_view_sp');
                }else{
                    return $this->render('view_report', 'project_view');
                }
            }elseif($mode == 'report_view'){
                $report = $this->Report->get_report_of_project($report_id, $id);
                if(!$report){
                    $this->redirect('/');
                }
                $this->set(compact('report'));
                //活動報告コンテンツの取得
                $this->set('report_contents', $this->ReportContent->get_contents($report_id));
                if($this->smart_phone){
                    return $this->render('sp/view_report_view_sp', 'project_view_sp');
                }else{
                    return $this->render('view_report_view', 'project_view');
                }
            }
        }else{
            //プロジェクトコンテントの取得
            $contents = Cache::read('project_contents_'.$id);
            if($contents === false){
                $contents = $this->Project->ProjectContent->get_contents($id);
                Cache::write('project_contents_'.$id, $contents);
            }
            $this->set(compact('contents'));
            if($this->smart_phone){
                return $this->render('sp/view_sp', 'project_view_sp');
            }else{
                return $this->render('view', 'project_view');
            }
        }
    }

    /**
     * プロジェクト投稿画面
     */
    public function add()
    {
        $this->layout = 'mypage';
        $this->set('categories', $this->Project->Category->get_list());
        $this->set('areas', $this->Area->get_list());
        //ユーザのメアド登録済みチェック
        if(empty($this->auth_user['User']['email'])){
            $this->Session->setFlash('プロジェクトの作成は、メールアドレスの認証を完了していただく必要があります。');
            $this->redirect($this->referer());
        }
        if($this->request->is('post') || $this->request->is('put')){
            $this->autoRender                          = false;
            $this->request->data['Project']['user_id'] = $this->Auth->user('id');
            if(!empty($this->request->params['form']['pic'])){
                $this->request->data['Project']['pic'] = $this->request->params['form']['pic'];
            }else{
                $this->request->data['Project']['pic'] = null;
            }
            //全項目入力されているかチェック
            if(! $this->_chk_add_input($this->request->data)){
                return json_encode(array(
                        'status' => 0, 'msg' => '全項目を入力してください'
                ));
            }
            $this->Ring->bindUp('Project');
            $this->Project->create();
            if($this->Project->save($this->request->data, true, $this->_save_fields())
            ){
                //管理者・申込者に通知
                $this->Mail->pj_create($this->auth_user, $this->Project->read(), 'admin');
                $this->Mail->pj_create($this->auth_user, $this->Project->read(), 'user');
                $this->Session->setFlash('プロジェクトの新規作成を受け付けました。サイト管理者からの連絡をいましばらくお待ちください。');
                return json_encode(array(
                        'status' => 1, 'msg' => 'プロジェクトの新規作成を受け付けました。サイト管理者からの連絡をいましばらくお待ちください。'
                ));
            }else{
                if(!empty($this->Project->validationErrors)){
                    $errors = $this->Project->validationErrors;
                    return json_encode(array(
                            'status' => 0, 'msg' => '恐れ入りますが、入力内容をご確認の上、再度お試しください。', 'errors' => $errors
                    ));
                }else{
                    return json_encode(array(
                            'status' => 0, 'msg' => 'プロジェクトの登録が失敗しました。恐れ入りますが再度お試しください。'
                    ));
                    $this->log('プロジェクト投稿エラー：ユーザーID '.$this->Auth->user('id'));
                }
            }
        }
    }

    private function _chk_add_input($data)
    {
        if(empty($data['Project']['project_name'])
           || empty($data['Project']['pic'])
           || empty($data['Project']['category_id'])
           || empty($data['Project']['goal_amount'])
           || empty($data['Project']['description'])
           || empty($data['Project']['return'])
           || empty($data['Project']['contact'])
           || empty($data['Project']['rule'])
        ){
            return false;
        }
        if($this->setting['cat_type_num'] == 2){
            if(empty($data['Project']['area_id'])){
                return false;
            }
        }
        return true;
    }

    private function _save_fields()
    {
        $fields = array('project_name', 'category_id', 'goal_amount', 'description',
                        'return', 'contact', 'rule', 'user_id', 'created', 'modified', 'pic');
        if($this->setting['cat_type_num'] == 2){
            $fields[] = 'area_id';
        }
        return $fields;
    }

}
