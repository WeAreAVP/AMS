<?php

if ( ! defined('BASEPATH'))
{
	exit('No direct script access allowed');
}

class MY_Instantiation_Model extends MY_Essencetrack_Model
{

	function __construct()
	{
		parent::__construct();
	}

	/**
	 * Get Instantiation dates by instantiations ID.
	 * 
	 * @param integer $instantiation_id
	 * @return stdObject
	 */
	function get_instantiation_dates($instantiation_id)
	{
		return $this->db->select("{$this->table_instantiation_dates}.instantiation_date,{$this->table_date_types}.date_type")
		->join($this->table_date_types, "{$this->table_date_types}.id = {$this->table_instantiation_dates}.date_types_id", 'LEFT')
		->where("{$this->table_instantiation_dates}.instantiations_id", $instantiation_id)
		->get($this->table_instantiation_dates)->result();
	}

}

?>