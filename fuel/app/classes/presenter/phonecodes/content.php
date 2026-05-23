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


/**
 * The phonecodes content presenter.
 *
 * @package  app
 * @extends  Presenter
 */
class Presenter_Phonecodes_Content extends Presenter
{
	/**
	 * Prepare the view data, keeping this in here helps clean up
	 * the controller.
	 *
	 * @return void
	 */
	public function view()
	{
		// Dropdown options for search types
		$searchTypes = [
			'code' => 'Code',
			'group' => 'Charge Group',
			'exchange' => 'Exchange',
			'historical' => 'Historical Info',
		];

		// Create fildset and build form elements
		$searchform = Fieldset::forge('phonecodesearch');
		$searchform->add('searchterm', 'Search term:&nbsp;', ['id' => 'searchterm']);
		$searchform->add('searchtype', 'Search term:&nbsp;', [
								'type' => 'select',
								'options' => $searchTypes,
								'id' => 'searchtype']);
		$searchform->add('submitsearch', '', ['type' => 'submit', 'value' => ' Search ', 'id' => 'submitsearch']);

		// Set in view
		$this->searchForm = $searchform;
	}
}
