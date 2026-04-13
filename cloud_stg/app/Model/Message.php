<?php
App::uses('AppModel', 'Model');

/**
 * Message Model
 */
class Message extends AppModel
{

    /**
     * Validation rules
     * @var array
     */
    public $validate = array();

    /**
     * messagesテーブルのpairフィールドを作成する
     * @param int $user_id (相手のuser_id)
     * @param int $my_id   (ログインユーザのuser_id)
     * @return string $pair
     */
    public function make_pair($user_id, $my_id)
    {
        if($user_id > $my_id){
            return $my_id.'_'.$user_id;
        }else{
            return $user_id.'_'.$my_id;
        }
    }

}
