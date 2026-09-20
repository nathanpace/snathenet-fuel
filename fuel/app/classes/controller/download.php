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
use \MyLog as MyLog;

/**
 * The Download Controller.
 *
 * Controller for downloads
 *
 * @package  app
 * @extends  Controller
 */
class Controller_Download extends Controller_Base
{
	// Root folder where all downloads are to be stored
	private $downloadRoot = DOCROOT."assets/downloads/";

	// Array of all downloadable files
	private $downloads = [
		'cv' => [
			'serverFile' => "cv-current", // name of file on server, without file type
			'downloadName' => "nathan-pace-cv", // filename downloaded file will be saved as
			'fileType' => "pdf",	// file type
		],
	];

	/**
	 * Download CV
	 *
	 * @access  public
	 * @return  mixed CV file as download
	 */
    public function action_file()
	{	
		// Get item to download from url
		$toDownload = $this->param("file");
		
		// Return 404 page is the file is not in the list of downloadable files
		if (!array_key_exists($toDownload, $this->downloads)) {
			return $this->show404();
		};

		// File is downloadable, present download
		$this->present_download($this->downloads[$toDownload]);
	}

	/**
	 * @function present_download
	 * @description Presents the requested file for download
	 * 
	 * @access private
	 * @param array $download the details of the file to download
	 * 
	 * @throws InvalidPathException
	 */
	private function present_download($download) 
	{	
		try {
			// Determine the filename of the file on the server
			$serverFile = $this->downloadRoot . $download['serverFile'] . "." . $download['fileType'];

			// Construct the filename of the file as to be saved as downloaded.
			// Use the original server filename if a specific download name has not been presented
			$downloadName = (array_key_exists('downloadName', $download) ? $download['downloadName'] : $download['serverFile']) . "." . $download['fileType'];

			// Call the framework download function to present the download
			File::download($serverFile, $downloadName);
		} catch (InvalidPathException $e) {
			MyLog::warning("Unable to find {$serverFile} for downloading. Exception trace: {$e->getMessage()}");
			$this->showError("The file requested for downloading could not be found!", 404);
		}
	}
}