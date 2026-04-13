<?php
App::uses('AppModel', 'Model');

/**
 * User Model
 */
class User extends AppModel
{
    public $actsAs = array(
            'Filebinder.Bindable' => array(
                    'dbStorage' => false, 'beforeAttach' => 'resize', 'afterAttach' => 'thumbnail'
            )
    );
    public $bindFields = array();
    /**
     * Display field
     * @var string
     */
    public $displayField = 'name';
    /**
     * Validation rules
     * @var array
     */
    public $validate = array(
            'img' => array(
                    'allowExtention' => array(
                            'rule' => array(
                                    'checkExtension', array(
                                            'jpg', 'jpeg', 'png', 'gif'
                                    )
                            ), 'message' => '拡張子が無効です', 'allowEmpty' => true,
                    ), 'fileSize' => array(
                            'rule' => array(
                                    'checkFileSize', '0.5MB'
                            ), 'message' => 'ファイルサイズが0.5MBを超過しています',
                    ), 'illegalCode' => array('rule' => array('checkIllegalCode'),)
            ), 'nick_name' => array(
                    'notblank' => array(
                            'rule' => array('notblank'), 'message' => 'ユーザーネームを入力してください。', 'allowEmpty' => false,
                    ), 'maxLength' => array(
                            'rule' => array(
                                    'maxLength', 15
                            ), 'message' => 'ユーザーネームは15文字以内の半角英数字を入力してください。'
                    ),
            ), 'name' => array(
                    'notblank' => array(
                            'rule' => array('notblank'), 'message' => '名前を入力してください。', 'allowEmpty' => true,
                    ), 'maxLength' => array(
                            'rule' => array(
                                    'maxLength', 20
                            ), 'message' => '名前は20文字以内で入力してください。'
                    ),
            ), 'email' => array(
                    'email' => array(
                            'rule' => array('email'), 'message' => '正しいメールアドレスを入力してください。',
                    ), 'notblank' => array(
                            'rule' => array('notblank'), 'message' => 'メールアドレスを入力してください。',
                    ), 'unique' => array(
                            'rule' => 'uniqueActive', 'required' => 'create', 'message' => 'このメールアドレスは既に登録されています。',
                    ),
            ), 'password' => array(
                    'notblank' => array(
                            'rule' => array('notblank'), 'message' => 'パスワードを入力してください。',
                    ), 'between' => array(
                            'rule' => array(
                                    'between', 5, 15
                            ), 'message' => 'パスワードは5文字以上15文字以内の半角英数字を入力してください。'
                    ), 'confirm' => array(
                            'rule' => array('passConfirm'), 'message' => 'パスワードが一致しません。',
                    )
            ), 'url1' => array(
                    'url' => array(
                            'rule' => array('url'), 'message' => '正しいURLを入力してください。', 'allowEmpty' => true,
                    ),
            ), 'url2' => array(
                    'url' => array(
                            'rule' => array('url'), 'message' => '正しいURLを入力してください。', 'allowEmpty' => true,
                    ),
            ), 'url3' => array(
                    'url' => array(
                            'rule' => array('url'), 'message' => '正しいURLを入力してください。', 'allowEmpty' => true,
                    ),
            ),
    );
    /**
     * hasMany associations
     * @var array
     */
    public $hasMany = array(
            'BackedProject' => array(
                    'className' => 'BackedProject', 'foreignKey' => 'user_id', 'dependent' => false, 'conditions' => '',
                    'fields' => '', 'order' => '', 'limit' => '', 'offset' => '', 'exclusive' => '',
                    'finderQuery' => '', 'counterQuery' => ''
            ), 'Comment' => array(
                    'className' => 'Comment', 'foreignKey' => 'user_id', 'dependent' => false, 'conditions' => '',
                    'fields' => '', 'order' => '', 'limit' => '', 'offset' => '', 'exclusive' => '',
                    'finderQuery' => '', 'counterQuery' => ''
            ), 'FavouriteProject' => array(
                    'className' => 'FavouriteProject', 'foreignKey' => 'user_id', 'dependent' => false,
                    'conditions' => '', 'fields' => '', 'order' => '', 'limit' => '', 'offset' => '', 'exclusive' => '',
                    'finderQuery' => '', 'counterQuery' => ''
            ), 'Project' => array(
                    'className' => 'Project', 'foreignKey' => 'user_id', 'dependent' => false, 'conditions' => '',
                    'fields' => '', 'order' => '', 'limit' => '', 'offset' => '', 'exclusive' => '',
                    'finderQuery' => '', 'counterQuery' => ''
            )
    );

    function __construct()
    {
        parent::__construct();
        $this->bindFields[] = array(
                'field' => 'img', 'tmpPath' => Configure::read('file_path').'tmp/',
                'filePath' => Configure::read('file_path').'upload/',
        );
    }

    /**
     * 画像リサイズ
     * @param array $tmp_file_path
     * @return bool
     */
    public function resize($tmp_file_path)
    {
        return $this->resize_image($tmp_file_path, 400, 400);
    }

    //The Associations below have been created with all possible keys, those that are not needed can be removed
    /**
     * サムネイル作成
     * @param array $file_path
     * @return bool
     */
    public function thumbnail($file_path)
    {
        return $this->create_thumbnail($file_path, array(
                array(
                        48, 48
                )
        ));
    }

    public function passConfirm($data)
    {
        if(!empty($this->data['User']['password2'])){
            return $data['password'] == $this->data['User']['password2'];
        }
        return true;
    }

    public function uniqueActive($data)
    {
        $options = array(
                'conditions' => array(
                        'User.active' => 1, 'User.email' => $data['email']
                ), 'fields' => array('User.id')
        );
        if(!empty($this->id)){
            $options['conditions']['User.id !='] = $this->id;
        }
        if($this->find('first', $options)){
            return false;
        }
        return true;
    }

    public function beforeSave($options = array())
    {
        if(isset($this->data[$this->alias]['password'])){
            $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
        }
        return true;
    }

    /**
     * プロジェクトへの支援者のオプションを取得する関数
     * @param int $project_id
     * @param int $limit
     * @return array $users
     */
    public function get_backers_options($project_id, $limit = 10)
    {
        return array(
                'joins' => array(
                        array(
                                'table' => 'backed_projects', 'alias' => 'BackedProject', 'type' => 'inner',
                                'conditions' => array('User.id = BackedProject.user_id'),
                        ),
                ), 'conditions' => array(
                        'BackedProject.project_id' => $project_id,
                        'BackedProject.status' => Configure::read('STATUSES_FOR_OPEN')
                ), 'order' => array('BackedProject.created' => 'DESC'), 'limit' => $limit, 'fields' => array(
                        'User.id', 'User.nick_name', 'BackedProject.created', 'BackedProject.comment'
                )
        );
    }

    /**
     * プロジェクトへの支援者のオプションを取得する関数
     * （現金手渡しの支援者を省くバージョン）
     */
    public function get_backers_options_except_for_manual($project_id, $limit = 10)
    {
        return array(
                'joins' => array(
                        array(
                                'table' => 'backed_projects', 'alias' => 'BackedProject', 'type' => 'inner',
                                'conditions' => array('User.id = BackedProject.user_id'),
                        ),
                ), 'conditions' => array(
                        'BackedProject.project_id' => $project_id,
                        'BackedProject.status' => Configure::read('STATUSES_FOR_OPEN'), 'BackedProject.manual_flag' => 0
                ), 'order' => array('BackedProject.created' => 'DESC'), 'limit' => $limit, 'fields' => array(
                        'User.id', 'User.nick_name', 'BackedProject.created', 'BackedProject.comment'
                )
        );
    }

    /**
     * メールアドレスからユーザを取得する関数
     * @param string $email
     * @return array $user
     */
    public function get_user_from_email($email)
    {
        return $this->findByEmail($email);
    }

    /**
     * トークンからユーザを取得する関数
     */
    public function get_user_from_token($token)
    {
        return $this->findByToken($token);
    }

    /**
     * Token作成関数
     */
    public function make_token()
    {
        while(true){
            $token = Security::hash(rand(100000, 999999), 'sha1', true);
            $user  = $this->findByToken($token);
            if(empty($user)){
                return $token;
            }
        }
    }

    /**
     * Twitterプロフィール画像の登録関数
     * @param str $twitter_img_url
     * @param int $user_id
     */
    public function get_twitter_profile_img($twitter_img_url, $user_id)
    {
        return $this->save_twitter_profile_img($twitter_img_url, $user_id); //FileBinderのビヘイビアに書いた
    }

    /**
     * TwitterIdからユーザ取得する関数
     */
    public function get_user_by_twitter_id($twitter_id)
    {
        return $this->findByTwitterId($twitter_id);
    }

    /**
     * ログイン試行回数のアップ
     */
    public function up_login_try_count($email)
    {
        $user = $this->findByEmail($email);
        if($user){
            $login_try_count = $user['User']['login_try_count'] + 1;
            $this->id        = $user['User']['id'];
            $this->saveField('login_try_count', $login_try_count);
            return $login_try_count;
        }else{
            return null;
        }
    }

    /**
     * ログイン試行回数のリセット
     */
    public function reset_login_try_count($user_id)
    {
        $user     = array(
                'User' => array(
                        'login_try_count' => 0, 'token' => null
                )
        );
        $this->id = $user_id;
        if($this->save($user, true, array(
                'login_try_count', 'token'
        ))
        ){
            return true;
        }
        return false;
    }

    /**
     * 通常ユーザを全て取得する
     */
    public function get_all_users($option = true, $limit = 30)
    {
        $options = array(
                'conditions' => array('User.group_id' => USER_ROLE, 'User.active' => 1), 'limit' => $limit,
                'order' => array('User.id' => 'DESC')
        );
        if($option){
            return $options;
        }
        return $this->find('all', $options);
    }

    /**
     * ユーザをEmailで検索する
     */
    public function get_users_by_email($email, $option = true, $limit = 30)
    {
        $options = array(
                'conditions' => array(
                        'User.email LIKE' => "%$email%", 'User.group_id' => USER_ROLE,
                        'User.active' => 1
                ), 'limit' => $limit, 'order' => array('User.id' => 'DESC')
        );
        if($option){
            return $options;
        }
        return $this->find('all', $options);
    }

    /**
     * 退会処理
     */
    public function withdraw($user_id)
    {
        $this->id = $user_id;
        $data     = array(
                'active' => 0, 'twitter_id' => '', 'facebook_id' => ''
        );
        if($this->save($data)){
            return true;
        }
        return false;
    }

    /**
     * 初期データ作成
     */
    public function create_first_data()
    {
        $users = $this->find('first');
        if(empty($users)){
            $user = array(
                'User' => array(
                    'id' => 1,
                    'nick_name' => 'admin',
                    'name' => 'admin',
                    'email' => 'admin@cloud.com',
                    'password' => 'password1234',
                    'address' => 'Tokyo',
                    'group_id' => 1
                )
            );
            $this->create();
            if($this->save($user)){
                return true;
            }
        }
        return false;
    }

}
