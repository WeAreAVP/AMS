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

}

?>