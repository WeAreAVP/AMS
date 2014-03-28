<?php


/**
 * AMS Archive Management System
 * 
 * 
 * 
 * PHP version 5
 * 
 * @category   AMS
 * @package    CI
 * @subpackage Core
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @copyright  Copyright (c) WGBH (http://www.wgbh.org/). All Rights Reserved.
 * @license    http://www.gnu.org/licenses/gpl.txt GPLv3
 * @version    GIT: <$Id>
 * @link       https://github.com/avpreserve/AMS
 */

/**
 * MY_Instantiation_Model Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Core
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @copyright  Copyright (c) WGBH (http://www.wgbh.org/). All Rights Reserved.
 * @license    http://www.gnu.org/licenses/gpl.txt GPLv3
 * @link       https://ams.americanarchive.org
 */
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

	/**
	 * Get Instantiation events info by asset ID.
	 * 
	 * @param integer $asset_id
	 * @return stdObject
	 */
	function get_instantiation_events($asset_id)
	{
		return $this->db->select("{$this->table_events}.*,{$this->table_event_types}.event_type")
		->join($this->table_event_types, "{$this->table_event_types}.id = {$this->table_events}.event_types_id", 'LEFT')
		->join($this->table_instantiations, "{$this->table_instantiations}.id = {$this->table_events}.instantiations_id", 'LEFT')
		->join($this->_assets_table, "{$this->_assets_table}.id = {$this->table_instantiations}.assets_id", 'LEFT')
		->where("{$this->_assets_table}.id", $asset_id)
		->get($this->table_events)->result();
	}

	function get_instantiation_with_event_by_asset_id($asset_id)
	{
		$this->db->select("$this->table_instantiations.id");
		$this->db->select("$this->table_events.event_types_id");
		$this->db->where("$this->table_instantiations.assets_id", $asset_id);
		$this->db->join($this->table_events, "$this->table_events.instantiations_id=$this->table_instantiations.id");

		return $this->db->get($this->table_instantiations)->row();
	}

	/**
	 * update the instantiations record
	 * 
	 * @param type $instantiation_id
	 * @param array $data
	 * @return boolean 
	 */
	function update_instantiations($instantiation_id, $data)
	{
		$data['updated'] = date('Y-m-d H:i:s');
		$this->db->where('id', $instantiation_id);
		return $this->db->update($this->table_instantiations, $data);
	}

	/**
	 * Last oldest spreadsheet to fetch its information.
	 * @return type
	 */
	function get_spreadsheets()
	{
		return $this->db->order_by("$this->google_spreadsheets.updated_at", 'ASC')
		->limit(1)
		->get($this->google_spreadsheets)->row();
	}

	/**
	 * Update google spreadsheet info.
	 * 
	 * @param integer $_id
	 * @param array $data
	 * @return boolean
	 */
	function update_spreadsheet($_id, $data)
	{
		$this->db->where('id', $_id);
		return $this->db->update($this->google_spreadsheets, $data);
	}

	/**
	 * Get all the missing import records of spreadsheet.
	 * 
	 * @return stdObject
	 */
	function get_failed_import()
	{
		return $this->db->get('missing_info_gsheets')->result();
	}

}
