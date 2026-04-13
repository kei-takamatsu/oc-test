<?php
App::uses('Component', 'Controller');
App::import('Vendor', 'Facebook/facebook');

/**
 * Class LoginComponent
 * Login関連の共有関数
 */
class LoginComponent extends Component
{
    public $components = array('Session', 'Auth');

    public function initialize(Controller $controller)
    {
        $this->controller = $controller;
    }

    /**
     * ログイン画面に来る前のページURLをセッションに保存する関数
     */
    public function set_referer()
    {
        $referer = $this->controller->request->referer();
        if(!strpos($referer, 'login')){
            $this->Session->write('login_referer', $referer);
        }
    }

    /**
     * ログイン前のページにリダイレクトさせる関数
     */
    public function redirect_referer()
    {
        $referer = $this->get_referer();
        if($referer){
            return $this->controller->redirect($referer);
        }else{
            return $this->controller->redirect('/mypage');
        }
    }

    /**
     * ログイン画面に来る前のページURLを返す関数
     */
    private function get_referer()
    {
        $referer = $this->Session->read('login_referer');
        $this->Session->delete('login_referer');
        return $referer;
    }

}