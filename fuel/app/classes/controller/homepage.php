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

// Models to use
use \Model\GeocodeAPI; 	// Geolocation data
use \Model\Astro;		// Sunrise/sunset information
use \Model\WeatherAPI; 	// Weather information

/**
 * The Homepage Controller.
 *
 * Controller for the homepage
 *
 * @package  app
 * @extends  Controller
 */
class Controller_Homepage extends Controller_Base
{

	/**
	 * Index page
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_index()
	{
		// Set page attributes in base template
		$this->setPageAttributes([
			'js' => ['homepage.js'],
		]);

		// Forge homepage body content and set in base template
		$this->setContent([
			'body' => Presenter::forge('homepage/content')
		]);

	}
	

	/**
	 * Phpinfo shortcut
	 *
	 * @access  public
	 * @return  phpinfo
	 */
	public function action_info()
	{
		// Get home IP addres from env file
		$homeIP = getenv('HOME_IP');
	
		// Return not found if not localhost or home IP
		if (in_array(Input::ip(), ['127.0.0.1', $homeIP]) === false) {
			return Response::forge(View::forge('homepage/goaway'), 404);
		}

		// Show environment value, phpini values and phpinfo 
		echo (\Fuel::$env . "<br>");
		echo (\Debug::phpini() . "<br>");
		phpinfo();	
	}

}
