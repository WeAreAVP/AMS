<?php

/**
 * Autocomplete Model
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
 * Autocomplete Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Model
 * @author     Nouman Tayyab <nouman@geekschicago.com>
 * @license    AMS http://ams.avpreserve.com
 * @link       http://ams.avpreserve.com
 */
class Autocomplete_Model extends CI_Model
{

	/**
	 * constructor. set table name amd prefix
	 * 
	 */
	function __construct()
	{
		parent::__construct();
	}

	function get_autocomplete_value($table, $column, $term)
	{
		$this->db->select("DISTINCT $column AS value", FALSE);
		$this->db->like("$column", $term,'before');
		if ($table === 'identifiers')
			$this->db->where("$column !=", "http://americanarchiveinventory.org");
		$this->db->limit(50);
		$result = $this->db->get($table)->result();
		return $result;
	}

	

	

}

?>