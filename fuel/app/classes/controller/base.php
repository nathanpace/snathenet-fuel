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
 * The Base Controller.
 *
 * Controller for base stuff such as 404
 *
 * @package  app
 * @extends  Controller
 */
class Controller_Base extends Controller_Template
{
	// Defalt page title
	private $defaultTitle = "snathe.net - PHP developer in NW England for hire";
	
	// Default CSS files to be loaded
	private $defaultCSS = ['bootstrap/bootstrap.css', 'snathe.css'];

	// Default JS files to be loaded
	private $defaultJS = ['https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js'];

	/**
	 * The 404 action for the application.
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_404()
	{
		return Response::forge(Presenter::forge('base/404'), 404);
	}

	/**
	 * Set page attributes in the base template
	 * 
	 * @access protected
	 * @param array $attrs the page attributes to set
	 * 
	 * @return void
	 */
	protected function setPageAttributes($attrs) 
	{
		// Build up the data array to be passed to the view
		$data = [
			// Use class defaults if no value has been passed in the attribute array
			'title' => $attrs['title'] ?? $this->defaultTitle,
			
			'assets' => [
				// If css or js attributes have been passed in $attrs, merge them with the defaults
				'css' => array_key_exists('css', $attrs) ? array_merge($this->defaultCSS, $attrs['css']) : $this->defaultCSS,
				'js' => array_key_exists('js', $attrs) ? array_merge($this->defaultJS, $attrs['js']) : $this->defaultJS,
			]
		];

		// Forge view, add data
		$this->template->pageHead = View::forge('base/pagehead', $data);
	}

	/**
	 * Set page content
	 * 
	 * @access protected
	 * @param array $content the page content to set
	 * 
	 * @return void
	 */
	protected function setContent($content)
	{
		// Set the content accordingly. 
		// If data is not present in the $content array, default to null
		$this->setHeader($content['header'] ?? null);
		$this->setBody($content['body'] ?? null);
		$this->setFooter($content['footer'] ?? null);
	}

	/**
	 * @function show404
	 * @description show 404 content
	 * 
	 * @return Response
	 */
	protected function show404()
	{
		return Response::forge(Presenter::forge('base/404'), 404);
	}


	/**
	 * Set the header
	 * 
	 * @access private
	 * @param array $data the data to set in the header. Defaults to null if not supplied
	 * 
	 * @return void
	 */
	private function setHeader($data = null)
	{
		// Forge view, add data
		$this->template->header = View::forge('base/header', $data);
	}

	/**
	 * Set the body
	 * 
	 * @access private
	 * @param array $data the data to set in the header. Defaults to null if not supplied
	 * 
	 * @return void
	 */
	private function setBody($data = null)
	{
		// Add data
		$this->template->body = $data;
	}

	/**
	 * Set the footer
	 * 
	 * @access private
	 * @param array $data the data to set in the header. Defaults to null if not supplied
	 * 
	 * @return void
	 */
	private function setFooter($data = null)
	{
		// Forge view, add data
		$this->template->footer = View::forge('base/footer', $data);
	}

}
