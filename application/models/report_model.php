<?php

/**
 * Reports Model
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
 * Reports Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Model
 * @author     Nouman Tayyab <nouman@geekschicago.com>
 * @license    AMS http://ams.avpreserve.com
 * @link       http://ams.avpreserve.com
 */
class Report_Model extends CI_Model
{

	/**
	 * constructor. set table name amd prefix
	 * 
	 */
	function __construct()
	{
		parent::__construct();
		$this->_prefix = '';
		$this->_email_queue_table = 'email_queue';
		$this->_stations_table = 'stations';
		$this->_assets_table = 'assets';
		$this->_instantiations_table = 'instantiations';
		$this->_nomination_table = 'nominations';
		$this->_nomination_status_table = 'nomination_status';
		$this->_messages_table = 'messages';
		$this->_tracking_info_table = 'tracking_info';
	}

	/*
	 * Get All Email in Queue Table
	 * @perm condition Mysql where clause excluding WHERE keyword
	 * @Perm limit 
	 * @Perm offset
	 */

	function get_email_queue($condition = '', $offset = 0, $limit = 10)
	{
		if ( ! empty($condition))
			$this->db->where($condition, NULL, false);
		$this->db->select('*');
		$this->db->from($this->_email_queue_table);
		$this->db->limit($limit, $offset);
		$res = $this->db->get();
		if (isset($res) && ! empty($res))
		{
			return $res->result();
		}
		return false;
	}

	function scheduled_for_digitization_report()
	{
		$this->db->select("COUNT($this->_assets_table.id) as total,$this->_stations_table.station_name,$this->_stations_table.city,$this->_stations_table.state,$this->_stations_table.start_date", FALSE);
		$this->db->join($this->_assets_table, "$this->_assets_table.stations_id = $this->_stations_table.id");
		$this->db->join($this->_instantiations_table, "$this->_instantiations_table.assets_id = $this->_assets_table.id");
		$this->db->join($this->_nomination_table, "$this->_nomination_table.instantiations_id = $this->_instantiations_table.id");
		$this->db->where("$this->_stations_table.start_date IS NOT NULL");
		$this->db->or_where("$this->_stations_table.start_date !=", 0);
		$this->db->group_by("$this->_stations_table.id");
		$result = $this->db->get($this->_stations_table);
		return $result->result();
	}

	function materials_at_crawford_report()
	{
		$this->db->select("COUNT($this->_assets_table.id) as total,$this->_stations_table.station_name,$this->_stations_table.city,$this->_stations_table.state,$this->_stations_table.start_date", FALSE);
		$this->db->join($this->$this->_tracking_info_table, "$this->$this->_tracking_info_table.station_id = $this->_stations_table.id");
		$this->db->join($this->_assets_table, "$this->_assets_table.stations_id = $this->_stations_table.id");
		$this->db->join($this->_instantiations_table, "$this->_instantiations_table.assets_id = $this->_assets_table.id");
		$this->db->join($this->_nomination_table, "$this->_nomination_table.instantiations_id = $this->_instantiations_table.id");
		$this->db->where("$this->_stations_table.start_date IS NOT NULL");
		$this->db->or_where("$this->_stations_table.start_date !=", 0);
		$this->db->group_by("$this->_stations_table.id");
		$result = $this->db->get($this->_stations_table);
		return $result->result();
	}

	function shipment_return_report()
	{


		$this->db->select("", FALSE);
		$this->db->join($this->_table_messages, "$this->_table_messages.receiver_id = $this->_stations_table.id");
		$this->db->where("$this->_table_messages.msg_type", 3); //Shipment Return
		$result = $this->db->get($this->_stations_table);
		return $result->result();
	}

	function hard_disk_return_report()
	{
		$this->db->select("", FALSE);
		$this->db->join($this->_table_messages, "$this->_table_messages.receiver_id = $this->_stations_table.id");
		$this->db->where("$this->_table_messages.msg_type", 4); //Hard Drive Return Date
		$result = $this->db->get($this->_stations_table);
		return $result->result();
	}

}

?>