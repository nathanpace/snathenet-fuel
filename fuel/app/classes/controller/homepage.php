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
class Controller_Homepage extends Controller
{

	/**
	 * Homepage
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_index()
	{
		return Response::forge(Presenter::forge('homepage/index'));
	}
	
	/**
	 * Search action
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_search()
	{

		// Redirect to 404 page if not called from AJAX
		if (Input::is_ajax() !== true) {
			return Response::forge(Presenter::forge('404'), 404);
		}
		
		// If location from input is blank, treat as random place
		$location = empty(Input::post('location')) 
						? 'random'
						: Input::post('location');

		// Create new Geocode model, passing in location, and get details of this location
		$geocode = new GeocodeAPI($location);		
		$geocodeDetails = $geocode->getDetails();
		
		// Row counter used for row class styles
		$rowcount = 0;
		
		// Holding array for page data
		$output = [
			'data' => [],
			'page' => [],
		];
		
		// Check if no error retrieved from the geocode data
		if (!array_key_exists('error', $geocodeDetails)) {

			// Iterate over the returned places from geocode data
			foreach ($geocodeDetails as $geocodeDetail) {

				// Create new Astro model, passing in lat/long,
				// get sunrise/sunset and time offset for this location
				$astro = new Astro($geocodeDetail['latitude'], $geocodeDetail['longitude']);
				$astroDetails = $astro->getSunInfo();
				$offsetDetails = $astro->getTimeOffsetInfo();

				// Create new Weather model, passing in lat/long,
				// get weather information for this location
				$weather = new WeatherAPI($geocodeDetail['latitude'], $geocodeDetail['longitude']);
				$weatherDetails = $weather->getDetails();

				// Handle errors in weather API
				if (array_key_exists('error', $weatherDetails)) {
					$weatherText_1 = "Error";
					$weatherText_2 = $weatherDetails['error'];
				}
				else {
					$weatherText_1 = $weatherDetails['description'];
					$weatherText_2 = "{$weatherDetails['temp_c']}&deg;c / {$weatherDetails['temp_f']}&deg;f";
				}

				// Add Weather and Astro data to the return array, keyed on place id.
				$output['data'][$geocodeDetail['placeid']] = [
					'geocode' => $geocodeDetail,
					'astro' => $astroDetails,
					'offset' => $offsetDetails,
					'weather_1' => $weatherText_1,
					'weather_2' => $weatherText_2,
					'rowclass' => (++$rowcount % 2 === 1 ? "odd":"even"),
				];
			}
		}
		else {
			// Handle error
			$output['data'][++$rowcount] = [
				'error' => $geocodeDetails['error'],
				'rowclass' => ($rowcount % 2 === 1 ? "odd":"even"),
			];
				
		}

		// Create home timezone object 
		$timezone = new \DateTimeZone(getenv('HOME_TZ'));
		
		// Create date/time object for today with home timezone
		$today = new \DateTime();
		$today->setTimeZone($timezone);
		
		// Create another date/time object for tomorrow, again with home timezone
		$tomorrow = new \DateTime('+24 hours');
		$tomorrow->setTimeZone($timezone);
		
		// Add time/date information to the page data
		$output['page']['currentTime'] = $today->format("Y-m-d H:i:s");
		$output['page']['todayGmtBst'] = $today->format("I") === "1" ? "BST" : "GMT";
		$output['page']['todayDate'] = $today->format("D M j 'y");
		$output['page']['tomorrowDate'] = $tomorrow->format("D M j 'y");
		$output['page']['tomorrowGmtBst'] = $tomorrow->format("I") === "1" ? "BST" : "GMT";
		
		// Output page
		return View::forge('homepage/search', $output);	
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
			return Response::forge(Presenter::forge('404'), 404);
		}

		// Show environment value, phpini values and phpinfo 
		echo (\Fuel::$env . "<br>");
		echo (\Debug::phpini() . "<br>");
		phpinfo();	
	}

}
