<?php
App::uses('AppModel', 'Model');

/**
 * Report Model
 * @property Project $Project
 */
class Report extends AppModel
{

    public $actsAs = array(
            'Filebinder.Bindable' => array(
                    'dbStorage' => false, 'beforeAttach' => 'resize', 'afterAttach' => 'thumbnail'
            )
    );
    public $bindFields = array();
    /**
     * Validation rules
     * @var array
     */
    public $validate = array(
            'thumbnail' => array(
                    'allowExtention' => array(
                            'rule' => array(
                                    'checkExtension', array(
                                            'jpg', 'jpeg', 'png', 'gif'
                                    )
                            ), 'message' => '拡張子が無効です', 'allowEmpty' => true,
                    ), 'fileSize' => array(
                            'rule' => array(
                                    'checkFileSize', '0.5MB'
                            ), 'message' => 'ファイルサイズが0.5MBを超過しています',
                    ), 'illegalCode' => array('rule' => array('checkIllegalCode'),)
            ), 'title' => array(
                    'notblank' => array(
                            'rule' => array('notblank'), 'message' => 'タイトルを入力してください', 'allowEmpty' => false,
                            'required' => true,
                    ),
            ), 'project_id' => array(
                    'notblank' => array(
                            'rule' => array('notblank'), 'message' => 'プロジェクトを選択してください', 'allowEmpty' => false,
                            'required' => true,
                    ),
            ),
    );
    /**
     * belongsTo associations
     * @var array
     */
    public $belongsTo = array(
            'Project' => array(
                    'className' => 'Project', 'foreignKey' => 'project_id', 'conditions' => '', 'fields' => '',
                    'order' => ''
            )
    );

    function __construct()
    {
        parent::__construct();
        $this->bindFields[] = array(
                'field' => 'thumbnail', 'tmpPath' => Configure::read('file_path').'tmp'.DS,
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
        return $this->resize_image($tmp_file_path, 750, 500);
    }

    //The Associations below have been created with all possible keys, those that are not needed can be removed
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
     * 特定のプロジェクトの、1つの活動報告を取得する関数
     * （活動報告がプロジェクトに属するものかチェックする関数）
     * @param int $report_id
     * @param int $project_id
     * @return array $report or null
     */
    public function get_report_of_project($report_id, $project_id)
    {
        $options = array(
                'conditions' => array(
                        'Report.id' => $report_id, 'Report.project_id' => $project_id, 'Report.open' => 1
                )
        );
        return $this->find('first', $options);
    }

    /**
     * 特定のプロジェクトに属する公開中の活動報告のオプションを全て取得する関数
     */
    public function get_open_reports_options_of_project($project_id, $limit = 15, $recursive = -1)
    {
        return array(
                'joins' => array(
                        array(
                                'table' => 'projects', 'alias' => 'Project', 'type' => 'inner',
                                'conditions' => array('Project.id = Report.project_id',),
                        ),
                ), 'conditions' => array(
                        'Report.open' => 1, 'Report.project_id' => $project_id
                ), 'order' => array('Report.created' => 'DESC'), 'limit' => $limit, 'recursive' => $recursive,
                'fields' => array(
                        'Report.id', 'Report.project_id', 'Report.title', 'Report.created', 'Report.open',
                        'Project.user_id'
                )
        );
    }

    /**
     * 活動報告の編集権限チェック
     * @param int $report_id
     * @param int $user_id
     * @return array $report or null (nullなら権限なし）
     */
    public function edit_roll_check($report_id, $user_id)
    {
        $options                            = $this->get_all_reports_options_of_user($user_id);
        $options['conditions']['Report.id'] = $report_id;
        return $this->find('first', $options);
    }

    /**
     * ユーザの全ての活動報告（全プロジェクト分）のオプションを返す関数
     * @param int $user_id
     * @param int $limit
     * @return array $options
     */
    public function get_all_reports_options_of_user($user_id, $limit = 15)
    {
        return array(
                'joins' => array(
                        array(
                                'table' => 'projects', 'alias' => 'Project', 'type' => 'inner',
                                'conditions' => array('Report.project_id = Project.id',),
                        ),
                ), 'conditions' => array('Project.user_id' => $user_id), 'order' => array('Report.modified' => 'DESC'),
                'limit' => $limit, 'fields' => array(
                        'Project.project_name', 'Project.id', 'Project.user_id', 'Report.id', 'Report.created',
                        'Report.title', 'Report.open', 'Report.project_id'
                )
        );
    }

    /**
     * 活動報告の公開設定関数
     * 活動報告のタイトルが登録されていなければ公開できない
     * @param int $report_id
     * @return array(code, message) 1 -> ok, 2-> error, 3->公開できない状態にある
     */
    public function open($report_id)
    {
        //公開できる状態かチェック
        $report = $this->findById($report_id);
        if(empty($report['Report']['title'])){
            return array(
                    3, '公開にはタイトルの登録が必要です'
            );
        }
        $this->id = $report_id;
        $this->begin();
        if($this->saveField('open', 1)){
            $report_cnt = $this->get_report_number_of_project($report['Report']['project_id']);
            $Project    = ClassRegistry::init('Project');
            if($Project->save_report_cnt($report['Report']['project_id'], $report_cnt)){
                $this->commit();
                return array(
                        1, null
                );
            }
        }
        $this->rollback();
        return array(
                2, null
        );
    }

    /**
     * 特定のプロジェクトの活動報告の数を取得する関数
     */
    public function get_report_number_of_project($project_id)
    {
        $options = array(
                'conditions' => array(
                        'Report.open' => 1, 'Report.project_id' => $project_id
                )
        );
        return $this->find('count', $options);
    }

    /**
     * 活動報告の非公開設定関数
     */
    public function close($report_id)
    {
        $report = $this->findById($report_id);
        if(!$report){
            return false;
        }
        $this->id = $report_id;
        $this->begin();
        if($this->saveField('open', 0)){
            $report_cnt = $this->get_report_number_of_project($report['Report']['project_id']);
            $Project    = ClassRegistry::init('Project');
            if($Project->save_report_cnt($report['Report']['project_id'], $report_cnt)){
                $this->commit();
                return true;
            }
        }
        return false;
    }

    /**
     * 特定サイトの最新の活動報告を取得する関数（TOPページ用）
     */
    public function get_new_reports_in_site()
    {
        $options = array(
                'joins' => array(
                        array(
                                'table' => 'projects', 'alias' => 'Project', 'type' => 'inner',
                                'conditions' => array('Project.id = Report.project_id',),
                        ),
                ), 'conditions' => array('Report.open' => 1, 'Project.stop' => 0), 'order' => array('Report.created' => 'DESC'),
                'limit' => 10, 'fields' => array(
                        'Report.id', 'Report.project_id', 'Report.title', 'Report.created', 'Report.open',
                        'Project.user_id'
                )
        );
        return $this->find('all', $options);
    }

}
