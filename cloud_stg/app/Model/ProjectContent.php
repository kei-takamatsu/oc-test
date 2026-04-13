<?php
App::uses('AppModel', 'Model');

class ProjectContent extends AppModel
{
    public $actsAs = array(
            'Filebinder.Bindable' => array(
                    'dbStorage' => false, 'beforeAttach' => 'resize',
                    //'afterAttach'=> 'thumbnail'
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
                                    'checkFileSize', '1MB'
                            ), 'message' => 'ファイルサイズが1MBを超過しています'
                    ), 'illegalCode' => array('rule' => array('checkIllegalCode'),)
            ),
    );
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
        return $this->resize_image($tmp_file_path, 1280, null);
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
     * プロジェクトのコンテンツを取得する関数
     * @param int $project_id
     * @return array $contents
     */
    public function get_contents($project_id)
    {
        $Order = ClassRegistry::init('ProjectContentOrder');
        $order = $Order->findByProjectId($project_id);
        if($order){
            $order_array = json_decode($order['ProjectContentOrder']['order']);
            $order_str   = implode(',', $order_array);
            if($order && $order_str){
                $options = array(
                        'conditions' => array('ProjectContent.project_id' => $project_id),
                        'order' => "FIELD(id, ".$order_str.")", 'recursive' => -1
                );
                return $this->find('all', $options);
            }
        }
        return null;
    }

    /**
     * プロジェクトコンテンツを追加する関数
     * @param array $data
     * @param int   $order_no (content_id)
     * @return array $content
     */
    public function item_add($data, $order_no)
    {
        $this->begin();
        if($this->save($data)){
            $Order   = ClassRegistry::init('ProjectContentOrder');
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
     * @return array $project_content
     */
    public function movie_edit($data)
    {
        $this->id = $data['ProjectContent']['id'];
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
     * @return array $project_content
     */
    public function text_edit($data)
    {
        $this->id = $data['ProjectContent']['id'];
        if($this->save($data, true, array('txt_content',))){
            return $this->read();
        }else{
            return null;
        }
    }

    /**
     * 画像の更新
     * @param array $data
     * @return array $project_content
     */
    public function img_edit($data)
    {
        $this->id = $data['ProjectContent']['id'];
        if($this->save($data, true, array('img_caption',))){
            return $this->read();
        }else{
            return null;
        }
    }

    /**
     * プロジェクトコンテンツを削除する関数
     * （削除権限チェック済みの前提）
     * @param array $project_content
     * @return boolean
     */
    public function item_delete($project_content)
    {
        $this->begin();
        if($this->delete($project_content['ProjectContent']['id'])){
            $Order = ClassRegistry::init('ProjectContentOrder');
            if($Order->order_delete($project_content)){
                $this->commit();
                return true;
            }
        }
        $this->rollback();
        return false;
    }

    /**
     * プロジェクトコンテンツを公開する関数
     * @param int $project_content_id
     * @return boolean
     */
    public function content_open($project_content_id)
    {
        $this->id = $project_content_id;
        if($this->saveField('open', 1)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * プロジェクトコンテンツを非公開にする関数
     * @param int $project_content_id
     * @return boolean
     */
    public function content_close($project_content_id)
    {
        $this->id = $project_content_id;
        if($this->saveField('open', 0)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 特定プロジェクトに属するプロジェクトコンテンツを全て公開する関数
     */
    public function open_all($project_id)
    {
        $options  = array(
                'joins' => array(
                        array(
                                'table' => 'projects', 'alias' => 'Project', 'type' => 'inner',
                                'conditions' => array('Project.id = ProjectContent.project_id'),
                        ),
                ), 'conditions' => array(
                        'ProjectContent.project_id' => $project_id, 'ProjectContent.open' => 0
                ), 'fields' => array('ProjectContent.id')
        );
        $contents = $this->find('all', $options);
        $flag     = true;
        if($contents){
            foreach($contents as $c){
                $this->id = $c['ProjectContent']['id'];
                if(!$this->saveField('open', 1)){
                    $flag = false;
                }
            }
        }
        if($flag){
            return true;
        }
        return false;
    }

}
