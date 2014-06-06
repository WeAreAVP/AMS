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
 * Refine Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @copyright  Copyright (c) WGBH (http://www.wgbh.org/). All Rights Reserved.
 * @license    http://www.gnu.org/licenses/gpl.txt GPLv3
 * @link       https://ams.americanarchive.org
 */
class Refine extends MY_Controller
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
		$this->load->library('googlerefine');
		$this->load->model('refine_modal');
		$this->load->model('sphinx_model', 'sphinx');
		$this->load->model('instantiations_model', 'instantiation');
		$this->load->model('assets_model');
	}

	/**
	 * Make query for exporting the AMS Refine and insert in database
	 * 
	 * @param string $type
	 * 
	 * @return json with message
	 */
	public function export($type)
	{
		if ($type == 'instantiation')
		{
			$query = $this->refine_modal->export_refine_csv(TRUE);
			$record = array('user_id' => $this->user_id, 'is_active' => 1, 'export_query' => $query, 'refine_type' => 'instantiation');
			$job_id = $this->refine_modal->insert_job($record);
		}
		else
		{
			$query = $this->refine_modal->export_asset_refine_csv(TRUE);
			$record = array('user_id' => $this->user_id, 'is_active' => 1, 'export_query' => $query, 'refine_type' => 'asset');
			$job_id = $this->refine_modal->insert_job($record);
		}
		echo json_encode(array('msg' => 'You will receive an email containing the link for AMS Refine.'));
		exit_function();
	}

	/**
	 * Remove Project from AMS Refine
	 * 
	 * @param type $project_id
	 * 
	 * @retun 
	 */
	public function remove($db_id)
	{
		$db_detail = $this->refine_modal->get_by_id_or_project_id($db_id);
		$this->googlerefine->delete_project($db_detail->project_id);
		
		if ($db_detail)
		{
			$data = array('is_active' => 0);
			$this->refine_modal->update_job($db_detail->id, $data);
			if ($db_detail->refine_type == 'instantiation')
				redirect('instantiations');
			else
				redirect('records');
		}
	}

	/**
	 * Save the Project info when changes are commited on AMS Refine.
	 * 
	 * @param type $project_id
	 * 
	 * @return 
	 */
	public function save($project_id)
	{
		$project_detail = $this->refine_modal->get_by_project_id($project_id);
		if ($project_detail)
		{
			$response = $this->googlerefine->export_rows($project_detail->project_name, $project_id);
			$filename = 'AMS_Refined_Data_' . time() . '.txt';
			$folder_path = $this->config->item('path') . 'assets/google_refine/' . date('Y') . '/' . date('M') . '/imports/';
			$file_path = $folder_path . $filename;
			if ( ! is_dir($folder_path))
				mkdir($folder_path, 0777, TRUE);

			$fp = fopen($file_path, 'a');
			file_put_contents($path, $response);
			$this->googlerefine->delete_project($project_id);
			$data = array('is_active' => 2, 'import_csv_path' => $path);
			$this->refine_modal->update_job($project_detail->id, $data);
                        debug($project_detail);
			if ($project_detail->refine_type == 'instantiation')
				redirect('instantiations');
			else
				redirect('records');
		}
	}

// Location: ./controllers/refine.php
}

// END Google Refine Class

// End of file refine.php
// Location: ./application/controllers/refine.php
