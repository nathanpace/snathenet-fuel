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

// Pull in Phonecodes model ready for use
use \Model\Phonecodes;

/**
 * The CodeAPI controller
 *
 * Extends the RestAPI controller to provide API access to the phone code database
 *
 * @package  app
 * @extends  Controller_Rest
 */
class Controller_CodeAPI extends Controller_Rest
{
	
	// Holds processed URI data
	private $uriData = null;

	/**
	 * @function before
	 * @description Code that will be called before the main body of the API rest call is executed
	 * 
	 * @access public
	 * @return void
	 */
	public function before()
	{
		// Restrict access to my home IP for the moment
		if($_SERVER['REMOTE_ADDR'] != getenv('HOME_IP')) {
			die();
		}

		// Call parent function
		parent::before();
		
		// Process the URI and assign to class variable
		$this->uriData = $this->processURI();
	}
	
	/**
	 * @function get_code
	 * @description get information about the supplied code (eg 0151, 01772)
	 * 
	 * @return response
	 */
	public function get_code()
    {	
		if ($this->uriData['error'] === true) {
			
			return $this->handle_error();
		}
		else {
			$api = new Phonecodes();
			$searchTerm = $this->uriData['message'];
			$results = $api->getCodeInfo($searchTerm);

			return $this->response([
				'searchTerm' => $searchTerm,
				'resultCount' => count($results),
				'results' => $results,
			]);
		}
    }
 
	/**
	 * @function get_exchange
	 * @description get information about the supplied exchange (eg Preston, Huyton). Also accepts numeric codes.
	 * 
	 * @return response
	 */
    public function get_exchange()
    {	
		if ($this->uriData['error'] === true) {
			
			return $this->handle_error();
		}
		else {
			$api = new Phonecodes();
			$searchTerm = $this->uriData['message'];
			$results =  $api->getExchangeInfo($searchTerm);

			return $this->response([
				'searchTerm' => $searchTerm,
				'resultCount' => count($results),
				'results' => $results,
			]);
		}
	}

	/**
	 * @function get_group
	 * @description get information about the supplied exchange group (eg Tyneside).
	 * 
	 * @return response
	 */
    public function get_group()
    {	
		if ($this->uriData['error'] === true) {
			
			return $this->handle_error();
		}
		else {
			$api = new Phonecodes();
			$searchTerm = $this->uriData['message'];
			$results =  $api->getChargeGroupInfo($searchTerm);

			return $this->response([
				'searchTerm' => $searchTerm,
				'resultCount' => count($results),
				'results' => $results,
			]);
		}
	}

	/**
	 * @function get_historical
	 * @description get information about historical code information eg pre-PhONEday or big number change
	 * 
	 * @return response
	 */
    public function get_historical()
    {
		
		$searchTerm = $this->uriData['message'];

		// Return no results immdiately if any of the following are true:
		// - the search term is 2 digits long and explicitly NOT "01" 
		// - the search term is 3 digits long and the last digit is not 1
		// - the search term is shorter than 3 digits (and not 01) or longer than 6
		if ($this->searchIsValid($searchTerm) === false) {
			return $this->response([
				'searchTerm' => $searchTerm,
				'resultCount' => 0,
				'results' => [],
			]);
		}
		else {
			$api = new Phonecodes();
			$results = $api->getHistoricalCodeInfo($searchTerm);

			return $this->response([
				'searchTerm' => $searchTerm,
				'resultCount' => count($results),
				'results' => $results,
			]);
		}
	}
    
	/**
	 * @function searchIsValid
	 * @description checks if the search term is valid for historical data searches
	 * 
	 * @return boolean
	 */
	private function searchIsValid($search)
	{
		// Allow 01
		if ($search === "01") {
			return true;
		}

		// Allow 0x1 
		if (strlen($search) === 3 && substr($search, -1) == "1") {
			return true;
		}

		// Allow any other code that's longer than 3 digits, up to 6 digits
		// eg 0222, 07048, 097084
	    if (strlen($search) > 3 && strlen($search) <= 6) {
			return true;
		}

		// Deny anything elss
		return false;
	}

	/**
	 * @function handle_error
	 * @description deals with errors
	 * 
	 * @return response
	 */
    private function handle_error($errorData = [])
    {
		// Hard-code the header as JSON here
		header("Content-type: application/json; charset=utf-8");

		// Return any error data if it's been passed as a parameter
		if (empty($errorData)) {

			return $this->response([
				'error' => $this->uriData['message']
			], $this->uriData['code']);
		}

		// Data is in the object, so deal with it. We do need to JSON-encode it first though
		return $this->response(json_encode([
			'error' => $errorData['message']
		]), $errorData['code']);
	}
    
	/**
	 * @function processURI
	 * @description take the URI and work our what to do
	 */
    private function processURI()
    {
		// The correct URI should be something like:
		// codeAPI/{function}/{search}

		// Remove any trailing slashes from the ed of the URI
		$uri = trim(Input::uri(), '/');

		// Explode the parts of the URL into an array for ease of use, get count
		$params = explode("/", $uri);
		$numParams = count($params);

		// URI only has one part, so no function or search has been supplied
		if ($numParams === 1) {
			return $this->handle_error([
				'code' => 404,
				'message' => "No API function supplied",
			]);	
		}
		
		// Remove "codeAPI" from the URI params as it's not needed
		$ignore = array_shift($params);
		
		// Has a search term been passed in?
		$search = !empty($params[1]) ? $params[1] : false;

		// Work out which function to call
		$function = "get_" . $params[0];

		// Error if the function does not exist
		if (method_exists($this, $function) === false) {
			return $this->handle_error([
				'code' => 404,
				'message' => "Unrecognised API function {$params[0]}",
			]);	
		}

		// Error if mo search term has been supplied
		if ($search === false) { 
			return [
				'error' => true,
				'code' => 405,
				'message' => "No search term supplied",
			];
		}
		
		// At this point, we have a function name and search term
		// URL decode the search term and return it
		return [
			'error' => false,
			'code' => 200,
			'message' => urldecode($search),
		];
	}

}
