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
	 * Load Models, define global variables
	 * 
	 */
	public $mint_path;
	public $temp_path;

	function __construct()
	{
		parent::__construct();
		$this->load->model('assets_model');
		$this->load->model('cron_model');
		$this->load->model('mint_model', 'mint');
		$this->mint_path = 'assets/mint_import/';
		$this->temp_path = 'temp/';
	}

	function process_mint_dir()
	{
		$files = glob($this->mint_path . '*.zip');
		foreach ($files as $file)
		{
			$zip = new ZipArchive;
			$res = $zip->open($file);
			if ($res === TRUE)
			{
				$zip->extractTo($this->mint_path . 'unzipped/');
				$zip->close();
				$this->load->helper('directory');
				$map = directory_map($this->mint_path . 'unzipped/', 2);
				foreach ($map as $index => $directory)
				{
					foreach ($directory as $file)
					{
						$path = $index . '/' . $file;
						$db_info = $this->mint->get_import_info_by_path($path);
						if ( ! $db_info)
						{
							$mint_info = array('folder' => $index, 'path' => $path, 'is_processed' => 0, 'status_reason' => 'Not processed');
							$this->mint->insert_import_info($mint_info);
						}
						else
							myLog('Already in db.');
					}
				}
			}
			else
			{
				myLog('Something went wrong  while extracting zip file.');
			}
		}
		myLog('All mint files info stored. Folder Path:' . $this->mint_path);
		exit;
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
		$asset_id = $this->import_asset_info($station_id, $xmlArray['children']);
		if ($asset_id)
			$this->import_instantiation_info($asset_id, $xmlArray);
		log('Successfully imported all the information to AMS');
		debug($xmlArray);
	}

	function import_asset_info($station_id, $xmlArray)
	{
		
	}

	function import_instantiation_info($asset_id, $xmlArray)
	{
		
	}

	function delete_temp_files()
	{
		$files = glob($this->temp_path . '*');
		foreach ($files as $file)
		{
			unlink($file);
		}
	}

}