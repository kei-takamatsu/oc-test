<?php
App::uses('Controller', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class AppController extends Controller
{
    public $uses = array('User', 'Setting');
    public $heplers = array('Color');
    public $components = array(
        'Session', 'Auth' => array(
            'loginAction' => array(
                'controller' => 'users', 'action' => 'login', 'plugin' => 'LogiAuth'
            ),
            'logoutRedirect' => array(
                'controller' => 'base', 'action' => 'index', 'plugin' => false
            ),
            'ajaxLogin' => 'ajax/ajax_auth_error'
        ),
        'Security',
        'Filebinder.Ring'
    );

    public $smart_phone = false;  //スマホからアクセスしているかのフラグ（trueはスマホアクセス）
    public $setting = null; //設定情報（settingsテーブル）

    public function beforeFilter()
    {
        $this->_chk_https_for_sakura_ssl();
        $this->setting();
        //httpsとhttp接続の切り替え（全ページhttps接続を想定）
        $this->Security->blackHoleCallback = 'forceSSL';
        $this->Security->requireSecure();
        if($this->params['admin']){
            AuthComponent::$sessionKey = 'Auth.Admin';
            $this->Auth->authenticate  = array(
                'Form' => array(
                    'userModel' => 'User',
                    'scope' => array('User.group_id' => ADMIN_ROLE, 'active' => 1),
                    'fields' => array('username' => 'email', 'password' => 'password')
                )
            );
        }else{
            $this->Auth->authenticate = array(
                'Form' => array(
                    'userModel' => 'User',
                    'scope' => array('active' => 1),
                    'fields' => array('username' => 'email', 'password' => 'password')
                )
            );
        }
        $this->get_auth_user();
        $this->check_smart_phone();
    }

    /**
     * 設定情報の読み込み
     */
    private function setting()
    {
        $setting = $this->Setting->find('first');
        if(!empty($setting['Setting'])){
            $this->setting = $setting['Setting'];
            $this->set('setting', $this->setting);
        }else{
            $this->set('setting', null);
            $this->db_init();
        }
    }

    /**
     * ログインユーザの取得
     */
    private function get_auth_user()
    {
        if($this->Auth->user()){
            $this->auth_user = $this->User->findById($this->Auth->user('id'));
        }
        $this->set('auth_user', $this->auth_user);
    }

    /**
     * スマホからのアクセスだった場合の処理（各種変数セットなど）
     */
    public function check_smart_phone()
    {
        $ua = $this->request->header('User-Agent');
        if($this->check_agent($ua)){
            $this->set('smart_phone', true);
            $this->smart_phone = true;
        }else{
            $this->set('smart_phone', false);
        }
    }

    /**
     * スマホからのアクセスかチェックする関数
     * スマホだったらtrue(ipad除く）だったはず
     * @param $ua
     * @return bool
     */
    private function check_agent($ua)
    {
        $this->set(compact('ua'));
        if((((strpos($ua, 'iPhone') !== false) || (strpos($ua, 'iPod') !== false) || (strpos($ua, 'PDA') !== false)
             || (strpos($ua, 'BkackBerry') !== false)
             || (strpos($ua, 'Windows Phone') !== false))
            && strpos($ua, 'iPad') === false)
           || ((strpos($ua, 'Android') !== false) && (strpos($ua, 'Mobile') !== false))
        ){
            return true;
        }
    }

    /**
     * https強制接続
     */
    public function forceSSL()
    {
        if(!empty($this->setting['https_flag']) && $this->setting['https_flag']){
            //$this->redirect('https://'.env('SERVER_NAME').$this->here);
        }
    }

    /**
     * http強制接続
     */
    public function _unforceSSL()
    {
        $this->redirect('http://'.env('SERVER_NAME').$this->here);
    }

    /**
     * さくらレンタルサーバのSIN SSL利用への対応
     */
    public function _chk_https_for_sakura_ssl()
    {
        if(isset($_SERVER['HTTP_X_SAKURA_FORWARDED_FOR'])){
            $_SERVER['HTTPS'] = 'on';
        }
    }

    /**
     * Database初期化
     * - settingsとusersが空の場合に実行する
     * - settingsとusersにそれぞれ初期データを一つ格納する
     */
    private function db_init()
    {
        $settings = $this->Setting->find('first');
        $users = $this->User->find('first');
        if(empty($settings) && empty($users)){
            $this->Setting->begin();
            if($this->User->create_first_data()){
                if($this->Setting->create_first_data()){
                    $this->Setting->commit();
                    $this->redirect('/');
                }
            }
        }
        $this->Setting->rollback();
    }

}
