<?php
class AddCompanyTelToSettings extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_company_tel_to_settings';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'settings' => array(
					'company_tel' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 15, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'company_address'),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'settings' => array('company_tel'),
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
