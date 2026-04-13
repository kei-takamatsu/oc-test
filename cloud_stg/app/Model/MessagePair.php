<?php
App::uses('AppModel', 'Model');

/**
 * MessagePair Model
 */
class MessagePair extends AppModel
{

    public $primaryKey = 'message_pair_id';

    /**
     * Validation rules
     * @var array
     */
    public $validate = array();

    /**
     * message_pair_idを作成する
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

    /**
     * 既読にする
     * @param string pair_id
     * @return boolean
     */
    public function finish_read($pair_id)
    {
        $pair = $this->findByMessagePairId($pair_id);
        if(!$pair){
            return false;
        }
        $this->id = $pair_id;
        if($this->saveField('read_flag', true)){
            return true;
        }
        return false;
    }

}
