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

return array(
	/**
	 * -------------------------------------------------------------------------
	 *  Default route
	 * -------------------------------------------------------------------------
	 *
	 */

	'_root_' => 'homepage/index',

	/**
	 * -------------------------------------------------------------------------
	 *  Page not found
	 * -------------------------------------------------------------------------
	 *
	 */

	'_404_' => 'base/404',

	/**
	 * -------------------------------------------------------------------------
	 *  Example for Presenter
	 * -------------------------------------------------------------------------
	 *
	 *  A route for showing page using Presenter
	 *
	 */

	//'hello(/:name)?' => array('homepage/hello', 'name' => 'hello'),
	
	/**
	 * -------------------------------------------------------------------------
	 *  Other controller routes
	 * -------------------------------------------------------------------------
	 *
	 *  Routes to be handled via controllers
	 *
	 */
	'info' => 'homepage/info',
	
	'phonecodes/about' => 'phonecodes/about',
	'phonecodes' => 'phonecodes/index',
	'codeSearch' => 'phonecodes/search',
	
	'geostuff' => 'geostuff/index',
	'locationSearch' => 'geostuff/search',
);
