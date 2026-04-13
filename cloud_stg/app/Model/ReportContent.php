<?php
App::uses('AppModel', 'Model');

class ReportContent extends AppModel
{
    public $actsAs = array(
            'Filebinder.Bindable' => array(
                    'dbStorage' => false, 'beforeAttach' => 'resize',
            )
    );
    public $bindFields = array();
    public $validate = array(
            'img' => array(
                    'allowExtention' => array(
                            'rule' => array(
                                    'checkExtension', array(
                                            'jpg', 'jpeg', 'png', 'gif'
                                    )
                            ), 'message' => '拡張子が無効です', 'allowEmpty' => false
                    ), 'fileSize' => array(
                            'rule' => array(
                                    'checkFileSize', '0.5MB'
                            ), 'message' => 'ファイルサイズが0.5MBを超過しています'
                    ), 'illegalCode' => array('rule' => array('checkIllegalCode'),)
            ),
    );
    /**
     * belongsTo associations
     * @var array
     */
    public $belongsTo = array(
            'Report' => array(
                    'className' => 'Report', 'foreignKey' => 'report_id', 'conditions' => '', 'fields' => '',
                    'order' => ''
            )
    );

    function __construct()
    {
        parent::__construct();
        $this->bindFields[] = array(
                'field' => 'img', 'tmpPath' => Configure::read('file_path').'tmp'.DS,
                'filePath' => Configure::read('file_path').'upload'.DS,
        );
    }

    /**
     * 画像リサイズ
     * @param array $tmp_file_path
     * @return bool
     */
    public function resize($tmp_file_path)
    {
        return $this->resize_image($tmp_file_path, 750, null);
    }

    /**
     * サムネイル作成
     * @param array $file_path
     * @return bool
     */
    public function thumbnail($file_path)
    {
        return $this->create_thumbnail($file_path, array(//array(null, 200),
        ));
    }

    /**
     * 活動報告コンテンツのチェック
     *  - 存在するか
     *  - 自分のプロジェクトの活動報告か？
     * （違う場合はnullを返す）
     * @param int $report_content_id
     * @param int $user_id
     * @return array $report_content
     */
    public function check_content($report_content_id, $user_id)
    {
        $options = array(
                'joins' => array(
                        array(
                                'table' => 'reports', 'alias' => 'Report', 'type' => 'inner',
                                'conditions' => array('ReportContent.report_id = Report.id'),
                        ), array(
                                'table' => 'projects', 'alias' => 'Project', 'type' => 'inner',
                                'conditions' => array('Project.id = Report.project_id',),
                        ),
                ), 'conditions' => array(
                        'ReportContent.id' => $report_content_id, 'Project.user_id' => $user_id
                ), 'recursive' => -1,
        );
        return $this->find('first', $options);
    }

    /**
     * 活動報告のコンテンツを取得する関数
     * @param int $report_id
     * @return array $contents
     */
    public function get_contents($report_id)
    {
        $Order = ClassRegistry::init('ReportContentOrder');
        $order = $Order->findByReportId($report_id);
        if($order){
            $order_array = json_decode($order['ReportContentOrder']['order']);
            $order_str   = implode(',', $order_array);
            if($order){
                $options = array(
                        'conditions' => array('ReportContent.report_id' => $report_id),
                        'order' => "FIELD(id, ".$order_str.")", 'recursive' => -1
                );
                return $this->find('all', $options);
            }
        }
        return null;
    }

    /**
     * 活動報告コンテンツを追加する関数
     * @param array $data
     * @param int   $order_no (content_id)
     * @return array $content
     */
    public function item_add($data, $order_no)
    {
        $this->begin();
        if($this->save($data)){
            $Order   = ClassRegistry::init('ReportContentOrder');
            $content = $this->read();
            if($order_no){
                if($Order->order_before($content, $order_no)){
                    $this->commit();
                    return $content;
                }
            }else{
                if($Order->order_add($content)){
                    $this->commit();
                    return $content;
                }
            }
        }
        $this->rollback();
        return null;
    }

    /**
     * 動画の更新
     * @param array $data
     * @return array $report_content
     */
    public function movie_edit($data)
    {
        $this->id = $data['ReportContent']['id'];
        if($this->save($data, true, array(
                'movie_type', 'movie_code'
        ))
        ){
            return $this->read();
        }else{
            return null;
        }
    }

    /**
     * テキストの更新
     * @param array $data
     * @return array $report_content
     */
    public function text_edit($data)
    {
        $this->id = $data['ReportContent']['id'];
        if($this->save($data, true, array('txt_content',))){
            return $this->read();
        }else{
            return null;
        }
    }

    /**
     * 画像の更新
     * @param array $data
     * @return array $report_content
     */
    public function img_edit($data)
    {
        $this->id = $data['ReportContent']['id'];
        if($this->save($data, true, array('img_caption',))){
            return $this->read();
        }else{
            return null;
        }
    }

    /**
     * 活動報告コンテンツを削除する関数
     * （削除権限チェック済みの前提）
     * @param array $report_content
     * @return boolean
     */
    public function item_delete($report_content)
    {
        $this->begin();
        if($this->delete($report_content['ReportContent']['id'])){
            $Order = ClassRegistry::init('ReportContentOrder');
            if($Order->order_delete($report_content)){
                $this->commit();
                return true;
            }
        }
        $this->rollback();
        return false;
    }

}