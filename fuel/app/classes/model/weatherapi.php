<?php
/*
 * 	WeatherAPI
 * 
 *  Gets weather information for a requested longitude and latitude
 * 
 *  Extends the base \ApiRequest class
 */
namespace Model;

class WeatherAPI extends \ApiRequest
{	
	// Storage for data returned from API call
	private $details;

	// Default error message
	protected $defaultError = "Could not get weather information";

	/**
	 * Constructor
	 *
	 * Performs curl call to populates object value
	 *  
	 * @param string $lat latitude
	 * @param string $long longitude
	 */ 
	public function __construct($lat, $long)
	{

		// Construct API request, passing in API URL
		parent::__construct(getenv("WEATHER_API_URL"));

		// Retrieve data for supplied lat and loong
		$this->details = $this->retrieveData($lat, $long);
	}
	
	/**
	 * @function retrieveData
	 * @description Gets data from API, does some extra processing once retrieved
	 * 
	 * @access private
	 * 
	 * @param string $lat latitude of location to get weather data for
	 * @param string $long longitude of location to get weather data for
	 * 
	 * @return array error information if retrieval failed, else weather data from API
	 * 
	 */
	private function retrieveData($lat, $long)
	{	

		// Set up param array to pass to API call
		$params = [
			'lat' => $lat,
			'lon' => $long,
			'units' => 'metric',
			'appid' => getenv("WEATHER_API_KEY"),
		];

		// Execute API call and get data
		$fromApi = $this->call($params);
		
		// Go no further if no data was retrieved
		if (empty($fromApi)) {
			return [
				'error' => "No weather data found",
			];
		}
		
		// Return error if present
		if (array_key_exists('error', $fromApi)) {
			return [
				'error' => array_key_exists('message', $fromApi) ? $fromApi['message'] : $this->defaultError,
			];
		}

		// Data was retrieved
		$data = [
			'description' => ucfirst($fromApi['weather'][0]['description']),
			'icon' => "http://openweathermap.org/img/wn/{$fromApi['weather'][0]['icon']}@2x.png",
			'temp_c' => $this->convertTemp($fromApi['main']['temp']),
			'temp_f' => $this->convertTemp($fromApi['main']['temp'], 'f'),
		];
		
		// Garbage collection
		unset($fromApi);

		// Return formatted data
		return $data;
	}
	
	/**
	 * @function convertTemp
	 * @description rounds temperature value to 1 decimal place, converts to fahrenheit first if required
	 * 
	 * @access private
	 * 
	 * @param float $value raw temperature value in degrees c
	 * @param mixed $unit if set to 'f', convert value to degrees f. Ignore anything else
	 * 
	 * @return float converted and rounded value
	 */
	private function convertTemp($value, $unit = null)
	{
		switch ($unit)
		{
			// Convert value to f if required
			case "f" :
				$value = (($value * 9) / 5) + 32;
				break;
		}
		 
		// Round value to 1 decimal place and return
		return round($value, 1);
	}

	/**
	 * @function getDetails()
	 * @description Get weather details stored in the object
	 * 
	 * @access public
	 * 
	 * @return array details array from object
	 * 
	 */
    public function getDetails()
    {
		return $this->details;
    }
}
