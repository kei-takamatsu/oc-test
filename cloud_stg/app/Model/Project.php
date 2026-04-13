<?php
App::uses('AppModel', 'Model');

/**
 * Class Project
 * opened -> yes (公開中）
 * active -> yes (募集中・募集前）
 * stop -> 1 （募集停止中）※合わせてopenendもnoにする前提
 */
class Project extends AppModel
{
    public $displayField = 'project_name';

    public $actsAs = array(
        'Filebinder.Bindable' => array(
            'dbStorage' => false, 'beforeAttach' => 'resize', 'afterAttach' => 'thumbnail'
        )
    );
    public $bindFields = array();
    public $validate = array(
        'pic' => array(
            'allowExtention' => array(
                'rule' => array(
                    'checkExtension', array(
                        'jpg', 'jpeg', 'png', 'gif'
                    )
                ), 'message' => '拡張子が無効です', 'allowEmpty' => true
            ), 'fileSize' => array(
                'rule' => array(
                    'checkFileSize', '1MB'
                ), 'message' => 'ファイルサイズが1MBを超過しています'
            ), 'illegalCode' => array('rule' => array('checkIllegalCode'),)
        ), 'project_name' => array(
            'notblank' => array(
                'rule' => array('notblank'), 'message' => 'プロジェクト名を入力してください', 'allowEmpty' => false,
            ),
        ), 'goal_amount' => array(
            'notblank' => array(
                'rule' => array('notblank'), 'message' => '目標金額を入力してください。', 'allowEmpty' => false,
            ), 'naturalNumber' => array(
                'rule' => array('naturalNumber'), 'message' => '目標金額は数値を入力してください。',
            ), 'range' => array(
                'rule' => array(
                    'range', 99999, 1000000000
                ), 'message' => '10万円以上を入力してください',
            ),
        ), 'collection_term' => array(
            'notblank' => array(
                'rule' => array('notblank'),
                'message' => '募集期間を入力してください。',
                'allowEmpty' => false,
            ), 'naturalNumber' => array(
                'rule' => array('naturalNumber'), 'message' => '募集期間は数値を入力してください。',
            ), 'range' => array(
                'rule' => array(
                    'range', 14, 81
                ), 'message' => '15から80までの数字を入力してください。',
            ),
        ), 'return' => array(
            'notblank' => array(
                'rule' => array('notblank'), 'message' => 'リターン概要を入力してください', 'on' => 'create'
            )
        ), 'rule' => array(
            'equalTo' => array(
                'rule' => array(
                    'equalTo', '1'
                ), 'message' => '規約に同意してください。', 'allowEmpty' => true, 'on' => 'create'
            )
        ), 'contact' => array(
            'notblank' => array(
                'rule' => array('notblank'), 'message' => '連絡先を入力してください。', 'on' => 'create'
            )
        ), 'description' => array(
            'notempyt' => array(
                'rule' => array('notblank'),
                'message' => 'プロジェクト概要を入力してください。',
                //'on' => 'create'
            )
        ),
    );
    /**
     * belongsTo associations
     * @var array
     */
    public $belongsTo = array(
        'Category' => array(
            'className' => 'Category', 'foreignKey' => 'category_id', 'conditions' => '', 'fields' => '',
            'order' => ''
        ), 'User' => array(
            'className' => 'User', 'foreignKey' => 'user_id', 'conditions' => '', 'fields' => '', 'order' => ''
        )
    );
    /**
     * hasMany associations
     * @var array
     */
    public $hasMany = array(
        'BackingLevel' => array(
            'className' => 'BackingLevel', 'foreignKey' => 'project_id', 'dependent' => false,
            'conditions' => '', 'fields' => '', 'order' => '', 'limit' => '', 'offset' => '', 'exclusive' => '',
            'finderQuery' => '', 'counterQuery' => ''
        ), 'Comment' => array(
            'className' => 'Comment', 'foreignKey' => 'project_id', 'dependent' => false, 'conditions' => '',
            'fields' => '', 'order' => '', 'limit' => '', 'offset' => '', 'exclusive' => '',
            'finderQuery' => '', 'counterQuery' => ''
        ), 'Report' => array(
            'className' => 'Report', 'foreignKey' => 'project_id', 'dependent' => false, 'conditions' => '',
            'fields' => '', 'order' => '', 'limit' => '', 'offset' => '', 'exclusive' => '',
            'finderQuery' => '', 'counterQuery' => ''
        ), 'ProjectContent' => array(
            'className' => 'ProjectContent', 'foreignKey' => 'project_id', 'dependent' => false,
            'conditions' => '', 'fields' => '', 'order' => '', 'limit' => '', 'offset' => '', 'exclusive' => '',
            'finderQuery' => '', 'counterQuery' => ''
        ), 'BackedProject' => array(
            'className' => 'BackedProject', 'foreignKey' => 'project_id', 'dependent' => true,
            'conditions' => '', 'fields' => '', 'order' => '', 'limit' => '', 'offset' => '', 'exclusive' => '',
            'finderQuery' => '', 'counterQuery' => ''
        )
    );

    function __construct()
    {
        parent::__construct();
        $this->bindFields[] = array(
            'field' => 'pic', 'tmpPath' => Configure::read('file_path').'tmp'.DS,
            'filePath' => Configure::read('file_path').'upload'.DS,
        );
    }

    /**
     * 画像リサイズ
     * @param array $tmp_file_path
     * @return bool
     */
    public function resize($tmp_file_path)
    {
        return $this->resize_image($tmp_file_path, 1500, 1000);
    }

    /**
     * サムネイル作成
     * @param array $file_path
     * @return bool
     */
    public function thumbnail($file_path)
    {
        return $this->create_thumbnail($file_path, array(
            array(
                400, 267
            ),
        ));
    }

    /**
     * IDからプロジェクト取得
     * @param int   $id
     * @param array $joins (backing_levels)
     * @return array
     */
    public function get_pj_by_id($id, $joins = array())
    {
        $options     = array(
            'conditions' => array('Project.id' => $id), 'recursive' => 1,
        );
        $hasmany     = array_keys($this->hasMany);
        $unbind_list = array_diff($hasmany, $joins);
        $this->unbindModel(array('hasMany' => $unbind_list));
        return $this->find('first', $options);
    }

    /**
     * 募集中PJの取得
     * @param string $mode (options, all, list, first)
     * @param int    $limit
     * @param int    $recursive
     * @return array
     */
    public function get_wanted_pj($mode = 'options', $limit = 30, $recursive = -1)
    {
        $options                                 = $this->get_pj($mode, 'open', $limit, $recursive, 'all');
        $options['conditions']['Project.active'] = 'yes';
        return $this->_switch_return_mode($mode, $options);
    }

    /**
     * プロジェクト取得
     * @param string $mode   (options, all, list, first)
     * @param string $opened (all, open, close)
     * @param int    $limit
     * @param int    $recursive
     * @param string $success
     * @return array
     */
    public function get_pj($mode = 'all', $opened = 'all', $limit = 10, $recursive = -1, $success = 'all')
    {
        $options = array(
            'limit' => $limit, 'recursive' => $recursive, 'order' => array('Project.created' => 'DESC')
        );
        switch($opened){
            case 'all':
                break;
            case 'open':
                $options['conditions']['Project.opened'] = 'yes';
                $options['conditions']['Project.stop']   = 0;
                break;
            case 'close':
                $options['conditions']['Project.opened'] = 'no';
        }
        $options = $this->_switch_success($options, $success);
        return $this->_switch_return_mode($mode, $options);
    }

    /**
     * 成功、失敗、全ての切り替え
     */
    private function _switch_success($options, $success)
    {
        switch($success){
            case 'all':
                $options['order']['Project.created'] = 'DESC';
                break;
            case 'success':
                $options['conditions']['Project.active']         = 'no';
                $options['conditions'][]                         = 'Project.goal_amount <= Project.collected_amount';
                $options['order']['Project.collection_end_date'] = 'DESC';
                break;
            case 'fail':
                $options['conditions']['Project.active']         = 'no';
                $options['conditions'][]                         = 'Project.goal_amount > Project.collected_amount';
                $options['order']['Project.collection_end_date'] = 'DESC';
                break;
        }
        return $options;
    }

    /**
     * モデルのreturn形式をmodeによって切り替える
     */
    private function _switch_return_mode($mode, $options)
    {
        switch($mode){
            case 'options':
                return $options;
            case 'all':
                return $this->find('all', $options);
            case 'first':
                return $this->find('first', $options);
            case 'list':
                return $this->find('list', $options);
        }
    }

    /**
     * ユーザがPJを公開していないかチェックする
     */
    public function chk_user_opened_pj($user_id)
    {
        $options = array(
            'conditions' => array(
                'Project.user_id' => $user_id, 'Project.opened' => 'yes'
            ), 'limit' => 1, 'fields' => array('Project.id')
        );
        if($this->find('first', $options)){
            return false;
        }
        return true;
    }

    /**
     * ユーザが作成して、且つ成功したプロジェクトかをチェックする関数
     */
    public function check_made_and_success_project($project_id, $user_id)
    {
        $options                             = $this->get_pj_of_user($user_id, 'options', 'success', 1000, -1);
        $options['conditions']['Project.id'] = $project_id;
        return $this->find('first', $options);
    }

    /**
     * ユーザが作成したPJを返す
     * 公開中のみ表示
     * @param int    $user_id
     * @param int    $limit
     * @param string $mode    (options, all, list, first)
     * @param string $success (all, success, fail)
     * @param int    $recursive
     * @return array
     */
    public function get_pj_of_user($user_id, $mode = 'options', $success = 'all', $limit = 10, $recursive = 0)
    {
        $options = array(
            'conditions' => array(
                'Project.user_id' => $user_id, 'Project.opened' => 'yes', 'Project.stop' => 0
            ), 'limit' => $limit, 'recursive' => $recursive, 'fields' => array(
                'Project.id', 'Project.project_name', 'Project.category_id', 'Project.goal_amount',
                'Project.collection_start_date', 'Project.collection_end_date', 'Project.backers',
                'Project.opened', 'Project.collected_amount',
            )
        );
        $options = $this->_switch_success($options, $success);
        return $this->_switch_return_mode($mode, $options);
    }

    /**
     * 支援したPJを返す
     * @param int    $user_id
     * @param string $mode    (options, all, list, first)
     * @param string $success (all, success, fail)
     * @param int    $limit
     * @return array
     */
    public function get_back_pj_of_user($user_id, $mode = 'options', $success = 'all', $limit = 10)
    {
        $options = array(
            'joins' => array(
                array(
                    'table' => 'backed_projects', 'alias' => 'BackedProject', 'type' => 'inner',
                    'conditions' => array('Project.id = BackedProject.project_id',),
                ), array(
                    'table' => 'backing_levels', 'alias' => 'BackingLevel', 'type' => 'inner',
                    'conditions' => array('BackedProject.backing_level_id = BackingLevel.id',),
                ), array(
                    'table' => 'users', 'alias' => 'User', 'type' => 'inner',
                    'conditions' => array('Project.user_id = User.id',),
                ),
            ), 'order' => array('BackedProject.created' => 'DESC'), 'limit' => $limit, 'conditions' => array(
                'BackedProject.user_id' => $user_id,
                'BackedProject.status' => Configure::read('STATUSES_FOR_OPEN'), 'Project.opened' => 'yes'
            ), 'fields' => array(
                'Project.id', 'Project.project_name', 'Project.goal_amount', 'Project.collected_amount',
                'Project.collection_end_date', 'Project.category_id', 'Project.backers', 'Project.description',
                'Project.opened', 'BackedProject.id', 'BackedProject.invest_amount', 'BackedProject.comment',
                'BackingLevel.id', 'BackingLevel.invest_amount', 'BackingLevel.return_amount',
                'BackingLevel.now_count', 'User.id', 'User.nick_name',
            ),
        );
        $options = $this->_switch_project_status($success, $options);
        return $this->_switch_return_mode($mode, $options);
    }

    /**
     * conditionsのステータス（支援、成功、失敗）の切り替え
     */
    private function _switch_project_status($success, $options)
    {
        switch($success){
            case 'all':
                $options['conditions']['BackedProject.status'] = Configure::read('STATUSES_FOR_OPEN');
                break;
            case 'success':
                $options['conditions']['BackedProject.status'] = Configure::read('STATUSES_OK');
                break;
            case 'fail':
                $options['conditions']['BackedProject.status'] = Configure::read('STATUSES_NG');
        }
        return $options;
    }

    /**
     * TOPPAGE用　優先プロジェクトの取得関数
     */
    public function get_toppage_top_pj($top_pj_ids, $pickup_pj_id)
    {
        $options                                = array(
            'conditions' => array(
                'Project.opened' => 'yes', 'Project.stop' => 0
            )
        );
        $options['conditions']['Project.id']    = $top_pj_ids;
        $options['conditions']['Project.id !='] = $pickup_pj_id;
        $options['order']                       = array(
            'Project.active ASC', 'Project.collected_amount DESC'
        );
        $options['limit']                       = 10;
        return $this->find('all', $options);
    }

    /**
     * TOPPAGE用 Project一覧の取得関数
     * 公開中、ストップでない
     * ピックアップPJと、優先PJを除く
     * 募集してる順、支援額多い順
     */
    public function get_toppage_pj($pickup_pj_id, $top_pj_ids, $limit = 30)
    {
        $count = count($top_pj_ids);
        if($pickup_pj_id){
            $top_pj_ids[] = $pickup_pj_id;
            $count += 1;
        }
        $options = array(
            'conditions' => array(
                'Project.id !=' => $top_pj_ids, 'Project.opened' => 'yes', 'Project.stop' => 0,
            ), 'order' => array(
                'Project.active' => 'ASC', 'Project.collected_amount' => 'DESC'
            ), 'limit' => ($limit - $count), 'fields' => array(
                'Project.id', 'Project.project_name', 'Project.category_id', 'Project.goal_amount',
                'Project.collection_start_date', 'Project.collection_end_date', 'Project.backers',
                'Project.opened', 'Project.collected_amount',
            )
        );
        return $this->find('all', $options);
    }

    /**
     * プロジェクト検索用
     * プロジェクトのオプションを返す関数
     */
    public function search_projects($category_id, $area_id, $sort, $limit = 30)
    {
        $options = array(
            'conditions' => array(
                'Project.opened' => 'yes', 'Project.stop' => 0,
            ), 'order' => array(
                'Project.active' => 'ASC', 'Project.collected_amount' => 'DESC',
            ), 'limit' => $limit, 'fields' => array(
                'Project.id', 'Project.project_name', 'Project.category_id', 'Project.goal_amount',
                'Project.collection_start_date', 'Project.collection_end_date', 'Project.backers',
                'Project.opened', 'Project.collected_amount',
            )
        );
        if(!empty($category_id)){
            $options['conditions']['Project.category_id'] = $category_id;
        }
        if(!empty($area_id)){
            $options['conditions']['Project.area_id'] = $area_id;
        }
        switch($sort){
            case 1: //支援金額の多い順
                //$options['order']['Project.collected_amount'] = 'DESC';
                break;
            case 2: //新着順
                unset($options['order']['Project.collected_amount']);
                $options['order']['Project.collection_start_date'] = 'DESC';
                break;
            case 3: //募集終了が近い順
                unset($options['order']['Project.collected_amount']);
                $options['order']['Project.collection_end_date'] = 'ASC';
                break;
        }
        return $options;
    }

    /**
     * プロジェクトの削除
     *  - 公開中は削除できない
     *  - 支援数が1以上は削除できない
     * @param int $project_id
     * @return array $result 1 => OK, 2 => ERROR, 3 => 削除できない状態
     */
    public function delete_project($project_id)
    {
        $project = $this->findById($project_id);
        if(!$project){
            return array(
                2, null
            );
        }
        //削除できるか確認
        if($project['Project']['opened'] != 'no'){
            return array(
                3, '公開中のプロジェクトは削除できません'
            );
        }
        if($project['Project']['backers'] != 0){
            return array(
                3, '支援者のいるプロジェクトは削除できません'
            );
        }
        if($this->delete($project_id)){
            return array(
                1, null
            );
        }else{
            return array(
                2, null
            );
        }
    }

    /**
     * 支援可能なプロジェクトかチェックする関数
     * OKならProject配列を返す
     *  - 存在するか？
     *  - 公開中か？
     *  - Activeか？
     *  - 募集期間は終わっていないか？
     *  - STOPされてないか？
     * @param int $project_id
     * @param int $recursive
     * @return array $project or null
     */
    public function check_backing_enable($project_id, $recursive = 0)
    {
        $this->recursive = $recursive;
        $project         = $this->findById($project_id);
        if(!$project){
            return null;
        }
        if($project['Project']['opened'] != 'yes'){
            return null;
        }
        if($project['Project']['active'] != 'yes'){
            return null;
        }
        if(!$this->_check_collection_end_date($project)){
            return null;
        }
        if($project['Project']['stop']){
            return null;
        }
        return $project;
    }

    /**
     * 募集日が過ぎていないか確認する関数
     * @param array $project
     * @return boolean 過ぎてたらfalse
     */
    private function _check_collection_end_date($project)
    {
        $current_date = new DateTime(date('Y-m-d,H:i:s'));
        $closing_date = new DateTime($project['Project']['collection_end_date']);
        if($closing_date > $current_date){
            return true;
        }
        return false;
    }

    /**
     * プロジェクトをプロジェクト名から取得する
     * 公開されているもののみ
     * オプションを返す
     */
    public function get_pj_by_name($pj_name)
    {
        return array(
            'conditions' => array(
                'Project.project_name LIKE' => "%{$pj_name}%", 'Project.opened' => 'yes', 'Project.stop' => 0
            )
        );
    }

    /**
     * プロジェクトの公開
     * - 写真が登録されている
     * - コンテンツが登録されている
     * - 支援パターンが登録されている
     * - 目標金額が入力されている
     * - プロジェクト名が入力されている
     * - カテゴリーが選択されている
     * - プロジェクト概要が入力されている
     * 以上がクリアされていれば公開される。
     * 公開時に、現在の時間から、collection_termの日数後の時間をcollection_end_dateに登録する
     * 現在の時間をcollection_start_dateに登録する
     * 更に、SiteFeeを登録する
     * 公開後は、募集開始と終了時間、Feeを変更することはできない
     * @param int   $project_id
     * @param array $setting
     * @return array array(code, message) 1 => ok, 2 => ERROR 3 => 公開できない状態
     */
    public function project_open($project_id, $setting)
    {
        $check_result = $this->_open_checks($project_id);
        if($check_result[0] != 1){
            return $check_result;
        }
        $project = $check_result[2];
        //開始時間の取得
        $start_time = date('Y-m-d H:i:s');
        //終了時間の設定
        $term     = $project['Project']['collection_term'];
        $end_time = date('Y-m-d H:i:s', strtotime($start_time.'+'.$term.'days'));
        //登録データの作成
        $data = array(
            'Project' => array(
                'opened' => 'yes', 'collection_start_date' => $start_time, 'collection_end_date' => $end_time,
                'site_fee' => $setting['fee']
            )
        );
        //公開処理
        $this->id = $project_id;
        if($this->save($data, true, array(
            'opened', 'collection_start_date', 'collection_end_date', 'site_fee'
        ))
        ){
            return array(
                1, null
            );
        }else{
            return array(
                2, null
            );
        }
    }

    /**
     * プロジェクト公開時のチェック
     */
    private function _open_checks($project_id)
    {
        $project = $this->findById($project_id);
        if(!$project){
            return array(
                2, null
            );
        }
        $BackingLevel = ClassRegistry::init('BackingLevel');
        if(!$BackingLevel->findByProjectId($project_id)){
            return array(
                3, '支援パターンが登録されていません。'
            );
        }
        if(empty($project['Project']['goal_amount'])
           || empty($project['Project']['project_name'])
           || empty($project['Project']['description'])
           || empty($project['Project']['category_id'])
        ){
            return array(
                3, 'プロジェクト名、プロジェクト概要、カテゴリー、目標金額のいずれかが入力されていません。'
            );
        }
        if(empty($project['Project']['pic'])){
            return array(
                3, 'サムネイル画像が登録されていません。'
            );
        }
        if(!empty($project['Project']['stop'])){
            return array(
                3, 'プロジェクトが中断中の状態は公開できません。'
            );
        } //どういう意味だったかな？
        return array(
            1, null, $project
        );
    }

    /**
     * 公開ストップ処理
     */
    public function project_stop($id)
    {
        $project = $this->findById($id);
        if(!$project){
            return false;
        }
        $this->id = $id;
        if($this->saveField('stop', 1)){
            return true;
        }
        return false;
    }

    /**
     * 公開再開処理
     */
    public function project_restart($id)
    {
        $project = $this->findById($id);
        if(!$project){
            return false;
        }
        $this->id = $id;
        if($this->saveField('stop', 0)){
            return true;
        }
        return false;
    }

    /**
     * プロジェクト作成ユーザの変更
     */
    public function change_user($pj_id, $user_id)
    {
        $this->id = $pj_id;
        if($this->saveField('user_id', $user_id)){
            return true;
        }
        return false;
    }

    /**
     * プロジェクトの現在の支援金額等に$bpの内容を追加する
     * $bpは、これからGMOペイメントで仮売り上げ登録する予定の支援内容
     * @param array $bp (backed_project)
     * @param array $pj (project)
     * @return array / null
     */
    public function add_backed_to_project($bp, $pj)
    {
        $BP               = ClassRegistry::init('BackedProject');
        $backed_projects  = $BP->get_by_pj_id($pj['Project']['id'], false);
        $backers          = count($backed_projects);
        $collected_amount = 0;
        foreach($backed_projects as $b){
            $collected_amount += $b['BackedProject']['invest_amount'];
        }
        $pj['Project']['backers']          = $backers + 1;
        $pj['Project']['collected_amount'] = $collected_amount + $bp['amount'];
        $this->id                          = $pj['Project']['id'];
        if(!$this->save($pj, true, array(
            'backers', 'collected_amount'
        ))
        ){
            return null;
        }else{
            return $pj;
        }
    }

    /**
     * project view用
     * @param int $id
     * @return array $project
     */
    public function get_project_or_project_url($id)
    {
        $options = array(
            'joins' => array(
                array(
                    'table' => 'categories', 'alias' => 'Category', 'type' => 'inner',
                    'conditions' => array('Category.id = Project.category_id'),
                ),
                array(
                    'table' => 'areas', 'alias' => 'Area', 'type' => 'left',
                    'conditions' => array('Project.area_id = Area.id'),
                ),
            ),
            'conditions' => array(
                'Project.opened' => 'yes',
                'Project.stop' => 0,
                'Project.id' => $id
            ),
            'fields' => array(
                'Project.id', 'Project.user_id', 'Project.project_name',
                'Project.goal_amount', 'Project.collection_start_date',
                'Project.collection_end_date', 'Project.thumbnail_type',
                'Project.thumbnail_movie_type', 'Project.thumbnail_movie_code',
                'Project.backers', 'Project.comment_cnt', 'Project.report_cnt',
                'Project.collected_amount', 'Project.description', 'Category.name',
                'Area.name',
            )
        );
        $project = $this->find('first', $options);
        if(!$project){
            return null;
        }
        $BackingLevel            = ClassRegistry::init('BackingLevel');
        $project['BackingLevel'] = $BackingLevel->findAllByProjectId($id);
        $User                    = ClassRegistry::init('User');
        $project['User']         = $User->findById($project['Project']['user_id']);
        return $project;
    }

    /**
     * Projectに、活動報告の数を登録する関数
     */
    public function save_report_cnt($project_id, $report_cnt)
    {
        $this->id = $project_id;
        if($this->saveField('report_cnt', $report_cnt)){
            return true;
        }
        return false;
    }

    /**
     * Projectに、コメントの数を登録する関数
     */
    public function save_comment_cnt($project_id, $comment_cnt)
    {
        $this->id = $project_id;
        if($this->saveField('comment_cnt', $comment_cnt)){
            return true;
        }
        return false;
    }

    /**
     * テストプロジェクトを削除する
     */
    public function delete_test_pj($id)
    {
        if($this->delete($id)){
            $PC = ClassRegistry::init('ProjectContent');
            if($PC->deleteAll(array('ProjectContent.project_id' => $id), false)){
                $PCO = ClassRegistry::init('ProjectContentOrder');
                if($PCO->deleteAll(array('ProjectContentOrder.project_id' => $id), false)){
                    $BP = ClassRegistry::init('BackedProject');
                    if($BP->deleteAll(array('BackedProject.project_id' => $id), false)){
                        $BL = ClassRegistry::init('BackingLevel');
                        if($BL->deleteAll(array('BackingLevel.project_id' => $id), false)){
                            $CO = ClassRegistry::init('Comment');
                            if($CO->deleteAll(array('Comment.project_id' => $id), false)){
                                $FA = ClassRegistry::init('FavouriteProject');
                                if($FA->deleteAll(array('FavouriteProject.project_id' => $id), false)){
                                    $RE = ClassRegistry::init('Report');
                                    $reports = $RE->findAllByProjectId($id);
                                    $id_list = array();
                                    foreach($reports as $r){
                                        $id_list[] = $r['Report']['id'];
                                    }
                                    if($RE->deleteAll(array('Report.project_id' => $id), false)){
                                        $REC = ClassRegistry::init('ReportContent');
                                        if($REC->deleteAll(array('ReportContent.report_id' => $id_list) ,false)){
                                            $RECO = ClassRegistry::init('ReportContentOrder');
                                            if($RECO->deleteAll(array('ReportContentOrder.report_id' => $id_list), false)){
                                                return true;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

        }
        return false;
    }

}
