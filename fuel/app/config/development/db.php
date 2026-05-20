<?php
/**
 * Fuel is a fast, lightweight, community driven PHP 5.4+ framework.
 *
 * @package    Fuel
 * @version    1.8.2
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2019 Fuel Development Team
 * @link       https://fuelphp.com
 */

/**
 * -----------------------------------------------------------------------------
 *  Database settings for development environment
 * -----------------------------------------------------------------------------
 *
 *  These settings get merged with the global settings.
 *
 */

return array(
	'default' => array(
		'connection' => array(
			'dsn'      => 'mysql:host=localhost;dbname=fuel_dev',
			'username' => 'root',
			'password' => 'root',
		),
	),
	'phonecodes' => array(
		'type'           => 'mysqli',
		'connection'     => array(
			'hostname'       => getenv('PHONECODE_DB_HOST'),
			'port'           => getenv('PHONECODE_DB_PORT'),
			'database'       => getenv('PHONECODE_DB_NAME'),
			'username'       => getenv('PHONECODE_DB_USER'),
			'password'       => getenv('PHONECODE_DB_PSWD'),
			'persistent'     => false,
			'compress'       => false,
		),
	),
);
