<?php
App::uses('AppModel', 'Model');

/**
 * FavouriteProject Model
 * @property User    $User
 * @property Project $Project
 */
class FavouriteProject extends AppModel
{
    public $displayField = 'project_id';

    /**
     * Validation rules
     * @var array
     */
    public $validate = array('backed' => array('notblank' => array('rule' => array('notblank'),),),);

    /**
     * belongsTo associations
     * @var array
     */
    public $belongsTo = array(
            'User' => array(
                    'className' => 'User', 'foreignKey' => 'user_id', 'conditions' => '', 'fields' => '', 'order' => ''
            ), 'Project' => array(
                    'className' => 'Project', 'foreignKey' => 'project_id', 'conditions' => array(
                            'Project.active' => 'yes', 'Project.opened' => 'yes'
                    ), 'fields' => '', 'order' => ''
            )
    );

    /**
     * ユーザが該当プロジェクトをお気に入り済みかどうかを返す関数
     * @param int $user_id
     * @param int $project_id
     * @return boolean
     */
    public function check_favourite($user_id, $project_id)
    {
        $conditions = array(
                'FavouriteProject.user_id' => $user_id, 'FavouriteProject.project_id' => $project_id
        );
        if($this->find('first', array('conditions' => $conditions))){
            return true;
        }else{
            return false;
        }
    }

    /**
     * お気に入りプロジェクトのオプションを返す関数
     * @param int $user_id
     * @param int $limit
     * @return array $options
     */
    public function get_favourite_project_options($user_id, $limit = 10)
    {
        return array(
                'joins' => array(
                        array(
                                'table' => 'favourite_projects', 'alias' => 'FavouriteProject', 'type' => 'inner',
                                'conditions' => array('Project.id = FavouriteProject.project_id',),
                        ),
                ), 'order' => array('FavouriteProject.created' => 'DESC'), 'limit' => $limit, 'conditions' => array(
                        'FavouriteProject.user_id' => $user_id, 'Project.opened' => 'yes', 'Project.stop' => 0
                ), 'fields' => array(
                        'Project.id', 'Project.project_name', 'Project.category_id', 'Project.goal_amount',
                        'Project.collection_start_date', 'Project.collection_end_date', 'Project.backers',
                        'Project.opened', 'Project.collected_amount', 'FavouriteProject.id'
                ),
        );
    }

}
