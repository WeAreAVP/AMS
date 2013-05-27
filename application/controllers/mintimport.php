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
		$this->mint_path = 'assets/mint_import/';
	}

	function get_mint_import_zip()
	{
		$zip = new ZipArchive;
		$res = $zip->open($this->mint_path . 'pbcore.zip');
		if ($res === TRUE)
		{
			$zip->extractTo('temp/');
			$zip->close();
			$this->load->helper('directory');
			$map = directory_map('temp/', 2);
			foreach ($map as $index => $directory)
			{
				foreach ($directory as $file)
				{
					$this->parse_xml_file($index, $file);
				}
			}
		}
		else
		{
			log('Something went wrong  while extracting zip file.');
		}
	}

	function parse_xml_file($index, $file)
	{
		$file_content = file_get_contents('temp/' . $index . '/' . $file);
		$xml_string = @simplexml_load_string($file_content);
		unset($file_content);
		$xmlArray = xmlObjToArr($xml_string);
		debug($xmlArray);
	}

}