<?php
App::uses('AppModel', 'Model');

/**
 * Category Model
 * @property Project $Project
 */
class Category extends AppModel
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
     * カテゴリーの取得
     */
    public function get_categories()
    {
        $options = array('order' => array('Category.order' => 'ASC'));
        return $this->find('all', $options);
    }

    /**
     * カテゴリーリストの取得
     */
    public function get_list()
    {
        $options = array('order' => array('Category.order' => 'ASC'));
        return $this->find('list', $options);
    }

    /**
     * カテゴリーの利用チェック
     * プロジェクトで既に登録されているかチェック
     * 登録されている場合は、Trueを返す
     */
    public function use_check($category_id)
    {
        $Project = ClassRegistry::init('Project');
        if($Project->findByCategoryId($category_id)){
            return true;
        }
        return false;
    }

}
