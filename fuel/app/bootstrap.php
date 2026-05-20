<?php
/**
 * Fuel is a fast, lightweight, community driven PHP 5.4+ framework.
 *
 * @package    Fuel
 * @version    1.9-dev
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2019 Fuel Development Team
 * @link       https://fuelphp.com
 */

// Bootstrap the framework - THIS LINE NEEDS TO BE FIRST!
require COREPATH.'bootstrap.php';

// Add framework overload classes here
\Autoloader::add_classes(array(
	'DotEnv' => APPPATH . 'classes/lib/dotenv.php',
	'MyLog' => APPPATH . 'classes/lib/log.php',
	'ApiRequest' => APPPATH . 'classes/lib/apirequest.php',
));

// Register the autoloader
\Autoloader::register();

// Set up DotEnv package, load in .env file
(new DotEnv(APPPATH . '.env'))->load();

/**
 * Your environment.  Can be set to any of the following:
 *
 * Fuel::DEVELOPMENT
 * Fuel::TEST
 * Fuel::STAGING
 * Fuel::PRODUCTION
 */

// Checl $_SERVER and $_ENV superglobals to see if FUEL_ENV has been set
// Default to Fuel::PRODUCTION if it hasn't been set
Fuel::$env = Arr::get($_SERVER, 'FUEL_ENV', Arr::get($_ENV, 'FUEL_ENV', getenv('FUEL_ENV') ?: Fuel::PRODUCTION));


// Initialize the framework with the config file.
\Fuel::init('config.php');
