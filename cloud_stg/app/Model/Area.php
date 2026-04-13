<?php
App::uses('AppModel', 'Model');

/**
 * Area Model
 * エリア（カテゴリ２）
 */
class Area extends AppModel
{

    /**
     * Display field
     * @var string
     */
    public $displayField = 'name';

    /**
     * Validation rules
     * @var array
     */
    public $validate = array('name' => array('notblank' => array('rule' => array('notblank')),),);

    //The Associations below have been created with all possible keys, those that are not needed can be removed
    /**
     * hasMany associations
     * @var array
     */
    public $hasMany = array(
            'Project' => array(
                    'className' => 'Project', 'foreignKey' => 'category_id', 'dependent' => false, 'conditions' => '',
                    'fields' => '', 'order' => '', 'limit' => '', 'offset' => '', 'exclusive' => '',
                    'finderQuery' => '', 'counterQuery' => ''
            )
    );

    /**
     * カテゴリー2の取得
     */
    public function get_areas()
    {
        $options = array('order' => array('Area.order' => 'ASC'));
        return $this->find('all', $options);
    }

    /**
     * カテゴリー2リストの取得
     */
    public function get_list()
    {
        $options = array('order' => array('Area.order' => 'ASC'));
        $areas = $this->find('list', $options);
        return array_merge(array('' => '-----', $areas));
    }

    /**
     * カテゴリー2の利用チェック
     * プロジェクトで既に登録されているかチェック
     * 登録されている場合は、Trueを返す
     */
    public function use_check($area_id)
    {
        $Project = ClassRegistry::init('Project');
        if($Project->findByAreaId($area_id)){
            return true;
        }
        return false;
    }

}
