<?php
/*
 * 	GeocodeAPI
 * 
 *  Gets Geocode information about a requested placename or postcode
 * 
 *  Extends the base \ApiRequest class
 */
namespace Model;

use \Utils;

class GeocodeAPI extends \ApiRequest 
{
		
	// Determines if supplied data is a postcode
	private $isPostcode;
	
	// Storage for data returned from API call
	private $details;	
	
	// Default error message
	protected $defaultError = "Could not get Geocode information";

	/**
	 * Constructor
	 *
	 * Sets up model and performs call to API
	 *  
	 * @param string $query place name or postcode to get info about
	 */ 
	public function __construct($query)
	{
		// Call parent constructor, passing in API url
		parent::__construct(getenv("GEOCODE_API_URL"));

		// Check if this is a postcode to be searched
		$this->isPostcode = \Utils::isUKPostcode($query); //$this->checkIfPostcode($query);
		
		// Get data from API
		$this->details = $this->retrieveDataFromApi($query);
	}
		
	/**
	 * @function retrieveDataFromApi
	 * @description Gets data from API, does some extra processing once retrieved
	 * 
	 * @access private
	 * 
	 * @param string $query query string to pass to API call
	 * 
	 * @return boolean
	 * 
	 */
	private function retrieveDataFromApi($query)
	{	
		
		// Param array
		$params = [];
		
		// Processed data
		$data = [];
		
		// Determine API route
		if ($query === 'random') {
			$this->appendToBaseURL('/random/places');
		}
		else {
			$this->appendToBaseURL($this->isPostcode ? '/postcodes' : '/places');
			
			// Add query parameters
			$params = [
				'q' => $this->isPostcode ? str_replace(" ", "", $query) : str_replace(" ", "+", $query),
			];
		} 
		
		// Execute API call and get data
		$fromApi = $this->call($params);
		
		// Go no further if no data was retrieved
		if (empty($fromApi)) {
			return [
				'error' => "No data found for {$query}",
			];
		}
		
		// Return error if present
		if (array_key_exists('error', $fromApi)) {
			return [
				'error' => array_key_exists('message', $fromApi) ? $fromApi['message'] : $this->defaultError,
			];
		}

		// Data was retrieved
		$fromApi = $fromApi['result'];

		// Add an extra level to the data array if required
		if (count(array_filter(array_keys($fromApi), 'is_string')) > 0) {
			$fromApi = [$fromApi];
		} 
		
		// Iterate over data from API
		foreach ($fromApi as $apiKey => $apiData) {
			
			// Get just the fields we want from the retrieved API data
			$newData = array_intersect_key($apiData, 
					array_flip( 
						array('name_1', 'outcode','longitude', 'latitude')
				)
			);
			
			// Round lat and long to 6 dp
			$newData['longitude'] = round($newData['longitude'], 6);
			$newData['latitude'] = round($newData['latitude'], 6);
			
			// If this is a postcode search, use the postcode as the name		
			if ($this->isPostcode) {
				$newData['name_1'] = $query;
			}
			
			// Copy required fields to aray to be returned
			$data[$apiKey] = $newData;

			// Add some extra fields here
			$data[$apiKey]['placeid'] = $this->generatePlaceId($newData['name_1'], $newData['outcode'], $this->isPostcode);
			$data[$apiKey]['latlong'] = "{$newData['latitude']}, {$newData['longitude']}";
			$data[$apiKey]['maplink'] = \Utils::createMapLink([$newData['latitude'], $newData['longitude']]);
			$data[$apiKey]['isPostcode'] = $this->isPostcode;
		}
		
		// Garbage collection
		unset($fromApi);

		
		// Return formatted data
		return $data;
	}
	
	/**
	 * @function generateMapLink()
	 * @description Generates a map link from the supplied latitude and longitude
	 * 
	 * @access private
	 * 
	 * @param float $lat the latitude
	 * @param float $long the longitude
	 * 
	 * @return string a generated map link
	 * 
	 */
	private function generateMapLink($lat, $long)
	{
		$placeholders = ["*LAT*", "*LONG*"];
		$replacements = [$lat, $long];
		
		// Replace placeholders in class map URL constant with supplied params
		$link = str_replace($placeholders, $replacements, self::MAP_URL);

		// Create link text
		$text = "";

		return \Html::anchor($link, $postcode, ['target' => '_blank']);
	}
	
	/**
	 * @function generatePlaceId()
	 * @description Generate a unique ID from the supplied place name and postcode outcode
	 * 
	 * @access private
	 * 
	 * @param string $name the place name
	 * @param string $outcode the postcode outcode
	 * @param string $origQuery the original query (used for postcodes only)
	 * @param boolean $isPostcode whether this is a postcode or not
	 * 
	 * @return string the unque ID
	 * 
	 */
	private function generatePlaceId($name, $outcode, $isPostcode)
	{
		
		// If this is a postcode, return lowercase with spaces replaced by hyphens
		if ($isPostcode === true) {
			return strtolower(preg_replace('/[^\w]/', '-', $name));
		}	
		
		// Remove apostophes from name
		$name = str_replace("'", "", $name);
		
		// Return concatenated place name and outcode, lower-cased and with non-alphanumeric charcters replaced with hyphens
		return strtolower(preg_replace('/[^\w]/', '-', $name)) . "-" . strtolower($outcode);
	}
	
	
	/**
	 * @function getDetails()
	 * @description Get details stored in the object
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
