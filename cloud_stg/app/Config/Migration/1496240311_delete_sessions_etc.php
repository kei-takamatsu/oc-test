<?php
class DeleteSessionsEtc extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'delete_sessions_etc';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'drop_field' => array(
				'backed_projects' => array('payjp_id', 'payjp_created', 'axes_token', 'axes_try_num'),
				'projects' => array('setting_id'),
			),
			'drop_table' => array(
				'sessions'
			),
		),
		'down' => array(
			'create_field' => array(
				'backed_projects' => array(
					'payjp_id' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'PayJP', 'charset' => 'utf8'),
					'payjp_created' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 100, 'unsigned' => false, 'comment' => 'PayJP'),
					'axes_token' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 25, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'axes_try_num' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1, 'unsigned' => false),
				),
				'projects' => array(
					'setting_id' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 1, 'unsigned' => false),
				),
			),
			'create_table' => array(
				'sessions' => array(
					'id' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'primary', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
					'data' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
					'expires' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM', 'comment' => 'セッション管理用'),
				),
			),
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function after($direction) {
		return true;
	}
}
