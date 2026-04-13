<?php
App::uses('AppModel', 'Model');

/**
 * BackingLevel Model
 * @property Project       $Project
 * @property BackedProject $BackedProject
 */
class BackingLevel extends AppModel
{

    /**
     * Validation rules
     * @var array
     */
    public $validate = array(
            'level name' => array('notblank' => array('rule' => array('notblank')),), 'invest_amount' => array(
                    'notblank' => array('rule' => array('notblank')), 'numeric' => array('rule' => array('numeric')),
            ), 'return_amount' => array(
                    'notblank' => array(
                            'rule' => array('notblank'), 'message' => 'rewards cannot be left blank',
                    ),
            ),
    );

    //The Associations below have been created with all possible keys, those that are not needed can be removed
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

    /**
     * hasMany associations
     * @var array
     */
    public $hasMany = array(
            'BackedProject' => array(
                    'className' => 'BackedProject', 'foreignKey' => 'backing_level_id', 'dependent' => false,
                    'conditions' => '', 'fields' => '', 'order' => '', 'limit' => '', 'offset' => '', 'exclusive' => '',
                    'finderQuery' => '', 'counterQuery' => ''
            )
    );

    /**
     * 支援パターンがプロジェクトに所属するかチェックする関数
     * @param int $backing_level_id
     * @param int $project_id
     * @return array $backing_level or null
     */
    public function check_backing_level($backing_level_id, $project_id)
    {
        $this->id      = $backing_level_id;
        $backing_level = $this->read();
        if($backing_level['BackingLevel']['project_id'] != $project_id){
            return null;
        }else{
            return $backing_level;
        }
    }

    /**
     * 支援金額のチェック関数
     * 選択している支援パターンの設定金額以上が入力されているか？
     * @param array $backing_level
     * @param int   $invest_amount
     * @return boolean
     */
    public function check_invest_amount($backing_level, $invest_amount)
    {
        if($backing_level['BackingLevel']['invest_amount'] <= $invest_amount){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 支援パターンが最大支援回数を超過していないかのチェック関数
     */
    public function check_max_count($backing_level)
    {
        if(isset($backing_level['BackingLevel']['max_count'])
           && $backing_level['BackingLevel']['max_count']
        ){
            if($backing_level['BackingLevel']['max_count'] <= $backing_level['BackingLevel']['now_count']){
                return false;
            }
        }
        return true;
    }

    /**
     * プロジェクトの支援パターンを更新する関数
     * 支援パターンデータが$dataに入っているので$project_idを適用して、全部保存する
     * その後、$dataにない支援パターンを削除する
     * controller側でtransctionしているのでここでは考慮しない
     */
    public function edit_backing_level($data, $project_id)
    {
        $backing_level_ids = array();
        foreach($data as $d){
            $d['project_id'] = $project_id;
            if(!empty($d['id'])){
                $this->id = $d['id'];
            }else{
                $this->create();
            }
            if(!$this->save(array(
                    'BackingLevel' => $d, true, array(
                            'project_id', 'invest_amount', 'return_amount', 'max_count', 'delivery'
                    )
            ))
            ){
                return false;
            }
            $backing_level_ids[] = $this->id;
        }
        $conditions = array(
                'BackingLevel.project_id' => $project_id, 'NOT' => array('BackingLevel.id' => $backing_level_ids)
        );
        if(!$this->deleteAll($conditions, false)){
            return false;
        }
        return true;
    }

    /**
     * 支援パターンの現在購入回数を登録する関数
     * @param $backing_level
     * @return bool
     */
    public function put_backing_level_now_count($backing_level)
    {
        $conditions = array(
                'BackedProject.backing_level_id' => $backing_level['BackingLevel']['id'],
                'BackedProject.status' => Configure::read('STATUSES_FOR_OPEN')
        );
        $now_count  = $this->BackedProject->find('count', array('conditions' => $conditions));
        $this->id   = $backing_level['BackingLevel']['id'];
        if($this->saveField('now_count', $now_count + 1)){
            return true;
        }
        return false;
    }

}
