<?php
	
	/**
	* Assets Model.
	*
	* @package    AMS
	* @subpackage assets_model
	* @author     ALi RAza
	*/
class Instantiations_Model extends CI_Model
{
	
	/**
	* constructor. set table name amd prefix
	* 
	*/
	function __construct()
	{
		parent::__construct();
		$this->_prefix = '';
	}
}
?>