<?php

/**
 * Searchd Model
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
 * Searchd Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Model
 * @author     Nouman Tayyab <nouman@geekschicago.com>
 * @license    AMS http://ams.avpreserve.com
 * @link       http://ams.avpreserve.com
 */
class Searchd_Model extends CI_Model
{
	/*
	 *
	 * constructor. Load Sphinx Search Library
	 * 
	 */

	function __construct()
	{
		parent::__construct();
		$this->load->database('sphnix');
	}
	function check_sphnix(){
		$query=$this->db->query('SELECT * FROM assets_list limit 10');
		debug($query->result());
	}

}

?>
