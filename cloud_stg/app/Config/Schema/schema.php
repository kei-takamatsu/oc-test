<?php 
class AppSchema extends CakeSchema {

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {
	}

	public $areas = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 200, 'collate' => 'utf8_unicode_ci', 'comment' => 'エリア（カテゴリ２）名', 'charset' => 'utf8'),
		'order' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 2, 'unsigned' => false, 'comment' => '表示順'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB', 'comment' => 'エリア（カテゴリ２）')
	);

	public $attachments = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'model' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'model_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'field_name' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'file_name' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'file_content_type' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'file_size' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'file_object' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM', 'comment' => '画像アップロード用')
	);

	public $backed_projects = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'project_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'comment' => 'プロジェクトID'),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'comment' => 'ユーザID'),
		'backing_level_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'comment' => '支援パターンID'),
		'invest_amount' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'utf8_unicode_ci', 'comment' => '支援金額', 'charset' => 'utf8'),
		'comment' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '支援コメント', 'charset' => 'utf8'),
		'orderId' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'collate' => 'utf8_general_ci', 'comment' => 'GMO', 'charset' => 'utf8'),
		'accessId' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => 'GMO', 'charset' => 'utf8'),
		'accessPass' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => 'GMO', 'charset' => 'utf8'),
		'status' => array('type' => 'string', 'null' => false, 'default' => '作成完了', 'length' => 30, 'collate' => 'utf8_unicode_ci', 'comment' => '決済ステータス', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'データ作成日時'),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'データ更新日時'),
		'forward' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => 'GMO', 'charset' => 'utf8'),
		'approve' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => 'GMO', 'charset' => 'utf8'),
		'transactionId' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => 'GMO', 'charset' => 'utf8'),
		'tranDate' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'GMO'),
		'checkString' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => 'GMO', 'charset' => 'utf8'),
		'manual_flag' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'comment' => '手動登録フラグ'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB', 'comment' => '決済データ')
	);

	public $backing_levels = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'project_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'comment' => 'プロジェクトID'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_unicode_ci', 'comment' => '支援パターン名', 'charset' => 'utf8'),
		'invest_amount' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 200, 'collate' => 'utf8_unicode_ci', 'comment' => '最低支援額', 'charset' => 'utf8'),
		'return_amount' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => 'リターン内容', 'charset' => 'utf8'),
		'max_count' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 5, 'unsigned' => false, 'comment' => '最大支援数'),
		'now_count' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 5, 'unsigned' => false, 'comment' => '現在の支援数'),
		'delivery' => array('type' => 'integer', 'null' => true, 'default' => '1', 'length' => 1, 'unsigned' => false, 'comment' => 'リターン方法'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'データ作成日時'),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'データ更新日時'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB', 'comment' => '支援パターン（リターン内容）')
	);

	public $categories = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 200, 'collate' => 'utf8_unicode_ci', 'comment' => 'カテゴリ１名', 'charset' => 'utf8'),
		'order' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 2, 'unsigned' => false, 'comment' => '表示順'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB', 'comment' => 'カテゴリ１')
	);

	public $comments = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'comment' => 'ユーザID'),
		'project_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'comment' => 'プロジェクトID'),
		'comment' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => 'コメント', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'データ作成日時'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB', 'comment' => 'コメント')
	);

	public $favourite_projects = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'comment' => 'ユーザID'),
		'project_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'comment' => 'プロジェクトID'),
		'backed' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 20, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'データ生成日時'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB', 'comment' => 'お気に入りプロジェクト')
	);

	public $mail_auths = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'email' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => 'メールアドレス', 'charset' => 'utf8'),
		'token' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => 'トークン', 'charset' => 'utf8'),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => false, 'comment' => 'ユーザID'),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null, 'comment' => 'データ更新日時'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB', 'comment' => 'メールアドレス認証用')
	);

	public $message_pairs = array(
		'message_pair_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 23, 'key' => 'primary', 'collate' => 'utf8_unicode_ci', 'comment' => 'メッセージ送受信者ペアID', 'charset' => 'utf8'),
		'last_sender_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'comment' => '最後の送信者'),
		'last_receiver_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'comment' => '最後の受信者'),
		'read_flag' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'comment' => '既読フラグ'),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null, 'comment' => 'データ更新日時'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'message_pair_id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB', 'comment' => 'メッセージ送受信者ペア')
	);

	public $messages = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'message_pair_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 10, 'collate' => 'utf8_unicode_ci', 'comment' => 'メッセージ送受信者ペアID', 'charset' => 'utf8'),
		'from_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'comment' => '送信者ID'),
		'to_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'comment' => '受信者ID'),
		'content' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => 'メッセージ文', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'データ作成日時'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB', 'comment' => 'メッセージ')
	);

	public $project_content_orders = array(
		'project_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => false, 'key' => 'primary', 'comment' => 'プロジェクトID'),
		'order' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '表示順', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'project_id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB', 'comment' => 'プロジェクト詳細のコンテンツの表示順')
	);

	public $project_contents = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => false, 'key' => 'primary'),
		'project_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => false, 'comment' => 'プロジェクトID'),
		'open' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 4, 'unsigned' => false, 'comment' => '公開ステータス'),
		'type' => array('type' => 'string', 'null' => false, 'default' => 'text', 'length' => 20, 'collate' => 'utf8_unicode_ci', 'comment' => 'コンテンツ種別', 'charset' => 'utf8'),
		'txt_content' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => 'テキストコンテンツ内容', 'charset' => 'utf8'),
		'movie_type' => array('type' => 'string', 'null' => false, 'default' => 'youtube', 'length' => 10, 'collate' => 'utf8_unicode_ci', 'comment' => '動画種別', 'charset' => 'utf8'),
		'movie_code' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'collate' => 'utf8_unicode_ci', 'comment' => '動画コード', 'charset' => 'utf8'),
		'img_caption' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_unicode_ci', 'comment' => '画像キャプション', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB', 'comment' => 'プロジェクト詳細のコンテンツ')
	);

	public $projects = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'project_name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 200, 'collate' => 'utf8_unicode_ci', 'comment' => 'プロジェクト名', 'charset' => 'utf8'),
		'category_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'comment' => 'カテゴリ１ID'),
		'area_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => false, 'comment' => 'エリアID'),
		'description' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => 'プロジェクト概要', 'charset' => 'utf8'),
		'goal_amount' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_unicode_ci', 'comment' => '目標金額', 'charset' => 'utf8'),
		'collection_term' => array('type' => 'integer', 'null' => false, 'default' => '60', 'length' => 2, 'unsigned' => false, 'comment' => '募集期間（日）'),
		'collection_start_date' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => '募集開始日時'),
		'collection_end_date' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => '募集終了日時'),
		'thumbnail_type' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 1, 'unsigned' => false, 'comment' => 'サムネイル種別'),
		'thumbnail_movie_type' => array('type' => 'string', 'null' => true, 'default' => 'youtube', 'length' => 10, 'collate' => 'utf8_unicode_ci', 'comment' => 'サムネイルの動画種別', 'charset' => 'utf8'),
		'thumbnail_movie_code' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'collate' => 'utf8_unicode_ci', 'comment' => 'サムネイルの動画コード', 'charset' => 'utf8'),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'comment' => 'ユーザID'),
		'opened' => array('type' => 'string', 'null' => false, 'default' => 'no', 'length' => 3, 'collate' => 'utf8_unicode_ci', 'comment' => '公開ステータス', 'charset' => 'utf8'),
		'backers' => array('type' => 'integer', 'null' => true, 'default' => '0', 'unsigned' => false, 'comment' => '支援者数'),
		'comment_cnt' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 4, 'unsigned' => false, 'comment' => 'コメント数'),
		'report_cnt' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 3, 'unsigned' => false, 'comment' => '活動報告数'),
		'collected_amount' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 10, 'unsigned' => false, 'comment' => '現在の支援総額'),
		'max_back_level' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 20, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'データ作成日時'),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'データ更新日時'),
		'active' => array('type' => 'string', 'null' => false, 'default' => 'yes', 'length' => 3, 'collate' => 'utf8_unicode_ci', 'comment' => '募集中', 'charset' => 'utf8'),
		'stop' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'comment' => '公開停止ステータス'),
		'return' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => 'リターン概要', 'charset' => 'utf8'),
		'contact' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => 'プロジェクト起案者の連絡先', 'charset' => 'utf8'),
		'rule' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'comment' => '利用規約同意有無'),
		'site_fee' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 2, 'unsigned' => false, 'comment' => 'サイト手数料率'),
		'site_price' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 10, 'unsigned' => false, 'comment' => 'サイト手数料'),
		'project_owner_price' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 10, 'unsigned' => false, 'comment' => 'プロジェクト起案者への支払額'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB', 'comment' => 'プロジェクト')
	);

	public $report_content_orders = array(
		'report_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => false, 'key' => 'primary', 'comment' => '活動報告ID'),
		'order' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '表示順', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'report_id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB', 'comment' => '活動報告詳細コンテンツの表示順')
	);

	public $report_contents = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => false, 'key' => 'primary'),
		'report_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => false, 'comment' => '活動報告ID'),
		'type' => array('type' => 'string', 'null' => false, 'default' => 'text', 'length' => 20, 'collate' => 'utf8_unicode_ci', 'comment' => 'コンテンツ種別', 'charset' => 'utf8'),
		'txt_content' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => 'テキストコンテンツ内容', 'charset' => 'utf8'),
		'movie_type' => array('type' => 'string', 'null' => false, 'default' => 'youtube', 'length' => 10, 'collate' => 'utf8_unicode_ci', 'comment' => '動画種別', 'charset' => 'utf8'),
		'movie_code' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'collate' => 'utf8_unicode_ci', 'comment' => '動画コード', 'charset' => 'utf8'),
		'img_caption' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_unicode_ci', 'comment' => '画像キャプション', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB', 'comment' => '活動報告詳細のコンテンツ')
	);

	public $reports = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'project_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'comment' => 'プロジェクトID'),
		'open' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'comment' => '公開ステータス'),
		'title' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 200, 'collate' => 'utf8_unicode_ci', 'comment' => 'タイトル', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null, 'comment' => 'データ作成日時'),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null, 'comment' => 'データ更新日時'),
		'first_open_flag' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB', 'comment' => '活動報告')
	);

	public $schema_migrations = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'class' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'type' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $settings = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1, 'unsigned' => false, 'key' => 'primary'),
		'https_flag' => array('type' => 'boolean', 'null' => false, 'default' => '1', 'comment' => 'https接続強制有無'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => false, 'comment' => 'ユーザID'),
		'gmo_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_unicode_ci', 'comment' => 'GMO Shop ID', 'charset' => 'utf8'),
		'gmo_password' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => 'GMO Shop Password', 'charset' => 'utf8'),
		'payjp_pub' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => 'PayJp Public Key', 'charset' => 'utf8'),
		'payjp_secret' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => 'PayJp Secret', 'charset' => 'utf8'),
		'twitter_api_key' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => 'Twitter API Key', 'charset' => 'utf8'),
		'twitter_api_secret' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => 'Twitter API Secret', 'charset' => 'utf8'),
		'facebook_api_key' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => 'Facebook API Key', 'charset' => 'utf8'),
		'facebook_api_secret' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => 'Facebook API Secret', 'charset' => 'utf8'),
		'fee' => array('type' => 'integer', 'null' => false, 'default' => '20', 'length' => 2, 'unsigned' => false, 'comment' => 'サイト手数料'),
		'site_open' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'comment' => 'サイト公開ステータス'),
		'site_name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_unicode_ci', 'comment' => 'サイト名', 'charset' => 'utf8'),
		'site_title' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => 'サイトタイトル', 'charset' => 'utf8'),
		'site_description' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => 'サイト紹介文', 'charset' => 'utf8'),
		'site_keywords' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => 'メタキーワード', 'charset' => 'utf8'),
		'about' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => 'Aboutページの表示文章', 'charset' => 'utf8'),
		'company_name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_unicode_ci', 'comment' => '会社名（団体名）', 'charset' => 'utf8'),
		'company_type' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 1, 'unsigned' => false, 'comment' => '法人or個人'),
		'company_url' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '会社URL', 'charset' => 'utf8'),
		'company_ceo' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'collate' => 'utf8_unicode_ci', 'comment' => '会社代表者名', 'charset' => 'utf8'),
		'company_address' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '会社住所', 'charset' => 'utf8'),
		'company_tel' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 15, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'copy_right' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'collate' => 'utf8_unicode_ci', 'comment' => 'コピーライト表示内容', 'charset' => 'utf8'),
		'from_mail_address' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '送信先メールアドレス', 'charset' => 'utf8'),
		'admin_mail_address' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '管理者通知メールアドレス', 'charset' => 'utf8'),
		'mail_signature' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => 'メール署名', 'charset' => 'utf8'),
		'toppage_projects_ids' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 150, 'collate' => 'utf8_unicode_ci', 'comment' => 'オススメプロジェクト', 'charset' => 'utf8'),
		'toppage_pickup_project_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => false, 'comment' => 'ピックアッププロジェクト'),
		'toppage_new_projects_flag' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'comment' => '新着プロジェクト'),
		'toppage_ok_projects_flag' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'comment' => '達成したプロジェクト'),
		'link_color' => array('type' => 'string', 'null' => true, 'default' => '006699', 'length' => 6, 'collate' => 'utf8_unicode_ci', 'comment' => 'リンクの文字色', 'charset' => 'utf8'),
		'back1' => array('type' => 'string', 'null' => false, 'default' => 'f2f2f2', 'length' => 6, 'collate' => 'utf8_unicode_ci', 'comment' => '背景１の色', 'charset' => 'utf8'),
		'back2' => array('type' => 'string', 'null' => false, 'default' => 'd2d2d2', 'length' => 6, 'collate' => 'utf8_unicode_ci', 'comment' => '背景２の色', 'charset' => 'utf8'),
		'border_color' => array('type' => 'string', 'null' => false, 'default' => '999999', 'length' => 6, 'collate' => 'utf8_unicode_ci', 'comment' => '枠線の色', 'charset' => 'utf8'),
		'top_back' => array('type' => 'string', 'null' => true, 'default' => 'ffffff', 'length' => 6, 'collate' => 'utf8_unicode_ci', 'comment' => 'トップの背景色', 'charset' => 'utf8'),
		'top_color' => array('type' => 'string', 'null' => true, 'default' => '000000', 'length' => 6, 'collate' => 'utf8_unicode_ci', 'comment' => 'トップの文字色', 'charset' => 'utf8'),
		'top_alpha' => array('type' => 'integer', 'null' => false, 'default' => '80', 'length' => 2, 'unsigned' => false, 'comment' => 'トップの透明度'),
		'footer_back' => array('type' => 'string', 'null' => true, 'default' => 'd5d5d5', 'length' => 6, 'collate' => 'utf8_unicode_ci', 'comment' => 'フッターの背景色', 'charset' => 'utf8'),
		'footer_color' => array('type' => 'string', 'null' => true, 'default' => '353539', 'length' => 6, 'collate' => 'utf8_unicode_ci', 'comment' => 'フッターの文字色', 'charset' => 'utf8'),
		'graph_back' => array('type' => 'string', 'null' => true, 'default' => '0099cc', 'length' => 6, 'collate' => 'utf8_unicode_ci', 'comment' => '達成率グラフの背景色', 'charset' => 'utf8'),
		'top_box_back' => array('type' => 'string', 'null' => false, 'default' => 'cceeff', 'length' => 6, 'collate' => 'utf8_unicode_ci', 'comment' => 'トップ上段の背景色', 'charset' => 'utf8'),
		'top_box_black' => array('type' => 'integer', 'null' => false, 'default' => '60', 'length' => 2, 'unsigned' => false, 'comment' => 'トップ上段の黒の透明度'),
		'top_box_color' => array('type' => 'string', 'null' => false, 'default' => '000000', 'length' => 6, 'collate' => 'utf8_unicode_ci', 'comment' => 'トップ上段の文字色', 'charset' => 'utf8'),
		'top_box_height' => array('type' => 'integer', 'null' => false, 'default' => '500', 'length' => 3, 'unsigned' => false, 'comment' => 'トップ上段の高さ'),
		'top_box_content_num' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 1, 'unsigned' => false, 'comment' => 'トップ上段のコンテンツ数'),
		'content_type1' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 10, 'collate' => 'utf8_unicode_ci', 'comment' => 'コンテンツ１の種別', 'charset' => 'utf8'),
		'content_position1' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 2, 'collate' => 'utf8_unicode_ci', 'comment' => 'コンテンツ１の位置', 'charset' => 'utf8'),
		'txt_content1' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => 'コンテンツ１のテキスト内容', 'charset' => 'utf8'),
		'movie_type1' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 10, 'collate' => 'utf8_unicode_ci', 'comment' => 'コンテンツ１の動画種別', 'charset' => 'utf8'),
		'movie_code1' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'collate' => 'utf8_unicode_ci', 'comment' => 'コンテンツ１の動画コード', 'charset' => 'utf8'),
		'content_type2' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 10, 'collate' => 'utf8_unicode_ci', 'comment' => 'コンテンツ２の種別', 'charset' => 'utf8'),
		'content_position2' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 2, 'collate' => 'utf8_unicode_ci', 'comment' => 'コンテンツ２の位置', 'charset' => 'utf8'),
		'txt_content2' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => 'コンテンツ２のテキスト内容', 'charset' => 'utf8'),
		'movie_type2' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 10, 'collate' => 'utf8_unicode_ci', 'comment' => 'コンテンツ２の動画種別', 'charset' => 'utf8'),
		'movie_code2' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'collate' => 'utf8_unicode_ci', 'comment' => 'コンテンツ２の動画コード', 'charset' => 'utf8'),
		'cat_type_num' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 1, 'unsigned' => false, 'comment' => '利用するカテゴリ種類数'),
		'cat1_name' => array('type' => 'string', 'null' => false, 'default' => 'カテゴリー', 'length' => 15, 'collate' => 'utf8_unicode_ci', 'comment' => 'カテゴリ１の表示名', 'charset' => 'utf8'),
		'cat2_name' => array('type' => 'string', 'null' => false, 'default' => 'エリア', 'length' => 15, 'collate' => 'utf8_unicode_ci', 'comment' => 'カテゴリ２（エリア）の表示名', 'charset' => 'utf8'),
		'google_analytics' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 500, 'collate' => 'utf8_unicode_ci', 'comment' => 'Google Analytics code', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB', 'comment' => '各種設定データ')
	);

	public $users = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'nick_name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_unicode_ci', 'comment' => 'ニックネーム', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_unicode_ci', 'comment' => '氏名', 'charset' => 'utf8'),
		'email' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => 'メールアドレス', 'charset' => 'utf8'),
		'password' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'utf8_unicode_ci', 'comment' => 'パスワード', 'charset' => 'utf8'),
		'sex' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 10, 'collate' => 'utf8_unicode_ci', 'comment' => '性別', 'charset' => 'utf8'),
		'address' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 30, 'collate' => 'utf8_unicode_ci', 'comment' => 'お住まい', 'charset' => 'utf8'),
		'birthday' => array('type' => 'date', 'null' => true, 'default' => null, 'comment' => '生年月日'),
		'birth_area' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 4, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'school' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'twitter_id' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => 'twitter ID', 'charset' => 'utf8'),
		'facebook_id' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => 'Facebook ID', 'charset' => 'utf8'),
		'self_description' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '自己紹介', 'charset' => 'utf8'),
		'url1' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 200, 'collate' => 'utf8_unicode_ci', 'comment' => 'URL1', 'charset' => 'utf8'),
		'url2' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 200, 'collate' => 'utf8_unicode_ci', 'comment' => 'URL2', 'charset' => 'utf8'),
		'url3' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 200, 'collate' => 'latin1_swedish_ci', 'comment' => 'URL3', 'charset' => 'latin1'),
		'receive_address' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => 'リターン受け取り先住所', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'データ作成日時'),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'データ更新日時'),
		'group_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'comment' => 'ユーザ権限'),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => '1', 'comment' => '退会してないフラグ'),
		'token' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 512, 'collate' => 'utf8_unicode_ci', 'comment' => 'トークン', 'charset' => 'utf8'),
		'login_try_count' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 2, 'unsigned' => false, 'comment' => 'ログイン試行回数'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB', 'comment' => 'ユーザ')
	);

}
