<?php

/**
 * Pbcore_Model Model
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
 * AMS Pbcore_Model Class 
 *
 * @category   Class
 * @package    CI
 * @subpackage Model
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @version    GIT: <$Id>
 * @link       http://ams.avpreserve.com
 */
class Pbcore_Model extends MY_Model
{

	/**
	 * constructor. set table name amd prefix
	 * 
	 */
	function __construct()
	{
		parent::__construct();
	}

	function get_one_by($table, array $data)
	{
		foreach ($data as $column => $value)
		{
			$this->db->where($column, $value);
		}
		$result = $this->db->get($table);
		if (isset($result) && ! empty($result))
		{
			return $result->row();
		}
		return FALSE;
	}

	function get_by($table, array $data)
	{
		foreach ($data as $column => $value)
		{
			$this->db->where($column, $value);
		}
		$result = $this->db->get($table);
		if (isset($result) && ! empty($result))
		{
			return $result->result();
		}
		return FALSE;
	}

}

?>