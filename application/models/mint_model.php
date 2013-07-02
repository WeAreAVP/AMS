<?php

/**
 * Mint Model
 * 
 * PHP version 5
 * 
 * @category   AMS
 * @package    CI
 * @subpackage Model
 * @author     Nouman Tayyab <nouman@geekschicago.com>
 * @license    AVPS http://ams.avpreserve.com
 * @version    GIT: <$Id>
 * @link       http://ams.avpreserve.com
 */

/**
 * Mint_Modal Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Model
 * @author     Nouman Tayyab <nouman@geekschicago.com>
 * @license    AMS http://ams.avpreserve.com
 * @link       http://ams.avpreserve.com
 */
class Mint_Model extends CI_Model
{

	/**
	 * constructor. set table name amd prefix
	 * 
	 */
	function __construct()
	{
		parent::__construct();
		$this->_prefix = '';

		$this->_table = 'mint_import';
		$this->_table_mint_import_info = 'mint_import_info';
		$this->_table_mint_transformation = 'mint_transformation';
	}

	/**
	 * Insert the login and import info of mint.
	 * 
	 * @param array $data 
	 * @return integer inserted it
	 */
	function insert_record($data)
	{
		$this->db->insert($this->_table, $data);
		return $this->db->insert_id();
	}

	/**
	 * Update the status of mint import file by id.
	 * 
	 * @param array $data 
	 * @return integer inserted it
	 */
	function update_mint_import_file($import_id, $data)
	{
		$this->db->where('id', $import_id);
		return $this->db->update($this->_table_mint_import_info, $data);
	} 

	/**
	 * Insert files info of mint import.
	 *  
	 * @param array $data
	 * @return interger last inserted id.
	 */
	function insert_import_info($data)
	{
		$data['created_at'] = date('Y-m-d H:i:s');
		$this->db->insert($this->_table_mint_import_info, $data);
		return $this->db->insert_id();
	}

	/**
	 * Get file info by file path.
	 * 
	 * @param string $path
	 * @return stdObject or boolean
	 */
	function get_import_info_by_path($path)
	{
		$this->db->where('path', $path);
		$result = $this->db->get($this->_table_mint_import_info);
		if (isset($result) && ! empty($result))
		{
			return $result->row();
		}
		return $result;
	}

	/**
	 * Get the files that are not processed.
	 * 
	 * @return stdObject or boolean
	 */
	function get_files_to_import()
	{
		$this->db->where('is_processed', 0);
		$this->db->like("status_reason", 'Not processed');
		$this->db->limit(1000);
		$result = $this->db->get($this->_table_mint_import_info);
		if (isset($result) && ! empty($result))
		{
			return $result->result();
		}
		return $result;
	}

	/**
	 * 
	 * @param integer $user_id
	 * @param integer $mint_user_id
	 * @param integer $transformed_id
	 * @return stdObject / boolean
	 */
	function get_transformation($user_id, $mint_user_id, $transformed_id)
	{
		$this->db->where('user_id', $user_id);
		$this->db->where('mint_user_id', $mint_user_id);
		$this->db->where('transformed_id', $transformed_id);
		$result = $this->db->get($this->_table_mint_transformation);
		if (isset($result) && ! empty($result))
		{
			return $result->row();
		}
		return FALSE;
	}

	/**
	 * Get Transformation info by transformed id.
	 * 
	 * @param integer $transformed_id
	 * @return boolean / stdObject
	 */
	function get_transformation_by_tID($transformed_id)
	{
		$this->db->where('transformed_id', $transformed_id);
		$result = $this->db->get($this->_table_mint_transformation);
		if (isset($result) && ! empty($result))
		{
			return $result->row();
		}
		return FALSE;
	}

	/**
	 * Get Transformation info by download status and approval status.
	 * 
	 * @param integer $is_download
	 * @return boolean / stdObject
	 */
	function get_transformation_download($is_download)
	{
		$this->db->where('is_downloaded', $is_download);
		$this->db->where('is_approved ', 2);
		$result = $this->db->get($this->_table_mint_transformation);
		if (isset($result) && ! empty($result))
		{
			return $result->row();
		}
		return FALSE;
	}

	/**
	 * Update the mint_transformation table info.
	 * 
	 * @param integer $dbID
	 * @param array $data
	 * @return Integer
	 */
	function update_transformation($dbID, $data)
	{
		$data['updated_at'] = date('Y-m-d H:i:s');
		$this->db->where('id', $dbID);
		return $this->db->update($this->_table_mint_transformation, $data);
	}

	/**
	 * Insert the transformation info in mint_transformation.
	 * 
	 * @param array $data
	 * @return integer
	 */
	function insert_transformation($data)
	{
		$data['created_at'] = date('Y-m-d H:i:s');
		$data['updated_at'] = date('Y-m-d H:i:s');
		$this->db->insert($this->_table_mint_transformation, $data);
		return $this->db->insert_id();
	}

	function get_station_by_transformed($folder_name)
	{
		$this->db->select('users.station_id');
		$this->db->where("$this->_table_mint_transformation.folder_name", $folder_name);
		$this->db->join('users', "users.id=$this->_table_mint_transformation.user_id");
		$result = $this->db->get($this->_table_mint_transformation);
		if (isset($result) && ! empty($result))
		{
			return $result->row();
		}
		return FALSE;
	}
	function get_last_import_by_user($folder_name)
	{
		$this->db->select("$this->_table.station_id");
		$this->db->where("$this->_table_mint_transformation.folder_name", $folder_name);
		$this->db->join("$this->_table", "$this->_table.user_id=$this->_table_mint_transformation.user_id");
		$this->db->order_by("$this->_table_mint_transformation.id", "desc"); 
		$result = $this->db->get($this->_table_mint_transformation);
		if (isset($result) && ! empty($result))
		{
			return $result->row();
		}
		return FALSE;
	}

}

?>