<?php
App::uses('AppModel', 'Model');

class ProjectContentOrder extends AppModel
{

    public $primaryKey = 'project_id';

    /**
     * belongsTo associations
     */
    public $belongsTo = array(
            'Project' => array(
                    'className' => 'Project', 'foreignKey' => 'project_id', 'conditions' => '', 'fields' => '',
                    'order' => ''
            )
    );

    /**
     * orderに追加する関数
     * @param array $content
     * @return boolean
     */
    public function order_add($content)
    {
        //既にorderがあるか？
        $order = $this->findByProjectId($content['ProjectContent']['project_id']);
        if(!$order){
            //ない場合は新たに作成
            $order_data = array(
                    'ProjectContentOrder' => array(
                            'project_id' => $content['ProjectContent']['project_id'],
                            'order' => json_encode(array($content['ProjectContent']['id']))
                    )
            );
            $this->create();
            if($this->save($order_data)){
                return true;
            }
        }else{
            //既にある場合は後ろに追加
            $this->id   = $content['ProjectContent']['project_id'];
            $order_data = json_decode($order['ProjectContentOrder']['order']);
            array_push($order_data, $content['ProjectContent']['id']);
            if($this->saveField('order', json_encode($order_data))){
                return true;
            }
        }
        return false;
    }

    /**
     * 前に挿入する関数
     * @param array $content
     * @param int   $order_no (content_id)
     * @return boolean
     */
    public function order_before($content, $order_no)
    {
        $order = $this->findByProjectId($content['ProjectContent']['project_id']);
        if(!$order){
            return false;
        }
        $order_data = json_decode($order['ProjectContentOrder']['order']);
        $key        = array_search($order_no, $order_data);
        if($key === false){
            return false;
        }
        array_splice($order_data, $key, 0, $content['ProjectContent']['id']);
        $this->id = $content['ProjectContent']['project_id'];
        if($this->saveField('order', json_encode($order_data))){
            return true;
        }
        return false;
    }

    /**
     * orderの要素を削除する関数
     * @param array $content
     * @return boolean
     */
    public function order_delete($content)
    {
        $order = $this->findByProjectId($content['ProjectContent']['project_id']);
        if(!$order){
            return false;
        }
        $order_data = json_decode($order['ProjectContentOrder']['order']);
        $key        = array_search($content['ProjectContent']['id'], $order_data);
        if($key === false){
            return false;
        }
        array_splice($order_data, $key, 1);
        $this->id = $content['ProjectContent']['project_id'];
        if($this->saveField('order', json_encode($order_data))){
            return true;
        }
        return false;
    }

    /**
     * 並び順の更新（Ajax）
     * @param int   $project_id
     * @param array $order_data
     * @return boolean
     */
    public function sort($project_id, $order_data)
    {
        $this->id = $project_id;
        if($this->saveField('order', json_encode($order_data['item']))){
            return true;
        }
        return false;
    }

    /**
     * 上に移動（Ajax）
     * @param int   $project_id
     * @param array $data
     * @return boolean
     */
    public function move_up($project_id, $data)
    {
        $order = $this->findByProjectId($project_id);
        if(!$order){
            return false;
        }
        $order_data = json_decode($order['ProjectContentOrder']['order']);
        $my_key     = array_search($data['my_id'], $order_data);
        $ue_key     = array_search($data['ue_id'], $order_data);
        if($my_key === false || $ue_key === false){
            return false;
        }
        $order_data[$my_key] = $data['ue_id'];
        $order_data[$ue_key] = $data['my_id'];
        $this->id            = $project_id;
        if($this->saveField('order', json_encode($order_data))){
            return true;
        }
        return false;
    }

    /**
     * 下に移動（Ajax）
     * @param int   $project_id
     * @param array $data
     * @return boolean
     */
    public function move_down($project_id, $data)
    {
        $order = $this->findByProjectId($project_id);
        if(!$order){
            return false;
        }
        $order_data = json_decode($order['ProjectContentOrder']['order']);
        $my_key     = array_search($data['my_id'], $order_data);
        $down_key   = array_search($data['down_id'], $order_data);
        if($my_key === false || $down_key === false){
            return false;
        }
        $order_data[$my_key]   = $data['down_id'];
        $order_data[$down_key] = $data['my_id'];
        $this->id              = $project_id;
        if($this->saveField('order', json_encode($order_data))){
            return true;
        }
        return false;
    }

}
