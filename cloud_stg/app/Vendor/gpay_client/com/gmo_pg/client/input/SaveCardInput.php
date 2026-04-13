<?php
require_once ('com/gmo_pg/client/input/BaseInput.php');

/**
 * <b>カード登録　入力パラメータクラス</b>
 *
 * @package com.gmo_pg.client
 * @subpackage input
 * @see inputPackageInfo.php
 * @author GMO PaymentGateway
 * @version 1.0
 * @created 01-01-2008 00:00:00
 */
class SaveCardInput extends BaseInput {

	/**
	 * @var string サイトID GMOPG発行のサイト識別ID
	 */
	private $siteId;

	/**
	 * @var string サイトパスワード
	 */
	private $sitePass;

	/**
	 * @var string 会員ID
	 */
	private $memberId;

	/**
	 * @var string カード登録連番指定モード
	 */
	private $seqMode;

	/**
	 * @var integer カード登録連番
	 */
	private $cardSeq;

	/**
	 * @var string 継続課金対象フラグ
	 */
	private $defaultFlag;

	/**
	 * @var string カード会社略称
	 */
	private $cardName;

	/**
	 * @var string カード番号
	 */
	private $cardNo;

	/**
	 * @var string token
	 */
	private $token;

	/**
	 * @var string カードパスワード
	 */
	private $cardPass;

	/**
	 * @var string カード有効期限
	 */
	private $expire;

	/**
	 * @var string カード名義人
	 */
	private $holderName;

	/**
	 * コンストラクタ
	 *
	 * @param array $params 入力パラメータ
	 */
	public function __construct($params = null) {
		parent::__construct($params);
	}



	/**
	 * サイトID取得
	 * @return string サイトID
	 */
	public function getSiteId() {
		return $this->siteId;
	}

	/**
	 * サイトパスワード取得
	 * @return string サイトパスワード
	 */
	public function getSitePass() {
		return $this->sitePass;
	}

	/**
	 * 会員ID取得
	 * @return string 会員ID
	 */
	public function getMemberId() {
		return $this->memberId;
	}

	/**
	 * カード連番指定モード取得
	 * @return string カード連番指定モード
	 */
	public function getSeqMode(){
		return $this->seqMode;
	}

	/**
	 * 登録カード連番取得
	 * @return integer 登録カード連番
	 */
	public function getCardSeq(){
		return $this->cardSeq;
	}

	/**
	 * 洗替・継続課金対象フラグ取得
	 * @return string 洗替・継続課金対象フラグ
	 */
	public function getDefaultFlag(){
		return $this->defaultFlag;
	}

	/**
	 * カード会社略称取得
	 * @return string カード会社略称
	 */
	public function getCardName(){
		return $this->cardName;
	}

	/**
	 * カードパスワード取得
	 * @return string カードパスワード
	 */
	public function getCardPass(){
		return $this->cardPass;
	}

	/**
	 * 有効期限取得
	 * @return string 有効期限(YYMM)
	 */
	public function getExpire() {
		return $this->expire;
	}

	/**
	 * カード名義人名取得
	 * @return string カード名義人
	 */
	public function getHolderName() {
		return $this->holderName;
	}

	/**
	 * カード番号取得
	 * @return string カード番号
	 */
	public function getCardNo() {
		return $this->cardNo;
	}

	/**
	 * トークン取得
	 * @return string トークン
	 */
	public function getToken() {
		return $this->token;
	}

	/**
	 * サイトID設定
	 *
	 * @param string $siteId サイトID
	 */
	public function setSiteId($siteId) {
		$this->siteId = $siteId;
	}

	/**
	 * サイトパスワード設定
	 *
	 * @param string $sitePass サイトパスワード
	 */
	public function setSitePass($sitePass) {
		$this->sitePass = $sitePass;
	}

	/**
	 * 会員ID設定
	 *
	 * @param string $memberId 会員ID
	 */
	public function setMemberId($memberId) {
		$this->memberId = $memberId;
	}

	/**
	 * カード連番指定モード設定
	 * @param string $seqMode カード連番指定モード
	 */
	public function setSeqMode($seqMode){
		$this->seqMode = $seqMode;
	}

	/**
	 * 登録カード連番設定
	 * @param integer $cardSeq 登録カード連番
	 */
	public function setCardSeq($cardSeq){
		$this->cardSeq = $cardSeq;
	}

	/**
	 * 洗替・継続課金対象フラグ設定
	 *
	 * @param string $defaultFlag 洗替・継続課金対象フラグ
	 */
	public function setDefaultFlag($defaultFlag) {
		$this->defaultFlag = $defaultFlag;
	}

	/**
	 * カード会社略称設定
	 *
	 * @param string $cardName カード会社略称
	 */
	public function setCardName($cardName) {
		$this->cardName = $cardName;
	}

	/**
	 * カード番号設定
	 *
	 * @param string $cardNo カード番号
	 */
	public function setCardNo($cardNo) {
		$this->cardNo = $cardNo;
	}

	/**
	 * トークン設定
	 *
	 * @param string $token トークン
	 */
	public function setToken($token) {
		$this->token = $token;
	}

	/**
	 * カードパスワード設定
	 *
	 * @param string $cardPass カードパスワード
	 */
	public function setCardPass($cardPass) {
		$this->cardPass = $cardPass;
	}

	/**
	 * 有効期限設定
	 *
	 * @param string $expire 有効期限(YYMM)
	 */
	public function setExpire($expire) {
		$this->expire = $expire;
	}

	/**
	 * カード名義人設定
	 *
	 * @param string $holderName カード名義人
	 */
	public function setHolderName($holderName) {
		$this->holderName = $holderName;
	}


	/**
	 * デフォルト値を設定する
	 */
	public function setDefaultValues() {
	}

	/**
	 * 入力パラメータ群の値を設定する
	 *
	 * @param IgnoreCaseMap params 入力パラメータ
	 */
	public function setInputValues($params) {
		// 入力パラメータがnullの場合は設定処理を行わない
	    if (is_null($params)) {
	        return;
	    }

	    // 各項目の設定(Amount,Taxは値が数値でないものは無効とする)
	    $this->setSiteId($this->getStringValue($params, 'SiteID', $this->getSiteId()));
	    $this->setSitePass($this->getStringValue($params, 'SitePass', $this->getSitePass()));
        $this->setMemberId($this->getStringValue($params, 'MemberID', $this->getMemberId()));
        $this->setSeqMode($this->getStringValue($params, 'SeqMode' , $this->getSeqMode()));
        $this->setCardSeq($this->getIntegerValue($params, 'CardSeq' , $this->getCardSeq()));
        $this->setDefaultFlag( $this->getStringValue($params,'DefaultFlag' ,$this->getDefaultFlag()));
        $this->setCardName($this->getStringValue($params , 'CardName' , $this->getCardName()));
        $this->setCardNo($this->getStringValue($params , 'CardNo' , $this->getCardNo() ));
        $this->setToken($this->getStringValue($params , 'Token' , $this->getToken() ));
        $this->setCardPass($this->getStringValue($params , 'CardPass' , $this->getCardPass()));
        $this->setExpire($this->getStringValue($params , 'Expire' , $this->getExpire() ));
        $this->setHolderName($this->getStringValue($params , 'HolderName' , $this->getHolderName()));

	}

	/**
	 * 文字列表現
	 * URLのパラメータ文字列の形式の文字列を生成する
	 * @return string 接続文字列表現
	 */
	public function toString() {

	    $str  = 'SiteID=' . $this->encodeStr($this->getSiteId());
	    $str .= '&';
	    $str .= 'SitePass=' . $this->encodeStr($this->getSitePass());
	    $str .= '&';
	    $str .= 'MemberID=' . $this->encodeStr($this->getMemberId());
	    $str .= '&';
	    $str .= 'SeqMode=' . $this->encodeStr($this->getSeqMode());
	    $str .= '&';
	    $str .= 'CardSeq=' . $this->encodeStr($this->getCardSeq());
	    $str .= '&';
	    $str .= 'DefaultFlag=' . $this->encodeStr($this->getDefaultFlag());
	    $str .= '&';
	    $str .= 'CardName=' . $this->encodeStr($this->getCardName());
	    $str .= '&';
	    $str .= 'CardNo=' . $this->encodeStr($this->getCardNo());
	    $str .= '&';
	    $str .= 'Token=' . $this->encodeStr($this->getToken());
	    $str .= '&';
	    $str .= 'CardPass=' . $this->encodeStr($this->getCardPass());
	    $str .= '&';
	    $str .= 'Expire=' . $this->encodeStr($this->getExpire());
	    $str .= '&';
	    $str .= 'HolderName=' . $this->encodeStr($this->getHolderName());

	    return $str;
	}

}
?>