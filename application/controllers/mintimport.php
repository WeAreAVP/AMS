<?php

/**
 * Mint Import Controller
 * 
 * PHP version 5
 * 
 * @category   AMS
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@geekschicago.com>
 * @license    AMS http://ams.avpreserve.com
 * @version    GIT: <$Id>
 * @link       http://ams.avpreserve.com
 */

/**
 * Mintimport Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@geekschicago.com>
 * @license    AMS http://ams.avpreserve.com
 * @link       http://ams.avpreserve.com
 */
class Mintimport extends CI_Controller
{

	/**
	 * Constructor
	 * 
	 * Load Models
	 * 
	 */
	public $mint_path;

	function __construct()
	{
		parent::__construct();
		$this->load->model('assets_model');
		$this->load->model('cron_model');
		$this->assets_path = 'assets/mint_import/';
	}

	function get_mint_import_zip()
	{
		$zip = new ZipArchive;
		$res = $zip->open($this->assets_path . 'pbcore.zip');
		if ($res === TRUE)
		{
			$zip->extractTo('temp/');
			$zip->close();
			$this->cron_model->scan_mint_directory('temp/', $dir_files);
			echo $count = count($dir_files);
			
		}
		else
		{
			echo 'doh!';
		}
	}

}