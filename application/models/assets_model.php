<?php

/**
 * Assets Model.
 *
 * @package    AMS
 * @subpackage assets_model
 * @author     ALi RAza
 */
class Assets_Model extends CI_Model
{
	/**
    * constructor. set table name amd prefix
    * 
    */
	function __construct()
	{
		parent::__construct();
		$this->_prefix = '';
		$this->_assets_table = 'assets';
		$this->_table_backup = 'stations_backup';
		$this->_table_asset_title_types='asset_title_types';
		$this->_table_subjects='subjects';
		$this->_table_assets_subjects='assets_subjects';
		$this->_table_asset_descriptions='asset_descriptions';
		$this->_table_description_types='description_types';
		$this->_table_identifiers='identifiers';
		 
		
		
	}

	/**
	* Get list of all the Assets
	* 
	* @return array 
	*/
	function get_all()
	{
		return $query = $this->db->get($this->_assets_table)->result();
	}
	/**
	* search id by description_type
	* 
	* @param type $description_type
	* @return object 
	*/
	function get_description_id_by_type($description_type)
	{
		$this->db->where('description_type', $description_type);
		$res=$this->db->get($this->_table_description_types);
		if(isset($res) && !empty($res))
		{
			return $res->row();
		}
		return false;
	}
	
	/**
	* search asset_title_types by title_type
	* 
	* @param type $title_type
	* @return object 
	*/
	function get_subjects_id_by_subject($subject)
	{
		$this->db->where('subject', $subject);
		$res=$this->db->get($this->_table_subjects);
		if(isset($res) && !empty($res))
		{
			return $res->row();
		}
		return false;
	}
	/**
	* search asset_title_types by title_type
	* 
	* @param type $title_type
	* @return object 
	*/
	function get_asset_title_types_by_title_type($title_type)
	{
		$this->db->where('title_type', $title_type);
		$res=$this->db->get($this->_table_asset_title_types);
		if(isset($res) && !empty($res))
		{
			return $res->row();
		}
		return false;
	}
	/**
	* search asset by id
	* 
	* @param type $id
	* @return object 
	*/
	function get_asset_by_id($assets_id)
	{
		$this->db->where('id', $station_id);
		$res=$this->db->get($this->_assets_table);
		if(isset($res) && !empty($res))
		{
			return $res->row();
		}
		return false;
	}
	/**
	* get assets by staion id
	* 
	* @param type $station_id
	* @return array 
	*/
	function get_assets_by_station_id($station_id)
	{
		$this->db->select('*');
		$this->db->where('stations_id', $station_id);
		return $this->db->get($this->_assets_table)->result();
	}
	/**
	* update the stations record
	* 
	* @param type $station_id
	* @param array $data
	* @return boolean 
	*/
	function update_assets($id, $data)
	{
		$data['updated'] = date('Y-m-d H:i:s');
		$this->db->where('id', $id);
		return $this->db->update($this->_assets_table, $data);
	}
	
	/*
	*
	* insert the records in assets 
	* 
	* @param array $data
	* @return last inserted id 
	*/
	function insert_assets($data)
	{
		$this->db->insert($this->_assets_table, $data);
		return  $this->db->insert_id();
	}
	/*
	*
	* insert the records in asset_title_types 
	* 
	* @param array $data
	* @return last inserted id 
	*/
	function insert_asset_title_types($data)
	{
		$this->db->insert($this->_table_asset_title_types, $data);
		return  $this->db->insert_id();
	}
	/*
	*
	* insert the records in subjects 
	* 
	* @param array $data
	* @return last inserted id 
	*/
	function insert_subjects($data)
	{
		$this->db->insert($this->_table_subjects, $data);
		return  $this->db->insert_id();
	}
	/*
	*
	* insert the records in assets_subjects
	* 
	* @param array $data
	* @return last inserted id 
	*/
	function insert_assets_subjects($data)
	{
		$this->db->insert($this->_table_assets_subjects, $data);
		return  $this->db->insert_id();
	}
	/*
	*
	* insert the records in asset_descriptions
	* 
	* @param array $data
	* @return last inserted id 
	*/
	function insert_asset_descriptions($data)
	{
		$this->db->insert($this->_table_asset_descriptions, $data);
		return  $this->db->insert_id();
	}
	/*
	*
	* insert the records in description_types
	* 
	* @param array $data
	* @return last inserted id 
	*/
	function insert_description_types($data)
	{
		$this->db->insert($this->_table_description_types, $data);
		return  $this->db->insert_id();
	}
	
	/*
	*
	* insert the records in identifiers
	* 
	* @param array $data
	* @return last inserted id 
	*/
	function insert_identifiers($data)
	{
		$this->db->insert($this->_table_identifiers, $data);
		return  $this->db->insert_id();
	}
	/*
	*
	* insert the records in asset_titles
	* 
	* @param array $data
	* @return last inserted id 
	*/
	function insert_asset_titles($data)
	{
		$this->db->insert($this->_table_asset_titles, $data);
		return  $this->db->insert_id();
	}
	
	
	
}

?>