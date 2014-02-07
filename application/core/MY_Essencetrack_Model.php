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

	/**
	 * Update essence track information.
	 * 
	 * @param integer $essence_id
	 * @param array $data
	 * @return boolean
	 */
	function update_essence_track($essence_id, $data)
	{
		$this->db->where('id', $essence_id);
		return $this->db->update($this->table_essence_tracks, $data);
	}

	/**
	 * Get essence tracks information by instantiation id.
	 * 
	 * @param integer $ins_id
	 * 
	 * @return stdObject
	 */
	function get_essence_tracks_by_instantiations_id($ins_id)
	{
		$this->db->select("$this->table_essence_tracks.id")
		->select("$this->table_essence_tracks.standard,$this->table_essence_tracks.frame_rate")
		->select("$this->table_essence_tracks.playback_speed,$this->table_essence_tracks.sampling_rate")
		->select("$this->table_essence_tracks.bit_depth,$this->table_essence_tracks.aspect_ratio")
		->select("$this->table_essence_tracks.time_start,$this->table_essence_tracks.duration")
		->select("$this->table_essence_tracks.language,$this->table_essence_tracks.data_rate")
		->select("$this->table_data_rate_units.unit_of_measure")
		->select("$this->table_essence_track_types.essence_track_type")
		->select("$this->table_essence_track_frame_sizes.width,$this->table_essence_track_frame_sizes.height")
		->select("$this->table_essence_track_encodings.encoding,$this->table_essence_track_encodings.encoding_source,$this->table_essence_track_encodings.encoding_ref")
		->select("$this->table_essence_track_identifiers.essence_track_identifiers,$this->table_essence_track_identifiers.essence_track_identifier_source");

		$this->db->join($this->table_data_rate_units, "$this->table_data_rate_units.id=$this->table_essence_tracks.data_rate_units_id", 'LEFT')
		->join($this->table_essence_track_types, "$this->table_essence_track_types.id=$this->table_essence_tracks.essence_track_types_id", 'LEFT')
		->join($this->table_essence_track_frame_sizes, "$this->table_essence_track_frame_sizes.id=$this->table_essence_tracks.essence_track_frame_sizes_id", 'LEFT')
		->join($this->table_essence_track_encodings, "$this->table_essence_track_encodings.essence_tracks_id=$this->table_essence_tracks.id", 'LEFT')
		->join($this->table_essence_track_identifiers, "$this->table_essence_track_identifiers.essence_tracks_id=$this->table_essence_tracks.id", 'LEFT');
		$this->db->where("$this->table_essence_tracks.instantiations_id", $ins_id);
		return $this->db->get($this->table_essence_tracks)->result();
	}

}
