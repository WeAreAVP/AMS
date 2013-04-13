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

	function get_identifier_source($term)
	{
		$this->db->select("DISTINCT identifier_source", FALSE);
		$this->db->like("identifier_source", $term);
		$this->db->where('identifier_source !=', "http://americanarchiveinventory.org");
		$result = $this->db->get('identifiers')->result();
		return $result;
	}

}

?>