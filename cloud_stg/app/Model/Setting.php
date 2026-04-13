<?php
App::uses('AppModel', 'Model');

class Setting extends AppModel
{

    public $actsAs = array(
            'Filebinder.Bindable' => array(
                    'dbStorage' => false, 'afterAttach' => 'resize',
            )
    );

    public $bindFields = array();
    public $validate = array(
            'fee' => array(
                    'range' => array(
                            'rule' => array(
                                    'range', 3, 91
                            ), 'message' => '手数料率は4?90に設定してください', 'allowEmpty' => true
                    ),
            ), 'company_url' => array(
                    'url' => array(
                            'rule' => array('url'), 'message' => 'URLが正しくありません', 'allowEmpty' => true
                    ),
            ), 'site_name' => array(
                    'notblank' => array(
                            'rule' => array('notblank'), 'message' => 'サイト名を入力してください'
                    ), 'maxLength' => array(
                            'rule' => array(
                                    'maxLength', 15
                            ), 'message' => 'サイト名は15文字以内で入力してください。'
                    ),
            ), 'from_mail_address' => array(
                    'email' => array(
                            'rule' => array('email'), 'message' => '正しいメールアドレスを入力してください。',
                    ),
            ), 'admin_mail_address' => array(
                    'email' => array(
                            'rule' => array('email'), 'message' => '正しいメールアドレスを入力してください。',
                    ),
            ),
    );

    function __construct()
    {
        parent::__construct();
        $img_fields = array(
                'facebook_img' => 3, 'logo' => 3, 'top_box_pc' => 3, 'top_box_sm' => 3, 'top_box_img1' => 3,
                'top_box_img2' => 3
        );
        foreach($img_fields as $field => $file_size){
            $this->bindFields[]     = array(
                    'field' => $field, 'tmpPath' => Configure::read('file_path').'tmp'.DS,
                    'filePath' => Configure::read('file_path').'upload'.DS,
            );
            $this->validate[$field] = array(
                    'allowExtention' => array(
                            'rule' => array(
                                    'checkExtension', array(
                                            'jpg', 'jpeg', 'png', 'gif'
                                    )
                            ), 'message' => '拡張子が無効です', 'allowEmpty' => true,
                    ), 'fileSize' => array(
                            'rule' => array(
                                    'checkFileSize', $file_size.'MB'
                            ), 'message' => 'ファイルサイズが'.$file_size.'MBを超過しています'
                    ), 'illegalCode' => array('rule' => array('checkIllegalCode'),)
            );
        }
    }

    /**
     * 画像リサイズ
     * @param array $file_path
     * @return bool
     */
    public function resize($file_path)
    {
        if(strpos($file_path, '/top_box_sm/')){
            return $this->resize_image($file_path, 750, null);
        }elseif(strpos($file_path, '/top_box_pc/')){
            return $this->resize_image($file_path, 1500, null);
        }elseif(strpos($file_path, '/facebook_img/')){
            return $this->resize_image($file_path, 750, 393);
        }elseif(strpos($file_path, '/logo/')){
            return $this->resize_image($file_path, null, 30);
        }elseif(strpos($file_path, '/top_box_img1/')){
            return $this->resize_image($file_path, 600, null);
        }elseif(strpos($file_path, '/top_box_img2/')){
            return $this->resize_image($file_path, 600, null);
        }
        return false;
    }

    /**
     * ピックアッププロジェクトに設定する関数
     */
    public function set_pickup_project($project_id)
    {
        $options = array(
                'conditions' => array(
                        'Project.opened' => 'yes', 'Project.stop' => 0, 'Project.id' => $project_id,
                )
        );
        $Project = ClassRegistry::init('Project');
        $project = $Project->find('first', $options);
        if($project){
            $this->id = 1;
            if($this->saveField('toppage_pickup_project_id', $project_id)){
                return true;
            }
        }
        return false;
    }

    /**
     * ピックアッププロジェクトを解除する関数
     */
    public function unset_pickup_project()
    {
        $this->id = 1;
        if($this->saveField('toppage_pickup_project_id', null)){
            return true;
        }
        return false;
    }

    /**
     * 優先表示プロジェクトに設定する関数
     */
    public function set_top_project($project_id)
    {
        $setting = $this->findById(1);
        $options = array(
                'conditions' => array(
                        'Project.opened' => 'yes', 'Project.stop' => 0, 'Project.id' => $project_id,
                )
        );
        $Project = ClassRegistry::init('Project');
        $project = $Project->find('first', $options);
        if($project){
            $top_projects = json_decode($setting['Setting']['toppage_projects_ids'], true);
            if(!$top_projects || !in_array($project_id, $top_projects)){
                $top_projects[] = $project_id;
                $this->id       = 1;
                if($this->saveField('toppage_projects_ids', json_encode($top_projects))){
                    return true;
                }
            }else{
                return true;
            }
        }
        return false;
    }

    /**
     * 優先表示プロジェクトを解除する関数
     */
    public function unset_top_project($project_id)
    {
        $setting = $this->findById(1);
        $options = array(
                'conditions' => array(
                        'Project.opened' => 'yes', 'Project.stop' => 0, 'Project.id' => $project_id,
                )
        );
        $Project = ClassRegistry::init('Project');
        $project = $Project->find('first', $options);
        if($project){
            $top_projects = json_decode($setting['Setting']['toppage_projects_ids'], true);
            if(!$top_projects){
                return true;
            }
            $key = array_search($project_id, $top_projects);
            if($key !== false){
                unset($top_projects[$key]);
                $this->id = 1;
                if($this->saveField('toppage_projects_ids', json_encode($top_projects))){
                    return true;
                }
            }else{
                return true;
            }
        }
        return false;
    }

    /**
     * 初期データ作成
     */
    public function create_first_data()
    {
        $settings = $this->find('first');
        if (empty($settings)) {
            $setting = array(
                'Setting' => array(
                    'id' => 1,
                    'https_flag' => 1,
                    'user_id' => 1,
                    'gmo_id' => '',
                    'gmo_password' => '',
                    'gmo_site_id' => '',
                    'gmo_site_pass' => '',
                    'twitter_api_key' => '',
                    'twitter_api_secret' => '',
                    'facebook_api_key' => '',
                    'facebook_api_secret' => '',
                    'fee' => 15,
                    'site_open' => 1,
                    'site_url' => 'https://localhost',
                    'site_name' => 'Cloud',
                    'site_title' => 'Cloud - クラウドファンディング',
                    'site_description' => 'クラウドファンディングです',
                    'site_keywords' => 'クラウドファンディング',
                    'about' => '',
                    'company_name' => '株式会社Cloud',
                    'company_type' => 1,
                    'company_url' => 'https://google.com',
                    'company_ceo' => 'Cloud　太郎',
                    'company_address' => '東京都Cloud区',
                    'company_tel' => '03-1234-1234',
                    'copy_right' => '株式会社Cloud',
                    'from_mail_address' => 'admin@cloud.com',
                    'admin_mail_address' => 'admin@cloud.com',
                    'mail_signature' => "----------------\nクラウドファンディング\nhttps://localhost.com",
                    'toppage_projects_ids' => '[]',
                    'toppage_pickup_project_id' => 1,
                    'toppage_new_projects_flag' => 0,
                    'toppage_ok_projects_flag' => 0,
                    'link_color' => '006699',
                    'back1' => 'F2F2F2',
                    'back2' => 'D2D2D2',
                    'border_color' => 'CCCCCC',
                    'top_back' => 'FFFFFF',
                    'top_color' => '000000',
                    'top_alpha' => 80,
                    'footer_back' => '000000',
                    'footer_color' => 'FFFFFF',
                    'graph_back' => '0099CC',
                    'top_box_back' => 'FFFFFF',
                    'top_box_black' => 40,
                    'top_box_color' => 'FFFFFF',
                    'top_box_height' => 400,
                    'top_box_content_num' => 1,
                    'content_type1' => 'text',
                    'content_position1' => '中',
                    'txt_content1' => 'Cloud',
                    'movie_type1' => '',
                    'movie_code1' => '',
                    'content_type2' => 'img',
                    'content_position2' => '中',
                    'txt_content2' => '',
                    'movie_type2' => '',
                    'movie_code2' => '',
                    'cat_type_num' => 1,
                    'cat1_name' => 'カテゴリ',
                    'cat2_name' => 'エリア',
                    'google_analytics' => ''
                )
            );
            $this->create();
            if ($this->save($setting)) {
                return true;
            }
        }
        return false;
    }
}
