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

// Pull in Phonecodes model ready for use
use \Model\Phonecodes;

/**
 * Phonecode controller
 * Controller for the /phonecodes page
 *
 * @package  app
 * @extends  Controller
 */
class Controller_Phonecodes extends Controller_Base
{
	/**
	 * Index page action
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_index()
	{
		$this->setPageAttributes([
			'js' => ['https://cdn.datatables.net/2.3.7/js/dataTables.js','codesearch.js'],
			'css' => ['https://cdn.datatables.net/2.3.7/css/dataTables.dataTables.css'],
			'title' => 'snathe.net - STD code search tool',
		]);

		$this->setContent([
			'body' => View::forge('phonecodes/content')
		]);

		//return Response::forge(View::forge('phonecodes/index'));
	}
	
	/**
	 * Search page
	 *
	 * @access  public
	 * @return  mixed Response if page not called from AJAX, else View
	 */
	public function action_search()
	{
		// Redirect to 404 page if not called from AJAX
		if (Input::is_ajax() !== true) {
			return $this->show404();
		}

		// End process if no search term has been passed
		if (empty(Input::post('searchterm'))) {
			return View::forge('phonecodes/nomatch', ['searchType' => "data", 'searchTerm' => null]);
		}

		// Create new Phonecodes instance
		$api = new Phonecodes();

		// Decide what to do based on search type,
		// in all cases pass in the created Phonecodes instance
		switch (Input::post('searchtype')) {
			case "code" :	// Code search
				return self::codesearch($api);
				break;
			
				case "group" : // Charge group search
				return self::groupsearch($api);
				break;

			case "exchange" : // Exchange search
				return self::exchangesearch($api);
				break;
			
			case "historical" : // Historical search
				return self::historicalsearch($api);
				break;

			default: // Unrecognised search, treat as 404
				return $this->show404();
		}
	}
	
	/**
	 * About page
	 *
	 * @access  public
	 * @return  mixed Response if page not called from AJAX, else View
	 */
	public function action_about()
	{ 
		// Redirect to 404 page if not called from AJAX
		if (Input::is_ajax() !== true) {
			return $this->show404();
		}

		return Response::forge(View::forge('phonecodes/about'));
	}
	
	/**
	 * Search for a particular code
	 *
	 * @access  private
	 * @return  View
	 */
	private static function codesearch($api)
	{
		// Pass in the search term from the post data to the function to retrieve the matching data
		$results = $api->getCodeInfo(Input::post('searchterm'));
		
		// Handle no results returned from search
		if (count($results) < 1) {
			return View::forge('phonecodes/nomatch', ['searchType' => "code", 'searchTerm' => Input::post('searchterm')]);
		}
		
		// Results returned, set up data to pass to view
		$pageData = [
				'searchTerm' => Input::post('searchterm'),
				'searchType' => "codes",
				'resultCount' => count($results),
				'results' => $results,
		];

		// Pass data to view and generate output
		return View::forge('phonecodes/searchcode', $pageData);
	}

	/**
	 * Search for a particular charge group
	 *
	 * @access  private
	 * @return  View
	 */
	private static function groupsearch($api)
	{
		// Pass in the search term from the post data to the function to retrieve the matching data
		$results = $api->getChargeGroupInfo(Input::post('searchterm'));
		
		// Handle no results returned from search
		if (count($results) < 1) {
			return View::forge('phonecodes/nomatch', ['searchType' => "group", 'searchTerm' => Input::post('searchterm')]);
		}
		
		// Results returned, set up data to pass to view
		$pageData = [
				'searchTerm' => Input::post('searchterm'),
				'searchType' => "charge groups",
				'resultCount' => count($results),
				'results' => $results,
		];

		// Pass data to view and generate output
		return View::forge('phonecodes/searchcode', $pageData);
	}

	/**
	 * Search for a particular exchange
	 *
	 * @access  private
	 * @return  View
	 */
	private static function exchangesearch($api)
	{
		// Pass in the search term from the post data to the function to retrieve the matching data
		$results = $api->getExchangeInfo(Input::post('searchterm'));

		// Handle no results returned from search
		if (count($results) < 1) {
			return View::forge('phonecodes/nomatch', ['searchType' => "exchange", 'searchTerm' => Input::post('searchterm')]);
		}

		// Results returned, set up data to pass to view
		$pageData = [
				'searchTerm' => Input::post('searchterm'),
				'searchType' => "exchanges",
				'resultCount' => count($results),
				'results' => $results,
		];

		// Pass data to view and generate output
		return View::forge('phonecodes/searchexchange', $pageData);
	}

	/**
	 * Search for historical data
	 *
	 * @access  private
	 * @return  View
	 */
	private static function historicalsearch($api)
	{
		// Determine what search function to use and get data
		// (i.e. if search term is numeric, treat as a code)
		if (is_numeric(Input::post('searchterm'))) {
			$results = $api->getHistoricalCodeInfo(Input::post('searchterm'));
		} else {
			$results = $api->getHistoricalNameInfo(Input::post('searchterm'));
		}
		
		// Handle no results returned from search
		if (count($results['Codes']) < 1) {
			return View::forge('phonecodes/nomatch', ['searchType' => "historical data", 'searchTerm' => Input::post('searchterm')]);
		}

		// Results returned, set up data to pass to view
		$pageData = [
				'searchTerm' => Input::post('searchterm'),
				'searchType' => "historical codes",
				'resultCount' => [
							'codes' => count($results['Codes']),
							'exchanges' => count($results['Exchanges']),
				],
				'results' => $results,
		];

		// Pass data to view and generate output
		return View::forge('phonecodes/searchhistorical', $pageData);
	}
}
