<?php


use \Fuel\Core\Request;
use \MyLog as MyLog;

abstract class ApiRequest 
{
	// Base URL of the API - should be overwritten by child class
	protected $baseURL = null;

	// Called API name - determined at construction time
	private $calledApiName = null;

	// Default error message - can be overwritten by child class
	protected $defaultError = "Could not get data from API";

	/**
	 * Constructor
	 * 
	 * Populate object parameters
	 */
	protected function __construct($url)
	{
		$this->baseURL = $url;

		$this->calledApiName = (new \ReflectionClass($this))->getShortName();
	}

	/**
	 * @function appendToBaseURL
	 * @description appends the supplied URL fragment to the base API url
	 * 
	 * @access protected
	 * @param string $url the URL fragment to add
	 * 
	 * @return void
	 */
	protected function appendToBaseURL($url)
	{
		if (is_null($this->baseURL)|| empty($this->baseURL) === false) {
			$this->baseURL .= $url; 
		}
	}

	/**
	 * @function callAPI()
	 * @description Makes call to API
	 * 
	 * @access private
	 * 
	 * @param string $apiUrl the API's URL
	 * @param array $params params to pass to the API
	 * 
	 * @return array response from API
	 * 
	 */
	protected function call($params)
	{
		// Go no further if no API url has been set
		if (is_null($this->baseURL) || $this->baseURL === false) {

			return [
				'error' => true,
				'message' => "Missing {$this->calledApiName} URL",
			];
		}
		
		try {
			// Set up request
			$curl = Request::forge($this->baseURL, 'curl')
					->set_method('get')
					->set_auto_format(true);
			
			// Set up parameters if present
			if (!empty($params)) {
				$curl->set_params($params);
			}
			
			// Execute call
			$temp = $curl->execute();

			// Return body of respose to class
			return $curl->response()->body();
		}
		catch (\HttpNotFoundException $e)
		{
			// Set error message
			// TODO: more generic
			$exceptionMessage = $e->getMessage();

			// Log error message
			MyLog::debug("Error from {$this->calledApiName} call - {$exceptionMessage}");

			// Flag as error from exception, let the presenter/view deal with it all
			return [
				'error' => true,
			];

		}
	}
}