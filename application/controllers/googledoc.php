<?php

/**
 * AMS Archive Management System
 * 
 * PHP version 5
 * 
 * @category   AMS
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @copyright  Copyright (c) WGBH (http://www.wgbh.org/). All Rights Reserved.
 * @license    http://www.gnu.org/licenses/gpl.txt GPLv3
 * @version    GIT: <$Id>
 * @link       https://github.com/avpreserve/AMS
 */

/**
 * Googledoc Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @copyright  Copyright (c) WGBH (http://www.wgbh.org/). All Rights Reserved.
 * @license    http://www.gnu.org/licenses/gpl.txt GPLv3
 * @link       https://ams.americanarchive.org
 */
class Googledoc extends CI_Controller
{

	/**
	 * Constructor.
	 * 
	 * Load the Models,Library
	 * 
	 * @return 
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->model('instantiations_model', 'instantiation');
		$this->load->model('station_model', 'station');
		$this->load->model('cron_model');
		$this->load->model('pbcore_model');
	}

	/**
	 * List down all the missing records that are failed to import from google spreadsheet.
	 * 
	 * @return void.
	 */
	function failed_import()
	{
		$this->layout = 'main_layout.php';
		if ( ! $this->dx_auth->is_logged_in())
			redirect('login');

		$data['failed_import'] = $this->pbcore_model->get_failed_import();
		$this->load->view('googledoc/failed_import', $data);
	}

	/**
	 * Fetch Spreadsheet information from google drive and save into database.
	 * 
	 * @return void
	 */
	function fetch_gsheets()
	{
		$this->load->library('google_spreadsheet', array('user' => $this->config->item('google_spreadsheet_email'), 'pass' => $this->config->item('google_spreadsheet_password')));
		myLog('Getting Spreadsheet Info');
		$spreed_sheets = $this->google_spreadsheet->getAllSpreedSheetsDetails('');
		myLog('Total Spreadsheet Count ' . count($spreed_sheets));
		if ($spreed_sheets)
		{
			foreach ($spreed_sheets as $index => $spreed_sheet)
			{
				$explode_name = explode('_', $spreed_sheet['name']);
				if (isset($explode_name[0]))
				{
					$station_info = $this->station->get_station_by_cpb_id($explode_name[0]);
					if ($station_info)
					{
						$is_exist = $this->pbcore_model->get_one_by($this->pbcore_model->google_spreadsheets, array('spreadsheet_id' => $spreed_sheet['spreedSheetId'], 'spreadsheet_name' => $spreed_sheet['name']));
						if ( ! $is_exist)
						{
							$googlesheet = array();
							$googlesheet['spreadsheet_name'] = $spreed_sheet['name'];
							$googlesheet['spreadsheet_id'] = $spreed_sheet['spreedSheetId'];
							$googlesheet['spreadsheet_url'] = $spreed_sheet['URL'];
							$googlesheet['created_at'] = date('Y-m-d H:m:i');
							$googlesheet['updated_at'] = date('Y-m-d H:m:i');
							$this->pbcore_model->insert_record($this->pbcore_model->google_spreadsheets, $googlesheet);
							myLog('Successfully added in dataase Spreadsheet Name => ' . $spreed_sheet['name']);
						}
						else
						{
							myLog('Already Exist in database Spreadsheet Name => ' . $spreed_sheet['name']);
						}
					}
				}
			}
		}
	}

	/**
	 * Map Correct Spreadsheets and their worksheets.
	 * 
	 * @return none
	 */
	function import_gsheets()
	{
		set_time_limit(0);
		@ini_set("memory_limit", "4000M");
		@ini_set("max_execution_time", 999999999999);
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		$this->load->library('google_spreadsheet', array('user' => $this->config->item('google_spreadsheet_email'), 'pass' => $this->config->item('google_spreadsheet_password')));

		$spreadsheet_info = $this->pbcore_model->get_spreadsheets();
		myLog('Spreadsheet Name: ' . $spreadsheet_info->spreadsheet_name);
		$work_sheets = $this->google_spreadsheet->getAllWorksSheetsDetails($spreadsheet_info->spreadsheet_id);
		$this->pbcore_model->update_spreadsheet($spreadsheet_info->id, array('updated_at' => date('Y-m-d H:m:i')));
		foreach ($work_sheets as $work_sheet)
		{
			if ($work_sheet['name'] === 'Template')
			{
				myLog('Worksheet Name: ' . $work_sheet['name']);
				$data = $this->google_spreadsheet->displayWorksheetData($work_sheet['spreedSheetId'], $work_sheet['workSheetId']);

				myLog('Start importing Spreadsheet ' . $work_sheet['spreedSheetId']);
				$this->_store_event_data($data, $spreadsheet_info);
			}
		}
		$this->pbcore_model->update_spreadsheet($spreadsheet_info->id, array('updated_at' => date('Y-m-d H:m:i')));
		unset($work_sheets);
	}

	/**
	 * Map Data of Work sheet in to database
	 * 
	 * @param array $data contain event data of spreed sheet
	 * 
	 * @return helper
	 */
	private function _store_event_data($data, $spreadsheet_info)
	{
		if (isset($data) && ! empty($data))
		{

			myLog('Total Rows: ' . count($data));
			myLog('Start storing Event info from Spreadsheet');
			foreach ($data as $event_row)
			{
				if (isset($event_row[2]) && ! empty($event_row[2]) && isset($event_row[5]) && ! empty($event_row[5]))
				{
					$guid = $event_row[2];
					$explode = explode('-', $guid, 3);
					$db_guid = 'cpb-aacip/' . trim($explode[2]);
					myLog('GUID => ' . $db_guid);
					myLog('Physical Format  => ' . $event_row[5]);
					$instantiation = $this->instantiation->get_instantiation_by_guid_physical_format($db_guid, $event_row[5]);
					if ($instantiation)
					{

						$instantiation_data = array();
						if (isset($event_row[32]) && ! empty($event_row[32]))
						{
							$instantiation_data['channel_configuration'] = $event_row[32];
						}

						if (isset($event_row[33]) && ! empty($event_row[33]) && strtolower($event_row[33]) !== 'no')
						{
							$instantiation_data['alternative_modes'] = $event_row[33];
						}
						if (isset($event_row[42]) && ! empty($event_row[42]))
						{
							if (isset($instantiation->generation) && ! empty($instantiation->generation))
							{
								if ($instantiation->generation === 'Preservation Master' OR $instantiation->generation === 'Mezzanine' OR $instantiation->generation === 'Proxy')
								{
									$instantiation_data['location'] = $event_row[42];
								}
							}
						}
						myLog('Instantiation Table Changes According to american_archive spreadsheet template v1 Description <br/>Instantiation Id :' . $instantiation->id);
//						print_r($instantiation_data);
						$this->instantiation->update_instantiations($instantiation->id, $instantiation_data);
						myLog('Events Table changes');
						$this->_store_event_type_inspection($event_row, $instantiation->id);
						$this->_store_event_type_baked($event_row, $instantiation->id);
						$this->_store_event_type_cleaned($event_row, $instantiation->id);
						$this->_store_event_type_migration($event_row, $instantiation->id);
						$this->update_event_info($instantiation->id);
					}
					else
					{
						$message = "No Instantiations Found"
						. "Spreadsheet Name: {$spreadsheet_info->spreadsheet_name}<br/>"
						. "GUID: {$db_guid}<br/>"
						. "Physical Format: {$event_row[5]}";
						$already_in_db = $this->pbcore_model->get_one_by('missing_info_gsheets', array('guid' => $db_guid, 'spreadsheet_name' => $spreadsheet_info->spreadsheet_name));
						if ( ! $already_in_db)
						{
							$this->pbcore_model->insert_record('missing_info_gsheets', array('guid' => $db_guid, 'spreadsheet_name' => $spreadsheet_info->spreadsheet_name, 'format' => $event_row[5]));
						}
//						send_email('nouman@avpreserve.com', $this->config->item('from_email'), 'AMS Google Spreadsheet', $message);
						myLog('No instantiation found against ' . $db_guid);
					}
				}
				else
				{
					myLog('Event rows are empty');
				}
			}
		}
	}

	function update_event_info($instantiation_id)
	{

		$this->load->library('sphnixrt');
		$this->load->model('searchd_model');
		$this->load->helper('sphnixdata');
		$instantiation_list = $this->searchd_model->get_ins_index(array($instantiation_id));
		$new_list_info = make_instantiation_sphnix_array($instantiation_list[0], FALSE);
		$this->sphnixrt->update('instantiations_list', $new_list_info);
		$asset_list = $this->searchd_model->get_asset_index(array($instantiation_list[0]->assets_id));
		$new_asset_info = make_assets_sphnix_array($asset_list[0], FALSE);
		$this->sphnixrt->update('assets_list', $new_asset_info);
	}

	/**
	 * Store or Udpate inspection event type
	 * 
	 * @param Array   $event_row        row of spreed sheet
	 * @param Integer $instantiation_id use to match event instantiation id
	 * 
	 * @return helper
	 */
	private function _store_event_type_inspection($event_row, $instantiation_id)
	{
		myLog('Start storing Event Inspection info from Spreadsheet');
		if ((isset($event_row[8]) && ! empty($event_row[8])))
		{
			$event_data = array();
			$event_type = 'inspection';
			$event_data['instantiations_id'] = $instantiation_id;
			$event_data['event_types_id'] = $this->instantiation->_get_event_type($event_type);
			if (isset($event_row[8]) && ! empty($event_row[8]))
			{
				$event_data['event_date'] = date('Y-m-d', strtotime(str_replace("'", '', trim($event_row[8]))));
			}
			if (isset($event_row[9]) && ! empty($event_row[9]))
			{
				$event_data['event_note'] = $event_row[9];
			}
			$this->instantiation->_insert_or_update_event($instantiation_id, $event_data['event_types_id'], $event_data);
		}
		else
		{
			myLog('No Event Inspection info from Spreadsheet');
		}
	}

	/**
	 * Store or Udpate baked event type
	 * 
	 * @param Array   $event_row        row of spreed sheet
	 * @param Integer $instantiation_id use to match event instantiation id
	 * 
	 * @return helper
	 */
	private function _store_event_type_baked($event_row, $instantiation_id)
	{

		if ((isset($event_row[12]) && ! empty($event_row[12])))
		{
			$event_type = 'baked';
			$event_data['instantiations_id'] = $instantiation_id;
			$event_data['event_types_id'] = $this->instantiation->_get_event_type($event_type);
			if (isset($event_row[12]) && ! empty($event_row[12]))
			{
				$event_data['event_date'] = date('Y-m-d', strtotime(str_replace("'", '', trim($event_row[12]))));
			}
			if (isset($event_row[13]) && ! empty($event_row[13]))
			{
				$event_data['event_note'] = $event_row[13];
			}
			$this->instantiation->_insert_or_update_event($instantiation_id, $event_data['event_types_id'], $event_data);
		}
		else
		{
			myLog('No Event Baked info from Spreadsheet');
		}
	}

	/**
	 * Store or Udpate cleaned event type
	 * 
	 * @param Array   $event_row        row of spreed sheet
	 * @param Integer $instantiation_id use to match event instantiation id
	 * 
	 * @return helper
	 */
	private function _store_event_type_cleaned($event_row, $instantiation_id)
	{
		if ((isset($event_row[14]) && ! empty($event_row[14])))
		{
			$event_type = 'cleaned';
			$event_data['instantiations_id'] = $instantiation_id;
			$event_data['event_types_id'] = $this->instantiation->_get_event_type($event_type);
			if (isset($event_row[14]) && ! empty($event_row[14]))
			{
				$event_data['event_date'] = date('Y-m-d', strtotime(str_replace("'", '', trim($event_row[14]))));
			}
			if (isset($event_row[16]) && ! empty($event_row[16]))
			{
				$event_data['event_note'] = $event_row[16];
			}
			$this->instantiation->_insert_or_update_event($instantiation_id, $event_data['event_types_id'], $event_data);
		}
		else
		{
			myLog('No Event Cleaned info from Spreadsheet');
		}
	}

	/**
	 * Store or Update migration event type
	 * 
	 * @param Array   $event_row        row of spreed sheet
	 * @param Integer $instantiation_id use to match event instantiation id
	 * 
	 * @return helper
	 */
	private function _store_event_type_migration($event_row, $instantiation_id)
	{
		if ((isset($event_row[17]) && ! empty($event_row[17])))
		{
			$event_type = 'migration';
			$event_data['instantiations_id'] = $instantiation_id;
			$event_data['event_types_id'] = $this->instantiation->_get_event_type($event_type);
			if (isset($event_row[17]) && ! empty($event_row[17]))
			{
				$event_data['event_date'] = date('Y-m-d', strtotime(str_replace("'", '', trim($event_row[17]))));
			}
			if (isset($event_row[34]) && ! empty($event_row[34]))
			{
				if ($event_row[34] === 'N' || strtolower($event_row[34]) === 'no')
					$event_data['event_outcome'] = 0;
				else
					$event_data['event_outcome'] = 1;
			}
			if (isset($event_row[35]) && ! empty($event_row[35]))
			{
				$event_data['event_note'] = $event_row[35];
			}
			$this->instantiation->_insert_or_update_event($instantiation_id, $event_data['event_types_id'], $event_data);
		}
		else
		{
			myLog('No Event Migration info from Spreadsheet');
		}
	}

// Location: ./controllers/googledoc.php
}

// END Google Doc Controller

// End of file googledoc.php
// Location: ./application/controllers/googledoc.php
