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
class Presenter_Homepage_Index extends Presenter
{
	/**
	 * Prepare the view data, keeping this in here helps clean up
	 * the controller.
	 *
	 * @return void
	 */
	public function view()
	{
		$timezone = new \DateTimeZone(getenv('HOME_TZ'));
		$now = new \DateTime();
		$now->setTimeZone($timezone);
		$then = new \DateTime('@266544601');
		$age = $then->diff($now);
		$this->age = $age->y;
	}
}
