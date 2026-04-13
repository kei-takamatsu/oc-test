<?php
App::uses('DatabaseSession', 'Model/Datasource/Session');

class ComboSession extends DatabaseSession implements CakeSessionHandlerInterface
{
    public $cacheKey;

    public function __construct()
    {
        $this->cacheKey = Configure::read('Session.handler.cache');
        parent::__construct();
    }

    // セッションからデータ読み込み
    public function read($id)
    {
        $result = Cache::read($id, $this->cacheKey);
        if($result){
            return $result;
        }
        return parent::read($id);
    }

    // セッションへデータ書き込み
    public function write($id, $data)
    {
        $result = Cache::write($id, $data, $this->cacheKey);
        if($result){
            return parent::write($id, $data);
        }
        return false;
    }

    // セッションの破棄
    public function destroy($id)
    {
        Cache::delete($id, $this->cacheKey);
        return parent::destroy($id);
    }

    // 期限切れセッションの削除
    public function gc($expires = null)
    {
        return Cache::gc($this->cacheKey) && parent::gc($expires);
    }
}
