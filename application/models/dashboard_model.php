<?php

/**
 * Dashboard model
 * 
 * PHP version 5
 * 
 * @category   AMS
 * @package    CI
 * @subpackage Model
 * @author     Nouman Tayyab <nouman@geekschicago.com>
 * @license    AMS http://ams.avpreserve.com
 * @version    GIT: <$Id>
 * @link       http://ams.avpreserve.com
 */

/**
 * Dashboard Model Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Model
 * @author     Nouman Tayyab <nouman@geekschicago.com>
 * @license    AMS http://ams.avpreserve.com
 * @link       http://ams.avpreserve.com
 */
class Dashboard_Model extends CI_Model
{

	/**
	 * constructor. set table name amd prefix
	 * 
	 */
	function __construct()
	{
		parent::__construct();
		$this->_prefix = '';
		$this->_table = 'stations';
		$this->table_instantiation_formats = 'instantiation_formats';
		$this->table_instantiations = 'instantiations';
		$this->table_instantiation_media_types = 'instantiation_media_types';
		$this->table_nominations = 'nominations';
		$this->table_nomination_status = 'nomination_status';
		$this->_table_messages = 'messages';
		$this->_table_assets = 'assets';
	}

	function get_digitized_formats()
	{
		$this->db->select("$this->table_instantiation_formats.format_name", FALSE);

		$this->db->join($this->table_instantiation_formats, "$this->table_instantiation_formats.instantiations_id = $this->table_instantiations.id");
		$this->db->where("$this->table_instantiations.digitized", '1');
		$this->db->group_by("$this->table_instantiation_formats.instantiations_id");
		$result = $this->db->get($this->table_instantiations);

		return $result->result();
	}

	function get_scheduled_formats()
	{
		$this->db->select("$this->table_instantiation_formats.format_name", FALSE);

		$this->db->join($this->table_instantiations, "$this->table_instantiations.id = $this->table_instantiation_formats.instantiations_id");
		$this->db->join($this->table_nominations, "$this->table_nominations.instantiations_id = $this->table_instantiation_formats.instantiations_id");
		$this->db->where("$this->table_instantiations.digitized", '0');
		$this->db->or_where("$this->table_instantiations.digitized IS NULL");
		$this->db->group_by("$this->table_instantiation_formats.instantiations_id");
		$result = $this->db->get($this->table_instantiation_formats);

		return $result->result();
	}

	function get_material_goal()
	{
		$this->db->select("count(DISTINCT $this->table_instantiations.id) AS total", FALSE);
		$this->db->join($this->table_nominations, "$this->table_nominations.instantiations_id = $this->table_instantiations.id");
		$this->db->join($this->table_nomination_status, "$this->table_nomination_status.id = $this->table_nominations.nomination_status_id");
		$this->db->where("$this->table_nomination_status.status", 'Nominated/1st Priority');
		$result = $this->db->get($this->table_instantiations);
		return $result->row();
	}

	function get_digitized_hours()
	{
		$this->db->select("count(DISTINCT $this->table_instantiations.id) AS total", FALSE);
		$this->db->where("$this->table_instantiations.digitized", '1');
		$result = $this->db->get($this->table_instantiations);

		return $result->row();
	}

	/**
	 * Get the hours of Crawford
	 * 
	 * @param string $msg_type
	 * @return type 
	 */
	function get_hours_at_crawford($msg_type)
	{
		$this->db->select("($this->_table.nominated_hours_final+$this->_table.nominated_buffer_final) AS total", FALSE);
		$this->db->join($this->_table_messages, "$this->_table_messages.station_id = $this->_table.id");
		$this->db->where("$this->_table_messages.msg_type", $msg_type);
		$this->db->group_by("$this->_table_messages.station_id");
		$result = $this->db->get($this->_table);

		return $result->result();
	}

	function digitized_total_by_region($region)
	{
		$this->db->select("COUNT($this->_table_assets.id) AS total", FALSE);
		$this->db->join($this->_table, "$this->_table.id=$this->_table_assets.stations_id");
		$this->db->join($this->table_instantiations, "$this->table_instantiations.assets_id=$this->_table_assets.id");

		$this->db->where("$this->table_instantiations.digitized", 1);
		if ($region == 'other')
			$this->db->where_in("$this->_table.state", array('AK', 'GU', 'HI',)); //other
		else if ($region == 'midwest')
			$this->db->where_in("$this->_table.state", array('IA', 'IL', 'IN', 'MI', 'MN', 'MO', 'ND', 'OH', 'WI')); //midwest
		else if ($region == 'northeast')
			$this->db->where_in("$this->_table.state", array('CT', 'MA', 'ME', 'NH', 'NJ', 'NY', 'PA', 'RI', 'VT')); //northeast
		else if ($region == 'south')
			$this->db->where_in("$this->_table.state", array('AR', 'DC', 'FL', 'GA', 'KY', 'LA', 'MD', 'NC', 'SC', 'TN', 'TX', 'VA')); //south
		else if ($region == 'west')
			$this->db->where_in("$this->_table.state", array('AZ', 'CA', 'CO', 'ID', 'MT', 'NM', 'NV', 'OR', 'UT', 'WA', 'WY')); //west

		$result = $this->db->get($this->_table_assets);

		return $result->row();
	}

	function digitized_hours_by_region($region)
	{
		$this->db->select("HOUR(SEC_TO_TIME(SUM(TIME_TO_SEC($this->table_instantiations.actual_duration)))) AS time", FALSE);
		$this->db->join($this->_table, "$this->_table.id=$this->_table_assets.stations_id");
		$this->db->join($this->table_instantiations, "$this->table_instantiations.assets_id=$this->_table_assets.id");
		
		$this->db->where("$this->table_instantiations.digitized", 0);

		if ($region == 'other')
			$this->db->where_in("$this->_table.state", array('AK', 'GU', 'HI',)); //other
		else if ($region == 'midwest')
			$this->db->where_in("$this->_table.state", array('IA', 'IL', 'IN', 'MI', 'MN', 'MO', 'ND', 'OH', 'WI')); //midwest
		else if ($region == 'northeast')
			$this->db->where_in("$this->_table.state", array('CT', 'MA', 'ME', 'NH', 'NJ', 'NY', 'PA', 'RI', 'VT')); //northeast
		else if ($region == 'south')
			$this->db->where_in("$this->_table.state", array('AR', 'DC', 'FL', 'GA', 'KY', 'LA', 'MD', 'NC', 'SC', 'TN', 'TX', 'VA')); //south
		else if ($region == 'west')
			$this->db->where_in("$this->_table.state", array('AZ', 'CA', 'CO', 'ID', 'MT', 'NM', 'NV', 'OR', 'UT', 'WA', 'WY')); //west

		$result = $this->db->get($this->_table);
		echo $this->db->last_query().'<br/>';
		return $result->row();
	}

	function pie_total_scheduled()
	{
		$where = " ($this->table_instantiation_media_types.media_type LIKE '%sound%' OR $this->table_instantiation_media_types.media_type LIKE '%moving image%') ";
		$this->db->select("COUNT($this->_table_assets.id) as total");
		$this->db->join($this->_table_assets, "$this->_table_assets.stations_id = $this->_table.id");
		$this->db->join($this->table_instantiations, "$this->table_instantiations.assets_id = $this->_table_assets.id");
		$this->db->join($this->table_instantiation_media_types, "$this->table_instantiation_media_types.id = $this->table_instantiations.instantiation_media_type_id");
		$this->db->join($this->table_nominations, "$this->table_nominations.instantiations_id = $this->table_instantiations.id");
		$this->db->join($this->_table_messages, "$this->_table_messages.station_id = $this->_table.id");
		$this->db->where("$this->_table_messages.msg_type", 1); //DSD Alert
		$this->db->where("$this->table_instantiations.digitized IS NULL");
		$this->db->where($where, NULL, FALSE);
		$result = $this->db->get($this->_table);
		return $result->row();
	}

	function pie_total_completed()
	{
		$where = " ($this->table_instantiation_media_types.media_type LIKE '%sound%' OR $this->table_instantiation_media_types.media_type LIKE '%moving image%') ";
		$this->db->select("COUNT($this->_table_assets.id) as total");
		$this->db->join($this->_table_assets, "$this->_table_assets.stations_id = $this->_table.id");
		$this->db->join($this->table_instantiations, "$this->table_instantiations.assets_id = $this->_table_assets.id");
		$this->db->join($this->table_instantiation_media_types, "$this->table_instantiation_media_types.id = $this->table_instantiations.instantiation_media_type_id");
		$this->db->where("$this->table_instantiations.digitized", 1);
		$this->db->where($where, NULL, FALSE);
		$result = $this->db->get($this->_table);
		return $result->row();
	}

	function pie_total_radio_scheduled()
	{
		$this->db->select("COUNT($this->_table_assets.id) as total");
		$this->db->join($this->_table_assets, "$this->_table_assets.stations_id = $this->_table.id");
		$this->db->join($this->table_instantiations, "$this->table_instantiations.assets_id = $this->_table_assets.id");
		$this->db->join($this->table_instantiation_media_types, "$this->table_instantiation_media_types.id = $this->table_instantiations.instantiation_media_type_id");
		$this->db->join($this->table_nominations, "$this->table_nominations.instantiations_id = $this->table_instantiations.id");
		$this->db->join($this->_table_messages, "$this->_table_messages.station_id = $this->_table.id");
//		$this->db->where_in("$this->_table.type", array(0, 2));
		$this->db->where("$this->_table_messages.msg_type", 1); //DSD Alert
		$this->db->where("$this->table_instantiations.digitized IS NULL");
		$this->db->like("$this->table_instantiation_media_types.media_type", 'sound');


		$result = $this->db->get($this->_table);
		return $result->row();
	}

	function pie_total_radio_completed()
	{
		$this->db->select("COUNT($this->_table_assets.id) as total");
		$this->db->join($this->_table_assets, "$this->_table_assets.stations_id = $this->_table.id");
		$this->db->join($this->table_instantiations, "$this->table_instantiations.assets_id = $this->_table_assets.id");
		$this->db->join($this->table_instantiation_media_types, "$this->table_instantiation_media_types.id = $this->table_instantiations.instantiation_media_type_id");
		$this->db->where("$this->table_instantiations.digitized", 1);
		$this->db->like("$this->table_instantiation_media_types.media_type", 'sound');
//		$this->db->join($this->_table_messages, "$this->_table_messages.receiver_id = $this->_table.id");
//		$this->db->where_in("$this->_table.type", array(0, 2));
//		$this->db->where("$this->_table_messages.msg_type", 4); //Hard Drive Return Date
		$result = $this->db->get($this->_table);
		return $result->row();
	}

	function pie_total_tv_scheduled()
	{
		$this->db->select("COUNT($this->_table_assets.id) as total");
		$this->db->join($this->_table_assets, "$this->_table_assets.stations_id = $this->_table.id");
		$this->db->join($this->table_instantiations, "$this->table_instantiations.assets_id = $this->_table_assets.id");
		$this->db->join($this->table_instantiation_media_types, "$this->table_instantiation_media_types.id = $this->table_instantiations.instantiation_media_type_id");
		$this->db->join($this->table_nominations, "$this->table_nominations.instantiations_id = $this->table_instantiations.id");
		$this->db->join($this->_table_messages, "$this->_table_messages.station_id = $this->_table.id");
//		$this->db->where_in("$this->_table.type", array(0, 2));
		$this->db->where("$this->_table_messages.msg_type", 1); //DSD Alert
		$this->db->where("$this->table_instantiations.digitized IS NULL");
		$this->db->like("$this->table_instantiation_media_types.media_type", 'moving image');
		$result = $this->db->get($this->_table);
		return $result->row();
	}

	function pie_total_tv_completed()
	{
		$this->db->select("COUNT($this->_table_assets.id) as total");
		$this->db->join($this->_table_assets, "$this->_table_assets.stations_id = $this->_table.id");
		$this->db->join($this->table_instantiations, "$this->table_instantiations.assets_id = $this->_table_assets.id");
		$this->db->join($this->table_instantiation_media_types, "$this->table_instantiation_media_types.id = $this->table_instantiations.instantiation_media_type_id");
		$this->db->where("$this->table_instantiations.digitized", 1);
		$this->db->like("$this->table_instantiation_media_types.media_type", 'moving image');
		$result = $this->db->get($this->_table);
		return $result->row();
	}

}
