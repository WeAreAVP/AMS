<?php

/**
 * Migrate Model
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
 * Migrate  Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Model
 * @author     Nouman Tayyab <nouman@geekschicago.com>
 * @license    AMS http://ams.avpreserve.com
 * @link       http://ams.avpreserve.com
 */
class Migrate_Model extends CI_Model
{

	/**
	 * constructor. set table name amd prefix
	 * 
	 */
	function __construct()
	{
		parent::__construct();

		$this->_prefix = '';
		$this->_table = '';
	}

	/**
	 * insert the records in tracing_info
	 * 
	 * @param array $data
	 * @return boolean 
	 */
	function insert_record($data, $table_name)
	{
		$this->db->insert($table_name, $data);
		return $this->db->insert_id();
	}

}

?>