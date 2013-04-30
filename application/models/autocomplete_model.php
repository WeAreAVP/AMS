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
		if ($table === 'pbcore_picklist_value_by_type')
		{
			$this->db->select("DISTINCT value AS value", FALSE);
			$this->db->where("element_type_id", 16);
			$this->db->where("display_value !=", 3);
			$this->db->order_by("display_value,value");
		}
		else
		{
			$this->db->select("DISTINCT $column AS value", FALSE);
			$this->db->like("$column", $term, 'after');
			if ($table === 'identifiers')
				$this->db->where("$column !=", "http://americanarchiveinventory.org");
			$this->db->limit(50);
		}
		$result = $this->db->get($table)->result();
		return $result;
	}

}

?>