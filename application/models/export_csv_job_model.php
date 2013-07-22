<?php
/**
 * Export_csv_job Model
 * 
 * PHP version 5
 * 
 * @category   AMS
 * @package    CI
 * @subpackage Model
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @license    AVPS http://ams.avpreserve.com
 * @version    GIT: <$Id>
 * @link       http://ams.avpreserve.com
 */

/**
 * AMS Export_csv_job_model Class 
 *
 * @category   Class
 * @package    CI
 * @subpackage Model
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @version    GIT: <$Id>
 * @link       http://ams.avpreserve.com
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

	function get_all()
	{
		return $this->db->get($this->_table)->result();
	}

	function get_incomplete_jobs()
	{
		$this->db->where('status', '0');
		$this->db->limit(1);
		return $this->db->get($this->_table)->row();
	}

	function get_csv_records($query)
	{
		return $this->db->query($query)->result();
	}

	function get_job_by_id($job_id)
	{
		$this->db->where('id', $job_id);
		return $this->db->get($this->_table)->row();
	}

	function insert_job($data)
	{
		$data['updated_at'] = date('Y-m-d H:i:s');
		$data['created_at'] = date('Y-m-d H:i:s');
		$this->db->insert($this->_table, $data);
		return $this->db->insert_id();
	}

	function update_job($job_id, $data)
	{
		$data['updated_at'] = date('Y-m-d H:i:s');
		$this->db->where('id', $job_id);
		return $this->db->update($this->_table, $data);
	}

}

?>