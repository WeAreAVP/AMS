<?php

/**
 * MY_Essencetrack_Model Core
 * 
 * PHP version 5
 * 
 * @category   AMS
 * @package    CI
 * @subpackage Core
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @license    AMS http://ams.avpreserve.com
 * @version    GIT: <$Id>
 * @link       http://ams.avpreserve.com
 */
if ( ! defined('BASEPATH'))
{
	exit('No direct script access allowed');
}

/**
 * MY_Essencetrack_Model Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Core
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @license    AMS http://ams.avpreserve.com
 * @link       http://ams.avpreserve.com
 */
class MY_Essencetrack_Model extends MY_Model
{

	function __construct()
	{
		parent::__construct();
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

}
