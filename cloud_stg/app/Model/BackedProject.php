<?php
App::uses('AppModel', 'Model');

class BackedProject extends AppModel
{

    public $statuses_for_open = array(
            '仮売上完了', '売上確定', 'プロジェクト失敗'
    );
    public $status_ok = array('売上確定');

    /**
     * Validation rules
     * @var array
     */
    public $validate = array(
            'invest_amount' => array(
                    'notblank' => array(
                            'rule' => array('notblank'), 'message' => '支援金額を入力してください', 'allowEmpty' => false,
                    ), 'numeric' => array(
                            'rule' => array('numeric'), 'message' => '支援金額は数値を入力してください'
                    )
            ), 'comment' => array(
                    'notblank' => array(
                            'rule' => array('notblank'), 'message' => '支援コメントを入力してください', 'allowEmpty' => true,
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
            ), 'User' => array(
                    'className' => 'User', 'foreignKey' => 'user_id', 'conditions' => '', 'fields' => '', 'order' => ''
            ), 'BackingLevel' => array(
                    'className' => 'BackingLevel', 'foreignKey' => 'backing_level_id', 'conditions' => '',
                    'fields' => '', 'order' => ''
            )
    );

    /**
     * 該当プロジェクトの仮売上以上のbacked_projectを取得する関数
     */
    public function get_by_pj_id($project_id, $option = true)
    {
        $options = array(
                'conditions' => array(
                        'BackedProject.project_id' => $project_id,
                        'BackedProject.status' => Configure::read('STATUSES_FOR_OPEN')
                )
        );
        if($option){
            return $options;
        }
        return $this->find('all', $options);
    }

    /**
     * オーダーID作成関数
     * backed_projectのIDにランダムの半角英数字を追加
     * （GMOペイメントのOrderIdは、半角英数字と-のみ使用可）
     */
    public function get_order_id($backed_project)
    {
        $str      = substr(base_convert(md5(uniqid()), 16, 36), 0, 8);
        $bp       = $backed_project;
        $order_id = $bp['pj_id'].'-'.$bp['user_id'].'-'.$str;
        return $order_id;
    }

    /**
     * プロジェクトの支援者を取得するオプションを返す関数
     * （project_id、user_id（backer_id）から
     * userが本当にこのプロジェクトに支援していて、且つそのプロジェクトが成功しているかをチェックする）
     * user画像を表示するため、Userをfindする
     * @param int $project_id
     * @param int $backer_id ($user_id)
     * @return array $backer
     */
    public function check_backed_and_success_project($project_id, $backer_id)
    {
        $options                                        = $this->get_backers_of_project($project_id, 'options');
        $options['conditions']['BackedProject.user_id'] = $backer_id;
        $User                                           = ClassRegistry::init('User');
        return $User->find('all', $options);
    }

    /**
     * プロジェクトの支援者一覧を取得するオプションを返す関数
     * user画像を表示するため、Userをfindする
     * @param int    $project_id
     * @param string $mode ('options' or 'all')
     * @param int    $limit
     * @return array
     */
    public function get_backers_of_project($project_id, $mode = 'options', $limit = 30)
    {
        $options = array(
                'joins' => array(
                        array(
                                'table' => 'backed_projects', 'alias' => 'BackedProject', 'type' => 'inner',
                                'conditions' => array('BackedProject.user_id = User.id'),
                        ), array(
                                'table' => 'backing_levels', 'alias' => 'BackingLevel', 'type' => 'left',
                                'conditions' => array('BackedProject.backing_level_id = BackingLevel.id'),
                        ),
                ), 'conditions' => array(
                        'BackedProject.project_id' => $project_id,
                        'BackedProject.status' => Configure::read('STATUSES_FOR_OPEN')
                ), 'order' => array('BackedProject.created' => 'ASC'), 'limit' => $limit, 'fields' => array(
                        'User.id', 'User.nick_name', 'User.name', 'User.email', 'User.receive_address',
                        'BackedProject.id', 'BackedProject.created', 'BackedProject.invest_amount',
                        'BackedProject.comment', 'BackedProject.manual_flag', 'BackingLevel.id', 'BackingLevel.name',
                        'BackingLevel.invest_amount', 'BackingLevel.return_amount', 'BackingLevel.now_count'
                )
        );
        if($mode == 'options'){
            return $options;
        }
        $User = ClassRegistry::init('User');
        return $User->find('all', $options);
    }

    /**
     * プロジェクトの支援者一覧のリターン内容を整形する関数
     * 支援者一覧の配列を、
     * ユーザの重複表示をしないようにしつつ、
     * リターン内容のサマリーを作成する関数
     */
    public function make_return_summary($users)
    {
        $new_users = array();
        foreach($users as $u){
            if(!array_key_exists($u['User']['id'], $new_users)){
                $new_users[$u['User']['id']] = $u;
            }
            if(!empty($new_users[$u['User']['id']]['return_summary'][$u['BackingLevel']['id']])){
                $new_users[$u['User']['id']]['return_summary'][$u['BackingLevel']['id']]['count'] += 1;
            }else{
                $new_users[$u['User']['id']]['return_summary'][$u['BackingLevel']['id']]['count']         = 1;
                $new_users[$u['User']['id']]['return_summary'][$u['BackingLevel']['id']]['return_amount'] = $u['BackingLevel']['return_amount'];
            }
        }
        return $new_users;
    }

}
