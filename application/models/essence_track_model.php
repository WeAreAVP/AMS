<?php

/**
 * Essence Track Class
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
 * Essence Track Model
 *
 * @category   Class
 * @package    CI
 * @subpackage Model
 * @author     Nouman Tayyab <nouman@geekschicago.com>
 * @license    AMS http://ams.avpreserve.com
 * @link       http://ams.avpreserve.com
 */ 
class Essence_Track_Model extends CI_Model
{

	/**
	 * constructor. set table name amd prefix
	 * 
	 */
	function __construct()
	{
		parent::__construct();
		$this->_prefix = '';
		$this->_table_essence_tracks = 'essence_tracks';
		$this->_table_essence_track_types = 'essence_track_types';
		$this->_table_essence_track_identifiers = 'essence_track_identifiers';
		$this->_table_essence_track_encodings = 'essence_track_encodings';
		$this->_table_essence_track_annotations = 'essence_track_annotations';
		$this->_table_essence_track_frame_sizes = 'essence_track_frame_sizes';
		$this->_table_data_rate_units = 'data_rate_units';
	}

	

	/**
	 * search generations by @generation
	 * 
	 * @param type $status
	 * @return object 
	 */
	function get_essence_track_by_type($essence_track_type)
	{
		$this->db->where('essence_track_type LIKE', $essence_track_type);
		$res = $this->db->get($this->_table_essence_track_types);
		if (isset($res) && ! empty($res))
		{
			return $res->row();
		}
		return false;
	}

	/*
	 *
	 *  Insert the record in essence_track_frame_sizes table
	 *  @param array $data
	 *  @return integer last_inserted id
	 * 
	 */

	function insert_essence_track_frame_sizes($data)
	{
		$this->db->insert($this->_table_essence_track_frame_sizes, $data);
		return $this->db->insert_id();
	}

	/*
	 *
	 *  Insert the record in essence_track_types table
	 *  @param array $data
	 *  @return integer last_inserted id
	 * 
	 */

	function insert_essence_track_types($data)
	{
		$this->db->insert($this->_table_essence_track_types, $data);
		return $this->db->insert_id();
	}

	/*
	 *
	 *  Insert the record in essence_tracks table
	 *  @param array $data
	 *  @return integer last_inserted id
	 * 
	 */

	function insert_essence_tracks($data)
	{
		$this->db->insert($this->_table_essence_tracks, $data);
		return $this->db->insert_id();
	}

	/*
	 *
	 *  Insert the record in essence_track_identifiers table
	 *  @param array $data
	 *  @return integer last_inserted id
	 * 
	 */

	function insert_essence_track_identifiers($data)
	{
		$this->db->insert($this->_table_essence_track_identifiers, $data);
		return $this->db->insert_id();
	}

	/*
	 *
	 *  Insert the record in essence_track_encodings table
	 *  @param array $data
	 *  @return integer last_inserted id
	 * 
	 */

	function insert_essence_track_encodings($data)
	{
		$this->db->insert($this->_table_essence_track_encodings, $data);
		return $this->db->insert_id();
	}

	/*
	 *
	 *  Insert the record in essence_track_annotations table
	 *  @param array $data
	 *  @return integer last_inserted id
	 * 
	 */

	function insert_essence_track_annotations($data)
	{
		$this->db->insert($this->_table_essence_track_annotations, $data);
		return $this->db->insert_id();
	}

	function update_essence_track($essence_id, $data)
	{
		$this->db->where('id', $essence_id);
		return $this->db->update($this->_table_essence_tracks, $data);
	}

	function get_essence_tracks_by_instantiations_id($ins_id)
	{
		$this->db->select("$this->_table_essence_tracks.id");
		$this->db->select("$this->_table_essence_tracks.standard,$this->_table_essence_tracks.frame_rate");
		$this->db->select("$this->_table_essence_tracks.playback_speed,$this->_table_essence_tracks.sampling_rate");
		$this->db->select("$this->_table_essence_tracks.bit_depth,$this->_table_essence_tracks.aspect_ratio");
		$this->db->select("$this->_table_essence_tracks.time_start,$this->_table_essence_tracks.duration");
		$this->db->select("$this->_table_essence_tracks.language,$this->_table_essence_tracks.data_rate");
		$this->db->select("$this->_table_data_rate_units.unit_of_measure");
		$this->db->select("$this->_table_essence_track_types.essence_track_type");
		$this->db->select("$this->_table_essence_track_frame_sizes.width,$this->_table_essence_track_frame_sizes.height");
		$this->db->select("$this->_table_essence_track_encodings.encoding,$this->_table_essence_track_encodings.encoding_source,$this->_table_essence_track_encodings.encoding_ref");
		$this->db->select("$this->_table_essence_track_identifiers.essence_track_identifiers,$this->_table_essence_track_identifiers.essence_track_identifier_source");

		$this->db->join($this->_table_data_rate_units, "$this->_table_data_rate_units.id=$this->_table_essence_tracks.data_rate_units_id", 'LEFT');
		$this->db->join($this->_table_essence_track_types, "$this->_table_essence_track_types.id=$this->_table_essence_tracks.essence_track_types_id", 'LEFT');
		$this->db->join($this->_table_essence_track_frame_sizes, "$this->_table_essence_track_frame_sizes.id=$this->_table_essence_tracks.essence_track_frame_sizes_id", 'LEFT');

		$this->db->join($this->_table_essence_track_encodings, "$this->_table_essence_track_encodings.essence_tracks_id=$this->_table_essence_tracks.id", 'LEFT');
		$this->db->join($this->_table_essence_track_identifiers, "$this->_table_essence_track_identifiers.essence_tracks_id=$this->_table_essence_tracks.id", 'LEFT');
		$this->db->where("$this->_table_essence_tracks.instantiations_id", $ins_id);
		return $this->db->get($this->_table_essence_tracks)->result();
	}

	function get_annotation_by_essence_track_id($essence_track_id)
	{
		$this->db->where("$this->_table_essence_track_annotations.essence_tracks_id", $essence_track_id);
		return $this->db->get($this->_table_essence_track_annotations)->result();
	}

}

?>