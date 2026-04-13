<?php
App::uses('Component', 'Controller');

/**
 * Class FbComponent
 * Facebook API操作コンポーネント
 */
class FbComponent extends Component
{
    public $components = array('Session', 'Auth');

    public function initialize(Controller $controller)
    {
        $this->controller = $controller;
    }

    /**
     * Facebook Login URlのセット関数
     */
    public function set_facebook_login_url()
    {
        $fb = $this->get_facebook();
        if($fb){
            $permissions = ['email'];
            $url = $this->_create_callback_url();
            $helper = $fb->getRedirectLoginHelper();
            $loginUrl = $helper->getLoginUrl($url, $permissions);
            $this->controller->set('facebook_login_url', $loginUrl);
            return true;
        }else{
            return false;
        }
    }

    private function _create_callback_url()
    {
        $url = Router::url(array(
            'controller' => 'facebook',
            'action' => 'callback',
            'plugin' => 'LogiAuth'
        ), true);
        if(isset($_SERVER['HTTP_X_SAKURA_FORWARDED_FOR'])){
            $url = str_replace('http://', 'https://', $url );
        }
        return $url;
    }

    /**
     * Facebook Obj作成関数
     */
    public function get_facebook()
    {
        if(empty($this->controller->setting['facebook_api_key'])
           || empty($this->controller->setting['facebook_api_secret'])
        ){
            if(!FB_API_KEY || !FB_API_SECRET) return null;
            $app_id = FB_API_KEY;
            $app_secret = FB_API_SECRET;
        }else{
            $app_id = $this->controller->setting['facebook_api_key'];
            $app_secret = $this->controller->setting['facebook_api_secret'];
        }
        return new Facebook\Facebook(array(
            'app_id' => $app_id,
            'app_secret' => $app_secret,
            'default_graph_version' => 'v2.2',
        ));
    }

}