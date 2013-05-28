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
 * Mint Class
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

	function insert_record($data)
	{
		$this->db->insert($this->_table, $data);
		return $this->db->insert_id();
	}

	function insert_import_info($data)
	{
		$data['created_at'] = date('Y-m-d H:i:s');
		$this->db->insert($this->_table_mint_import_info, $data);
		return $this->db->insert_id();
	}

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

}

?>