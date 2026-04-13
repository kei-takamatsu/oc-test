<?php
require_once ('com/gmo_pg/client/output/BaseOutput.php');
/**
 * <b>カード情報詳細カード詳細情報取得　出力パラメータクラス</b>
 *
 * @package com.gmo_pg.client
 * @subpackage output
 * @see outputPackageInfo.php
 * @author GMO PaymentGateway
 */
class SearchCardDetailOutput extends BaseOutput {

	/**
	 * @var array カード番号（下四桁表示、以上マスク） カード番号を要素にもつ一次元配列
	 */
	private $cardNo;

	/**
	 * @var array  国際ブランド  国際ブランドを要素にもつ一次元配列
	 */
	private $brand;

	/**
	 * @var array  国内発行フラグ  削除フラグを要素にもつ一次元配列
	 */
	private $domesticFlag;

	/**
	 * @var array  イシュアコード  イシュアコードを要素にもつ一次元配列
	 */
	private $issuerCode;

	/**
	 * @var array  デビット／プリペイドフラグ  デビット／プリペイドフラグを要素にもつ一次元配列
	 */
	private $debitPrepaidIssuerName;

	/**
	 * @var array  デビット／プリペイドカード発行会社名 デビット／プリペイドカード発行会社名を要素にもつ一次元配列
	 */
	private $debitPrepaidFlag;

	/**
	 * @var array  最終仕向先 最終仕向先を要素にもつ一次元配列
	 */
	private $forwardFinal;


	/**
	 * @var array カードの配列。カード情報の連想配列が繰り返される、二次元配列。例：
	 *
	 *	<code>
	 *	$cardList =
	 * 		array(
	 *			array(
	 *	 			'CardNo'	=>	'************1111',
	 * 				'Brand'	=>	'VISA'
	 * 				'DomesticFlag'	=>	'1'
	 * 				'IssuerCode'	=>	'2a99660'
	 * 				'DebitPrepaidFlag'	=>	'1'
	 * 				'DebitPrepaidIssuerName'	=>	'IssuerName'
	 * 				'ForwardFinal'	=>	'15250'
	 *			),
	 *			array(
	 * 				'CardNo'	=>	'************2222',
	 * 				'Brand'	=>	'VISA'
	 * 				'DomesticFlag'	=>	'0'
	 * 				'IssuerCode'	=>	''
	 * 				'DebitPrepaidFlag'	=>	''
	 * 				'DebitPrepaidIssuerName'	=>	'IssuerName'
	 * 				'ForwardFinal'	=>	'15250'
	 *			),
	 *  	)
	 * </code>
	 *
	 */
	private $cardList = null;


	/**
	 * コンストラクタ
	 *
	 * @param IgnoreCaseMap $params  出力パラメータ
	 */
	public function __construct($params = null) {
		parent::__construct($params);

		// 引数が無い場合は戻る
		if (is_null($params)) {
			return;
		}

		// マップの展開
		//カードは複数返るので、全てマップに展開
		$cardArray = null;
		$tmp =  $params->get('CardNo');
		$cardNo					=	$params->get('CardNo');
		$brand					=	$params->get('Brand');
		$domesticFlag			=	$params->get('DomesticFlag');
		$issuerCode				=	$params->get('IssuerCode');
		$debitPrepaidFlag		=	$params->get('DebitPrepaidFlag');
		$debitPrepaidIssuerName	=	$params->get('DebitPrepaidIssuerName');
		$forwardFinal			=	$params->get('ForwardFinal');


		if( is_null( $cardNo ) ){
			return;
		}
		//項目ごとに配列として設定
		if( !is_null( $cardNo ) ){
			$this->setCardNo(explode('|',$cardNo ) );
		}
		if( !is_null( $brand ) ){
			$this->setBrand(explode('|',$brand ) );
		}
		if( !is_null( $domesticFlag ) ){
			$this->setDomesticFlag(explode('|',$domesticFlag ) );
		}
		if( !is_null( $issuerCode ) ){
			$this->setIssuerCode(explode('|',$issuerCode ) );
		}
		if( !is_null( $debitPrepaidIssuerName ) ){
			$this->setDebitPrepaidIssuerName(explode('|',$debitPrepaidIssuerName ) );
		}
		if( !is_null( $debitPrepaidFlag ) ){
			$this->setDebitPrepaidFlag(explode('|',$debitPrepaidFlag ) );
		}
		if( !is_null( $forwardFinal ) ){
			$this->setForwardFinal(explode('|',$forwardFinal ));
		}

		//カード配列を作成
		$cardList = null;
		$count = count( $this->cardNo );
		for( $i = 0 ; $i < $count; $i++ ){
			$tmp = null;
			$tmp['CardNo']					= $this->cardNo[$i];
			$tmp['Brand']					= $this->brand[$i];
			$tmp['DomesticFlag']			= $this->domesticFlag[$i];
			$tmp['IssuerCode']				= $this->issuerCode[$i];
			$tmp['DebitPrepaidFlag']		= $this->debitPrepaidFlag[$i];
			$tmp['DebitPrepaidIssuerName']	= $this->debitPrepaidIssuerName[$i];
			$tmp['ForwardFinal']			= $this->forwardFinal[$i];

			$cardList[]	=	$tmp;
		}
		$this->cardList = $cardList;
	}

	/**
	 * カード番号の配列取得
	 * @return array カード番号
	 */
	public function getCardNo() {
		return $this->cardNo;
	}

	/**
	 * 国際ブランド設定
	 * @param array $brand 国際ブランド
	 */
	public function getBrand() {
		return $this->brand;
	}

	/**
	 * 国内発行フラグ
	 * @param array $domesticFlagbrand 国内発行フラグ
	 */
	public function getDomesticFlag() {
		return $this->domesticFlag;
	}

	/**
	 * イシュアコード
	 * @param array $issuerCode イシュアコード
	 */
	public function getIssuerCode() {
		return $this->issuerCode;
	}

	/**
	 * デビット／プリペイドフラグ
	 * @param array $debitPrepaidIssuerName イシュアコード
	 */
	public function getDebitPrepaidIssuerName() {
		return $this->debitPrepaidIssuerName;
	}

	/**
	 * デビット／プリペイドカード発行会社名
	 * @param array $debitPrepaidIssuerName デビット／プリペイドカード発行会社名
	 */
	public function getDebitPrepaidFlag() {
		return $this->debitPrepaidFlag;
	}

	/**
	 * 最終仕向先
	 * @param array $forwardFinal 最終仕向先
	 */
	public function getForwardFinal() {
		return $this->forwardFinal;
	}

	/**
	 * カードリスト取得
	 * <p>
	 * 	　$cardListを返します
	 * </p>
	 * @return array カードリスト
	 */
	public function getCardList() {
		return $this->cardList;
	}

	/**
	 * カード番号設定
	 * @param array $cardNo カード番号
	 */
	public function setCardNo( $cardNo) {
		$this->cardNo = $cardNo;
	}

	/**
	 * 国際ブランド設定
	 * @param array $brand 国際ブランド
	 */
	public function setBrand($brand) {
		$this->brand = $brand;
	}

	/**
	 * 国内発行フラグ
	 * @param array $domesticFlagbrand 国内発行フラグ
	 */
	public function setDomesticFlag($domesticFlagbrand) {
		$this->domesticFlag = $domesticFlagbrand;
	}

	/**
	 * イシュアコード
	 * @param array $issuerCode イシュアコード
	 */
	public function setIssuerCode($issuerCode) {
		$this->issuerCode = $issuerCode;
	}

	/**
	 * デビット／プリペイドフラグ
	 * @param array $debitPrepaidIssuerName イシュアコード
	 */
	public function setDebitPrepaidIssuerName($debitPrepaidIssuerName) {
		$this->debitPrepaidIssuerName = $debitPrepaidIssuerName;
	}

	/**
	 * デビット／プリペイドカード発行会社名
	 * @param array $debitPrepaidIssuerName デビット／プリペイドカード発行会社名
	 */
	public function setDebitPrepaidFlag($debitPrepaidFlag) {
		$this->debitPrepaidFlag = $debitPrepaidFlag;
	}

	/**
	 * 最終仕向先
	 * @param array $forwardFinal 最終仕向先
	 */
	public function setForwardFinal($forwardFinal) {
		$this->forwardFinal = $forwardFinal;
	}

	/**
	 * カードリスト設定
	 * @param array $cardList カードリスト設定
	 */
	public function setCardList($cardList) {
		$this->cardList = $cardList;
	}

	/**
	 * 文字列表現
	 * <p>
	 *  現在の各パラメータを、パラメータ名=値&パラメータ名=値の形式で取得します。
	 * </p>
	 * @return string 出力パラメータの文字列表現
	 */
	public function toString() {
		$str ='';
		$str .= 'CardNo='					. (is_null($this->cardNo)?'':implode('|',$this->cardNo));
		$str .= '&';
		$str .= 'Brand='					. (is_null($this->brand)?'':implode('|',$this->brand));
		$str .= '&';
		$str .= 'DomesticFlag='				. (is_null($this->domesticFlag)?'':implode('|',$this->domesticFlag));
		$str .= '&';
		$str .= 'IssuerCode='				. (is_null($this->issuerCode)?'':implode('|',$this->issuerCode));
		$str .= '&';
		$str .= 'DebitPrepaidFlag='			. (is_null($this->debitPrepaidFlag)?'':implode('|',$this->debitPrepaidFlag));
		$str .= '&';
		$str .= 'DebitPrepaidIssuerName='	. (is_null($this->debitPrepaidIssuerName)?'':implode('|',$this->debitPrepaidIssuerName));
		$str .= '&';
		$str .= 'ForwardFinal='				. (is_null($this->forwardFinal)?'':implode('|',$this->forwardFinal));

		if ($this->isErrorOccurred()) {
			// エラー文字列を連結して返す
			$errString = parent::toString();
			$str .= '&' . $errString;
		}

		return $str;
	}

}
?>
