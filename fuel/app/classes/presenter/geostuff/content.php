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
use \Model\GeocodeAPI;
use \Model\Astro;
use \Model\WeatherAPI;

/**
 * The homepage index presenter.
 *
 * @package  app
 * @extends  Presenter
 */
class Presenter_Geostuff_Content extends Presenter
{
	/**
	 * Prepare the view data, keeping this in here helps clean up
	 * the controller.
	 *
	 * @return void
	 */
	public function view()
	{
		// Place name array. Home location will always be first
		$places = [
			getenv('HOME_LOC'), 
			'random', 
			'random', 
		];
		
		// Storage for the data to be passed to the view
		$output = [];
		
		// Row count used for row highlighting
		$count = 0;

		// Iterate over the places array
		foreach ($places as $place) {

			// Create new Geocode model, passing in placename, and get details of this place
			$geocode = new GeocodeAPI($place);
			$geocodeDetails = $geocode->getDetails();

			// Check if no error retrieved from the geocode data
			if (!array_key_exists('error', $geocodeDetails)) {

				// Iterate over the returned places from geocode data
				// will only be one place
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
					
					// Add Weather and Astro data to the output array, keyed on place id.
					$output[$geocodeDetail['placeid']] = [
						'geocode' => $geocodeDetail,
						'astro' => $astroDetails,
						'offset' => $offsetDetails,
						'weather_1' => $weatherText_1,
						'weather_2' => $weatherText_2,
						'rowclass' => ($count % 2 === 1 ? "odd":"even"),
					];
				}
			}
			// Deal with errors from the Geocode API
			else {
				$output[$count] = [
					'error' => $geocodeDetails['error'],
					'rowclass' => ($count % 2 === 1 ? "odd":"even"),
				];
					
			}
			++$count;
		}
		
		// Add the output to the places variable in the view
		$this->places = $output;	
		
		// Create date/time object for today with home timezone
		$timezone = new \DateTimeZone(getenv('HOME_TZ'));
		
		// Create date/time object for today with home timezone
		$today = new \DateTime();
		$today->setTimeZone($timezone);
		
		// Create another date/time object for tomorrow, again with London timezone
		$tomorrow = new \DateTime('+24 hours');
		$tomorrow->setTimeZone($timezone);

		// Set all time information variables in the view with corresponding information.
		$this->todayGmtBst = $today->format("I") === "1" ? "BST" : "GMT";
		$this->currentTime = $today->format("Y-m-d H:i:s");
		$this->todayDate = $today->format("D M j 'y");
		$this->tomorrowDate = $tomorrow->format("D M j 'y");
		$this->tomorrowGmtBst = $tomorrow->format("I") === "1" ? "BST" : "GMT";

		// Build search form and set in view
		$this->searchForm = $this->buildSearchForm();
	}

	/**
	 * @function buildSearchForm
	 * @description Builds the search form using \Fieldset and passes object to form
	 * 
	 * @access private
	 * 
	 * @return Fieldset
	 */
	private function buildSearchForm()
	{
		// Text for the label
		$labelText = "Location search (either UK postcode GB place name; leave blank for random location):&nbsp;";
		
		// Create form
		$searchform = Fieldset::forge('locationsearch');
		$searchform->add('location', $labelText, ['id' => 'location']);
		$searchform->add('submitsearch', '', ['type' => 'submit', 'value' => ' Search ']);
		
		return $searchform;
	}
}
