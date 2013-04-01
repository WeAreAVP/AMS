<?php

/**
 * Reports Model
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
 * Reports Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Model
 * @author     Nouman Tayyab <nouman@geekschicago.com>
 * @license    AMS http://ams.avpreserve.com
 * @link       http://ams.avpreserve.com
 */
class Report_Model extends CI_Model
{

	/**
	 * constructor. set table name amd prefix
	 * 
	 */
	function __construct()
	{
		parent::__construct();
		$this->_prefix = '';
		$this->_email_queue_table = 'email_queue';
	}

	/*
	 * Get All Email in Queue Table
	 * @perm condition Mysql where clause excluding WHERE keyword
	 * @Perm limit 
	 * @Perm offset
	 */

	function get_email_queue($condition = '', $offset = 0, $limit = 10)
	{
		if ( ! empty($condition))
			$this->db->where($condition, NULL, false);
		$this->db->select('*');
		$this->db->from($this->_email_queue_table);
		$this->db->limit($limit, $offset);
		$res = $this->db->get();
		if (isset($res) && ! empty($res))
		{
			return $res->result();
		}
		return false;
	}

}

?>