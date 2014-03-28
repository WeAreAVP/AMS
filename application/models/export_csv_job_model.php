<?php

/**
 * AMS Archive Management System
 * 
 * 
 * PHP version 5
 * 
 * @category   AMS
 * @package    CI
 * @subpackage Model
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @copyright  Copyright (c) WGBH (http://www.wgbh.org/). All Rights Reserved.
 * @license    http://www.gnu.org/licenses/gpl.txt GPLv3
 * @version    GIT: <$Id>
 * @link       https://github.com/avpreserve/AMS
 */

/**
 * Export_csv_job_model Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Model
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @copyright  Copyright (c) WGBH (http://www.wgbh.org/). All Rights Reserved.
 * @license    http://www.gnu.org/licenses/gpl.txt GPLv3
 * @link       https://ams.americanarchive.org
 */
class Export_csv_job_model extends CI_Model
{

	/**
	 * constructor. set table name amd prefix
	 * 
	 */
	function __construct()
	{
		parent::__construct();
		$this->_prefix = '';
		$this->_table = 'export_csv_job';
	}

	/**
	 * List all the records.
	 * 
	 * @return stdObject
	 */
	function get_all()
	{
		return $this->db->get($this->_table)->result();
	}

	/**
	 * Get export jobs that are still not processed.
	 * 
	 * @param string $type
	 * 
	 * @return stdObject
	 */
	function get_export_jobs($type)
	{
		$this->db->where('status', '0');
		$this->db->where('type', $type);
		$this->db->limit(1);
		return $this->db->get($this->_table)->row();
	}

	/**
	 * Run given query and return its result.
	 * 
	 * @param string $query
	 * 
	 * @return stdObject
	 */
	function get_csv_records($query)
	{
		return $this->db->query($query)->result();
	}

	/**
	 * Get export job by job_id.
	 * 
	 * @param integer $job_id
	 * 
	 * @return stdObject
	 */
	function get_job_by_id($job_id)
	{
		$this->db->where('id', $job_id);
		return $this->db->get($this->_table)->row();
	}

	/**
	 * Insert new job.
	 * 
	 * @param array $data
	 * 
	 * @return integer
	 */
	function insert_job($data)
	{
		$data['updated_at'] = date('Y-m-d H:i:s');
		$data['created_at'] = date('Y-m-d H:i:s');
		$this->db->insert($this->_table, $data);
		return $this->db->insert_id();
	}

	/**
	 * Update the existing job.
	 * 
	 * @param integer $job_id
	 * @param array $data
	 * 
	 * @return boolean
	 */
	function update_job($job_id, $data)
	{
		$data['updated_at'] = date('Y-m-d H:i:s');
		$this->db->where('id', $job_id);
		return $this->db->update($this->_table, $data);
	}

}

?>