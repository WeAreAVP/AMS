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
	 * 
	 * @return stdObject
	 */
	function get_instantiation_dates($instantiation_id)
	{
		return $this->db->select("{$this->table_instantiation_dates}.instantiation_date,{$this->table_date_types}.date_type")
		->join($this->table_date_types, "{$this->table_date_types}.id = {$this->table_instantiation_dates}.date_types_id", 'LEFT')
		->where("{$this->table_instantiation_dates}.instantiations_id", $instantiation_id)
		->get($this->table_instantiation_dates)->result();
	}

	/**
	 * Get Instantiation generations by instantiations ID.
	 * 
	 * @param type $instantiation_id
	 * 
	 * @return stdObject
	 */
	function get_instantiation_generations($instantiation_id)
	{
		return $this->db->select("{$this->table_generations}.generation")
		->join($this->table_generations, "{$this->table_generations}.id = {$this->table_instantiation_generations}.generations_id", 'LEFT')
		->where("{$this->table_instantiation_generations}.instantiations_id", $instantiation_id)
		->get($this->table_instantiation_generations)->result();
	}

	/**
	 * Get Instantiation Relations by instantiation ID.
	 * 
	 * @param integer $instantiation_id
	 * @return stdObject
	 */
	function get_instantiation_relations($instantiation_id)
	{
		return $this->db->select("{$this->table_instantiation_relations}.relation_identifier,{$this->table_relation_types}.*")
		->join($this->table_relation_types, "{$this->table_relation_types}.id = {$this->table_instantiation_relations}.relation_types_id", 'LEFT')
		->where("{$this->table_instantiation_relations}.instantiations_id", $instantiation_id)
		->get($this->table_instantiation_relations)->result();
	}
	/**
	 * Get Instantiation nomination by instantiation ID.
	 * 
	 * @param integer $instantiation_id
	 * @return stdObject
	 */
	function get_instantiation_nomination($instantiation_id)
	{
		return $this->db->select("{$this->table_nominations}.*,{$this->table_nomination_status}.*")
		->join($this->table_nomination_status, "{$this->table_nomination_status}.id = {$this->table_nominations}.nomination_status_id", 'LEFT')
		->where("{$this->table_nominations}.instantiations_id", $instantiation_id)
		->get($this->table_nominations)->result();
		
	}

}

?>