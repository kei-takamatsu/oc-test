<?php
App::uses('Component', 'Controller');
App::import('Vendor', 'Twitter/twitteroauth');

/**
 * Class TwComponent
 * Twitter API操作コンポーネント
 */
class TwComponent extends Component
{
    public $components = array(
            'Session', 'Auth'
    );

    public function initialize(Controller $controller)
    {
        $this->controller = $controller;
    }

    /**
     * Twitter API情報の取得関数
     */
    public function get_api_info()
    {
        $url = h(Router::url('/tw_callback', true));
        if(isset($_SERVER['HTTP_X_SAKURA_FORWARDED_FOR'])){
            $url = str_replace('http://', 'https://', $url );
        }
        if(empty($this->controller->setting['twitter_api_key'])
           || empty($this->controller->setting['twitter_api_secret'])
        ){
            if(!TW_API_KEY || !TW_API_SECRET){
                return $this->controller->redirect('/login');
            }
            $url = h(Router::url('/tw_callback', true));
            if(isset($_SERVER['HTTP_X_SAKURA_FORWARDED_FOR'])){
                $url = str_replace('http://', 'https://', $url );
            }
            $this->log($url);
            return array(
                    TW_API_KEY, TW_API_SECRET, $url
            );
        }
        return array(
            $this->controller->setting['twitter_api_key'],
            $this->controller->setting['twitter_api_secret'],
            $url
        );
    }

    /**
     * twitter access_token取得関数
     * @param string $api_key
     * @param string $api_secret
     * @param string $request_token
     * @return string $access_token
     */
    public function get_access_token($api_key, $api_secret, $request_token)
    {
        $Twitter      = new TwitterOAuth($api_key, $api_secret, $request_token['oauth_token'], $request_token['oauth_token_secret']);
        $access_token = $Twitter->getAccessToken($_REQUEST['oauth_verifier']);
        if(!$access_token){
            $this->Session->setFlash('Twitterに接続できません。');
            $this->redirect('/login');
        }
        return $access_token;
    }

    /**
     * twitter access_tokenからtwitterユーザ情報を取得する関数
     */
    public function get_user_info($api_key, $api_secret, $access_token)
    {
        $Twitter   = new TwitterOAuth($api_key, $api_secret, $access_token['oauth_token'], $access_token['oauth_token_secret']);
        $params    = array('include_entities' => 'false');
        $user_info = $Twitter->get('account/verify_credentials', $params);
        if(empty($user_info) || empty($user_info->id) || empty($user_info->name)){
            $this->Session->setFlash('Twitterに接続できません。');
            $this->controller->redirect('/');
        }
        return $user_info;
    }

}