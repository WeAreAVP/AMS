<?php

/**
 * Spreadsheet Model
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
 * Spreadsheet_Model Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Model
 * @author     Nouman Tayyab <nouman@geekschicago.com>
 * @license    AMS http://ams.avpreserve.com
 * @link       http://ams.avpreserve.com
 */
class Spreadsheet_Model extends CI_Model
{

	/**
	 * constructor. set table name amd prefix
	 * 
	 */
	function __construct()
	{
		parent::__construct();
		$this->_prefix = '';

		$this->_table = 'google_spreadsheets';
	}

	/**
	 * Insert spreadsheet info
	 * 
	 * @param array $data 
	 * @return integer inserted it
	 */
	function insert_record($data)
	{
		$data['created_at'] = date('Y-m-d H:m:i');
		$this->db->insert($this->_table, $data);
		return $this->db->insert_id();
	}

	/**
	 * Update spreadsheet
	 * 
	 * @param array $data 
	 * @return integer inserted it
	 */
	function update_mint_import_file($import_id, $data)
	{
		$this->db->where('id', $import_id);
		return $this->db->update($this->_table, $data);
	}

	/**
	 * Get Spreadsheet by last updated
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

}

?>