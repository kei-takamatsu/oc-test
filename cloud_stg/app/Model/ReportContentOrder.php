<?php
App::uses('AppModel', 'Model');

class ReportContentOrder extends AppModel
{

    public $primaryKey = 'report_id';

    /**
     * belongsTo associations
     */
    public $belongsTo = array(
            'Report' => array(
                    'className' => 'Report', 'foreignKey' => 'report_id', 'conditions' => '', 'fields' => '',
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
        $order = $this->findByReportId($content['ReportContent']['report_id']);
        if(!$order){
            //ない場合は新たに作成
            $order_data = array(
                    'ReportContentOrder' => array(
                            'report_id' => $content['ReportContent']['report_id'],
                            'order' => json_encode(array($content['ReportContent']['id']))
                    )
            );
            $this->create();
            if($this->save($order_data)){
                return true;
            }
        }else{
            //既にある場合は後ろに追加
            $this->id   = $content['ReportContent']['report_id'];
            $order_data = json_decode($order['ReportContentOrder']['order']);
            array_push($order_data, $content['ReportContent']['id']);
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
        $order = $this->findByReportId($content['ReportContent']['report_id']);
        if(!$order){
            return false;
        }
        $order_data = json_decode($order['ReportContentOrder']['order']);
        $key        = array_search($order_no, $order_data);
        if($key === false){
            return false;
        }
        array_splice($order_data, $key, 0, $content['ReportContent']['id']);
        $this->id = $content['ReportContent']['report_id'];
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
        $order = $this->findByReportId($content['ReportContent']['report_id']);
        if(!$order){
            return false;
        }
        $order_data = json_decode($order['ReportContentOrder']['order']);
        $key        = array_search($content['ReportContent']['id'], $order_data);
        if($key === false){
            return false;
        }
        array_splice($order_data, $key, 1);
        $this->id = $content['ReportContent']['report_id'];
        if($this->saveField('order', json_encode($order_data))){
            return true;
        }
        return false;
    }

    /**
     * 並び順の更新（Ajax）
     * @param int   $report_id
     * @param array $order_data
     * @return boolean
     */
    public function sort($report_id, $order_data)
    {
        $this->id = $report_id;
        if($this->saveField('order', json_encode($order_data['item']))){
            return true;
        }
        return false;
    }

    /**
     * 上に移動（Ajax）
     * @param int   $report_id
     * @param array $data
     * @return boolean
     */
    public function move_up($report_id, $data)
    {
        $order = $this->findByReportId($report_id);
        if(!$order){
            return false;
        }
        $order_data = json_decode($order['ReportContentOrder']['order']);
        $my_key     = array_search($data['my_id'], $order_data);
        $ue_key     = array_search($data['ue_id'], $order_data);
        if($my_key === false || $ue_key === false){
            return false;
        }
        $order_data[$my_key] = $data['ue_id'];
        $order_data[$ue_key] = $data['my_id'];
        $this->id            = $report_id;
        if($this->saveField('order', json_encode($order_data))){
            return true;
        }
        return false;
    }

    /**
     * 下に移動（Ajax）
     * @param int   $report_id
     * @param array $data
     * @return boolean
     */
    public function move_down($report_id, $data)
    {
        $order = $this->findByReportId($report_id);
        if(!$order){
            return false;
        }
        $order_data = json_decode($order['ReportContentOrder']['order']);
        $my_key     = array_search($data['my_id'], $order_data);
        $down_key   = array_search($data['down_id'], $order_data);
        if($my_key === false || $down_key === false){
            return false;
        }
        $order_data[$my_key]   = $data['down_id'];
        $order_data[$down_key] = $data['my_id'];
        $this->id              = $report_id;
        if($this->saveField('order', json_encode($order_data))){
            return true;
        }
        return false;
    }

}
