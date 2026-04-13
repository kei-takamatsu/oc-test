<?php
App::uses('AppModel', 'Model');

/**
 * Comment Model
 */
class Comment extends AppModel
{

    /**
     * Display field
     * @var string
     */
    public $displayField = 'comment';

    /**
     * Validation rules
     * @var array
     */
    public $validate = array('comment' => array('notblank' => array('rule' => array('notblank'),),),);

    /**
     * belongsTo associations
     * @var array
     */
    public $belongsTo = array(
            'User' => array(
                    'className' => 'User', 'foreignKey' => 'user_id', 'conditions' => '', 'fields' => '', 'order' => ''
            ), 'Project' => array(
                    'className' => 'Project', 'foreignKey' => 'project_id', 'conditions' => '', 'fields' => '',
                    'order' => ''
            )
    );

    /**
     * コメントを取得
     */
    public function search($option = true, $where = null, $limit = 30, $recursive = -1)
    {
        $options = array(
                'limit' => $limit, 'order' => array('Comment.created' => 'DESC'), 'recursive' => $recursive
        );
        if(!empty($where['Comment']['pj_id'])){
            $options['conditions']['Comment.project_id'] = $where['Comment']['pj_id'];
        }
        if(!empty($where['Comment']['word'])){
            $options['conditions']['Comment.comment LIKE'] = "%{$where['Comment']['word']}%";
        }
        if($option){
            return $options;
        }
        return $this->find('all', $options);
    }

    /**
     * ユーザーが投稿したコメントを取得
     */
    public function get_by_user_id($user_id, $option = true, $limit = 15, $recursive = -1)
    {
        $options = array(
                'conditions' => array('Comment.user_id' => $user_id), 'order' => array('Comment.created' => 'DESC'),
                'limit' => $limit, 'recursive' => $recursive
        );
        if($option){
            return $options;
        }
        return $this->find('all', $options);
    }

    /**
     * コメント削除権限チェック関数
     */
    public function check_role($comment_id, $user_id)
    {
        $conditions = array(
                'Comment.user_id' => $user_id, 'Comment.id' => $comment_id
        );
        return $this->find('first', array('conditions' => $conditions));
    }

    /**
     * プロジェクトのコメント一覧を取得する関数
     * ユーザ画像表示のため、Userモデルに対するオプションを返す
     * @param int  $project_id
     * @param bool $option
     * @param int  $limit
     * @return array $comment_options
     */
    public function get_by_pj_id($project_id, $option = true, $limit = 20)
    {
        $options = array(
                'joins' => array(
                        array(
                                'table' => 'comments', 'alias' => 'Comment', 'type' => 'inner',
                                'conditions' => array('Comment.user_id = User.id'),
                        ),
                ), 'conditions' => array('Comment.project_id' => $project_id),
                'order' => array('Comment.created' => 'DESC'), 'limit' => $limit, 'recursive' => -1, 'fields' => array(
                        'Comment.id', 'Comment.comment', 'Comment.created', 'User.id', 'User.active', 'User.nick_name'
                )
        );
        if($option){
            return $options;
        }
        return $this->find('all', $options);
    }

    /**
     * コメントの削除
     * projectsのcomment_cntを1減らす
     */
    public function delete_comment($id)
    {
        $comment = $this->findById($id);
        if(empty($comment)){
            return false;
        }
        $pj_id = $comment['Comment']['project_id'];
        $this->begin();
        if($this->delete($id)){
            $cnt     = $this->get_comment_number_of_project($pj_id);
            $Project = ClassRegistry::init('Project');
            if($Project->save_comment_cnt($pj_id, $cnt)){
                $this->commit();
                return true;
            }
        }
        $this->rollback();
        return false;
    }

    /**
     * 特定のプロジェクトのコメントの数を取得する関数
     */
    public function get_comment_number_of_project($project_id)
    {
        $options = array('conditions' => array('Comment.project_id' => $project_id));
        return $this->find('count', $options);
    }

}
