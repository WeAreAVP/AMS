<?php

/**
 * Instantiations Model
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
 * Instantiations  Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Model
 * @author     Nouman Tayyab <nouman@geekschicago.com>
 * @license    AMS http://ams.avpreserve.com
 * @link       http://ams.avpreserve.com
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

		$this->table_date_types = 'date_types';
		$this->table_generations = 'generations';
		$this->table_instantiations = 'instantiations';
		$this->table_relation_types = 'relation_types';
		$this->table_data_rate_units = 'data_rate_units';
		$this->table_instantiation_dates = 'instantiation_dates';
		$this->table_instantiation_colors = 'instantiation_colors';
		$this->table_instantiation_formats = 'instantiation_formats';
		$this->table_instantiation_relations = 'instantiation_relations';
		$this->table_instantiation_identifier = 'instantiation_identifier';
		$this->table_instantiation_dimensions = 'instantiation_dimensions';
		$this->table_instantiation_media_types = 'instantiation_media_types';
		$this->table_instantiation_generations = 'instantiation_generations';
		$this->table_instantiation_annotations = 'instantiation_annotations';

		$this->_assets_table = 'assets';
		$this->asset_titles = 'asset_titles';
		$this->stations = 'stations';
		$this->table_nominations = 'nominations';
		$this->table_nomination_status = 'nomination_status';
		$this->table_events = 'events';
		$this->table_event_types = 'event_types';
	}

	function get_by_id($id)
	{
		$this->db->where('id ', $id);
		return $this->db->get($this->table_instantiations)->row();
	}

	/**
	 *  Insert the record in relation_types table
	 *  @param array $data
	 *  @return integer last_inserted id
	 * 
	 */
	function insert_instantiation_relation($data)
	{
		$this->db->insert($this->table_instantiation_relations, $data);
		return $this->db->insert_id();
	}

	function get_date_types()
	{
		$this->db->order_by("date_type");
		return $this->db->get($this->table_date_types)->result();
	}

	function list_all()
	{
		$this->db->select("$this->table_instantiations.*", FALSE);
		$this->db->select("$this->_assets_table.id as asset_id", FALSE);
		$this->db->select("GROUP_CONCAT($this->asset_titles.title SEPARATOR ' | ') as multi_assets", FALSE);
		$this->db->select("$this->stations.station_name as organization", FALSE);
		$this->db->select("$this->table_instantiation_dates.instantiation_date", FALSE);
		$this->db->select("$this->table_date_types.date_type", FALSE);
		$this->db->select("$this->table_instantiation_media_types.media_type", FALSE);
		$this->db->select("$this->table_instantiation_formats.format_type,$this->table_instantiation_formats.format_name", FALSE);
		$this->db->select("$this->table_instantiation_colors.color", FALSE);
		$this->db->select("$this->table_generations.generation", FALSE);
		$this->db->select("$this->table_nomination_status.status", FALSE);
		$this->db->select("CASE WHEN $this->table_events.event_outcome=0 THEN 'FAIL' WHEN $this->table_events.event_outcome=1 THEN 'PASS' END AS outcome_evnet", FALSE);
		$this->db->select("$this->table_event_types.event_type", FALSE);






		$this->db->join($this->_assets_table, "$this->_assets_table.id = $this->table_instantiations.assets_id", 'left');
		$this->db->join($this->_table_asset_descriptions, "$this->_table_asset_descriptions.assets_id = $this->_assets_table.id", 'left');

		$this->db->join($this->asset_titles, "$this->asset_titles.assets_id	 = $this->table_instantiations.assets_id", 'left');
		$this->db->join($this->stations, "$this->stations.id = $this->_assets_table.stations_id", 'left');
		$this->db->join($this->table_instantiation_dates, "$this->table_instantiation_dates.instantiations_id = $this->table_instantiations.id", 'left');
		$this->db->join($this->table_date_types, "$this->table_date_types.id = $this->table_instantiation_dates.date_types_id", 'left');
		$this->db->join($this->table_instantiation_media_types, "$this->table_instantiation_media_types.id = $this->table_instantiations.instantiation_media_type_id", 'left');
		$this->db->join($this->table_instantiation_formats, "$this->table_instantiation_formats.instantiations_id = $this->table_instantiations.id", 'left');
		$this->db->join($this->table_instantiation_colors, "$this->table_instantiation_colors.id = $this->table_instantiations.instantiation_colors_id", 'left');
		$this->db->join($this->table_instantiation_generations, "$this->table_instantiation_generations.instantiations_id = $this->table_instantiations.id", 'left');
		$this->db->join($this->table_generations, "$this->table_generations.id = $this->table_instantiation_generations.generations_id", 'left');
		$this->db->join($this->table_nominations, "$this->table_nominations.instantiations_id = $this->table_instantiations.id", 'left');
		$this->db->join($this->table_nomination_status, "$this->table_nomination_status.id = $this->table_nominations.nomination_status_id", 'left');
		$this->db->join($this->table_events, "$this->table_events.instantiations_id	 = $this->table_instantiations.id", 'left');
		$this->db->join($this->table_event_types, "$this->table_event_types.id	 = $this->table_events.event_types_id", 'left');
		$this->db->limit(5);
		$this->db->group_by("$this->_assets_table.id");
		$result = $this->db->get($this->table_instantiations);
		if (isset($result) && ! empty($result))
		{
			return $result->result();
		}
		return $result;
	}

	function get_nomination_status()
	{
		$this->db->order_by("status");
		return $query = $this->db->get($this->table_nomination_status)->result();
	}

	function get_media_types()
	{
		$this->db->order_by("media_type");
		return $query = $this->db->get($this->table_instantiation_media_types)->result();
	}

	function get_physical_formats()
	{
		$this->db->order_by("format_name");
		$this->db->where('format_type ', 'physical');
		$this->db->group_by('format_name');
		return $query = $this->db->get($this->table_instantiation_formats)->result();
	}

	function get_digital_formats()
	{
		$this->db->order_by("format_name");
		$this->db->where('format_type ', 'digital');
		$this->db->group_by('format_name');
		return $query = $this->db->get($this->table_instantiation_formats)->result();
	}

	function get_generations()
	{
		$this->db->order_by("generation");
		$this->db->group_by('generation');
		return $query = $this->db->get($this->table_generations)->result();
	}

	function get_file_size()
	{
		$this->db->select("file_size");
		$this->db->where('file_size !=', 'NULL');
		$this->db->order_by("file_size");
		$this->db->distinct();
		$result = $this->db->get($this->table_instantiations)->result();
		return $result;
	}

	function get_event_type()
	{
		$this->db->order_by("event_type");
		$this->db->group_by('event_type');
		return $query = $this->db->get($this->table_event_types)->result();
	}

	function get_event_outcome()
	{
		$this->db->select("CASE WHEN event_outcome=0 THEN 'FAIL' WHEN event_outcome=1 THEN 'PASS' END AS event_outcome", FALSE);
		$this->db->order_by("event_outcome");
		$query = $this->db->get($this->table_events)->result();
		return $query;
	}

	/**
	 * 
	 * @param Integer $instantiation_id
	 * @return boolean , Object
	 */
	function get_events_by_instantiation_id($instantiation_id)
	{
		$this->db->select("$this->table_event_types.event_type,$this->table_events.id,$this->table_events.instantiations_id,$this->table_events.event_types_id,$this->table_events.event_date,CASE WHEN $this->table_events.event_outcome=0 THEN 'FAIL' WHEN $this->table_events.event_outcome=1 THEN 'PASS' END AS event_outcome,$this->table_events.event_note", FALSE);
		$this->db->join($this->table_event_types, "$this->table_event_types.id	 = $this->table_events.event_types_id", 'left');
		$this->db->where('instantiations_id', $instantiation_id);
		$query = $this->db->get($this->table_events);
		if (isset($query) && ! is_empty($query))
		{
			return $query->result();
		}
		return false;
	}

	/**
	 * search generations by @generation
	 * 
	 * @param type $status
	 * @return object 
	 */
	function get_generations_by_generation($generation)
	{
		$sql = 'SELECT * FROM `' . $this->table_generations . '` WHERE `generation` LIKE CONVERT( _utf8 "' . $generation . '" USING latin1 )
																COLLATE latin1_swedish_ci';
		$res = $this->db->query($sql);
		if (isset($res) && ! empty($res))
		{
			return $res->row();
		}
		return false;
	}

	/**
	 * search instantiation event exists 
	 * 
	 * @param type array of different event fields
	 * @param $instantiation_id
	 * @return bool
	 */
	function is_event_exists($instantiation_id, $event_types_id)
	{
		$this->db->where('instantiations_id', $instantiation_id);
		$this->db->where('event_types_id', $event_types_id);
		$res = $this->db->get($this->table_events);
		if (isset($res) && ! empty($res))
			return $res->row();
		return false;
	}

	/**
	 * search instantiation by @guid and $physical_format
	 * 
	 * @param type $guid
	 * @param type $physical_format
	 * @return object 
	 */
	function get_instantiation_by_guid_physical_format($guid, $physical_format)
	{
		$sql = 'SELECT ins.id,IFNULL(gen.generation,"")  FROM instantiations AS ins 
				LEFT JOIN identifiers AS ide ON  ins.assets_id=ide.assets_id
				LEFT JOIN instantiation_formats AS inf ON  ins.id=inf.instantiations_id
				LEFT JOIN instantiation_generations AS ing ON  ins.id=ing.instantiations_id
				LEFT JOIN generations AS gen ON ing.generations_id=gen.id
				WHERE ide.identifier LIKE "' . $guid . '" AND inf.format_name LIKE "' . $physical_format . '" AND inf.format_type="physical"';
		$res = $this->db->query($sql);
		if (isset($res) && ! empty($res))
		{
			return $res->row();
		}
		return false;
	}

	/**
	 * search event_type id by @event_type
	 * 
	 * @param type $event_type
	 * @return object 
	 */
	function get_id_by_event_type($event_type)
	{
		$this->db->where('event_type LIKE', $event_type);
		$res = $this->db->get($this->table_event_types);
		if (isset($res) && ! empty($res))
		{
			return $res->row();
		}
		return false;
	}

	/**
	 * search instantiation_colors by @color
	 * 
	 * @param type $status
	 * @return object 
	 */
	function get_instantiation_colors_by_color($color)
	{
		$this->db->where('color LIKE', $color);
		$res = $this->db->get($this->table_instantiation_colors);
		if (isset($res) && ! empty($res))
		{
			return $res->row();
		}
		return false;
	}

	/**
	 * search data_rate_units by @unit
	 * 
	 * @param type $status
	 * @return object 
	 */
	function get_data_rate_units_by_unit($unit_of_measure)
	{
		$this->db->where('unit_of_measure LIKE', $unit_of_measure);
		$res = $this->db->get($this->table_data_rate_units);
		if (isset($res) && ! empty($res))
		{
			return $res->row();
		}
		return false;
	}

	/**
	 * search instantiation_media_types by @media_type
	 * 
	 * @param type $status
	 * @return object 
	 */
	function get_instantiation_media_types_by_media_type($media_type)
	{
		$this->db->where('media_type LIKE', $media_type);
		$res = $this->db->get($this->table_instantiation_media_types);
		if (isset($res) && ! empty($res))
		{
			return $res->row();
		}
		return false;
	}

	/**
	 * search date_types by @date_type
	 * 
	 * @param type $status
	 * @return object 
	 */
	function get_date_types_by_type($date_type)
	{
		$this->db->where('date_type LIKE', $date_type);
		$res = $this->db->get($this->table_date_types);
		if (isset($res) && ! empty($res))
		{
			return $res->row();
		}
		return false;
	}

	/*
	 *
	 *  Insert the record in data_rate_units table
	 *  @param array $data
	 *  @return integer last_inserted id
	 * 
	 */

	function insert_data_rate_units($data)
	{
		$this->db->insert($this->table_data_rate_units, $data);
		return $this->db->insert_id();
	}

	/*
	 *
	 *  Insert the record in generations table
	 *  @param array $data
	 *  @return integer last_inserted id
	 * 
	 */

	function insert_generations($data)
	{
		$this->db->insert($this->table_generations, $data);
		return $this->db->insert_id();
	}

	/*
	 *
	 *  Insert the record in instantiation_generations table
	 *  @param array $data
	 *  @return integer last_inserted id
	 * 
	 */

	function insert_instantiation_generations($data)
	{
		$this->db->insert($this->table_instantiation_generations, $data);
		return $this->db->insert_id();
	}

	/*
	 *
	 *  Insert the record in instantiation_media_types table
	 *  @param array $data
	 *  @return integer last_inserted id
	 * 
	 */

	function insert_instantiation_media_types($data)
	{
		$this->db->insert($this->table_instantiation_media_types, $data);
		return $this->db->insert_id();
	}

	/*
	 *
	 *  Insert the record in instantiation_formats table
	 *  @param array $data
	 *  @return integer last_inserted id
	 * 
	 */

	function insert_instantiation_formats($data)
	{
		$this->db->insert($this->table_instantiation_formats, $data);
		return $this->db->insert_id();
	}

	function update_instantiation_formats($p_id, $data)
	{
		$this->db->where('id', $p_id);
		$this->db->update($this->table_instantiation_formats, $data);
		return $this->db->insert_id();
	}

	/*
	 *
	 *  Insert the record in instantiation_dimensions table
	 *  @param array $data
	 *  @return integer last_inserted id
	 * 
	 */

	function insert_instantiation_dimensions($data)
	{
		$this->db->insert($this->table_instantiation_dimensions, $data);
		return $this->db->insert_id();
	}

	/*
	 *
	 *  Insert the record in instantiations table
	 *  @param array $data
	 *  @return integer last_inserted id
	 * 
	 */

	function insert_instantiations($data)
	{
		$data['created'] = date('Y-m-d H:i:s');
		$this->db->insert($this->table_instantiations, $data);
		return $this->db->insert_id();
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

	/*
	 *
	 *  Insert the record in instantiation_identifier table
	 *  @param array $data
	 *  @return integer last_inserted id
	 * 
	 */

	function insert_instantiation_identifier($data)
	{
		$this->db->insert($this->table_instantiation_identifier, $data);
		return $this->db->insert_id();
	}

	function update_instantiation_identifier($instantiation_id, $data)
	{
		$this->db->where('instantiations_id', $instantiation_id);
		return $this->db->update($this->table_instantiation_identifier, $data);
	}

	function update_instantiation_identifier_by_id($identifier_id, $data)
	{
		$this->db->where('id', $identifier_id);
		return $this->db->update($this->table_instantiation_identifier, $data);
	}

	/*
	 *
	 *  Insert the record in instantiation_dates table
	 *  @param array $data
	 *  @return integer last_inserted id
	 * 
	 */

	function insert_instantiation_dates($data)
	{
		$this->db->insert($this->table_instantiation_dates, $data);
		return $this->db->insert_id();
	}

	function update_instantiation_date($inst_id, $data)
	{
		$this->db->where('id', $inst_id);
		$this->db->update($this->table_instantiation_dates, $data);
		return $this->db->insert_id();
	}

	/*
	 *
	 *  Insert the record in date_types table
	 *  @param array $data
	 *  @return integer last_inserted id
	 * 
	 */

	function insert_date_types($data)
	{
		$this->db->insert($this->table_date_types, $data);
		return $this->db->insert_id();
	}

	/*
	 *
	 *  Insert the record in instantiation_colors table
	 *  @param array $data
	 *  @return integer last_inserted id
	 * 
	 */

	function insert_instantiation_colors($data)
	{
		$this->db->insert($this->table_instantiation_colors, $data);
		return $this->db->insert_id();
	}

	/*
	 *
	 *  Insert the record in instantiation_annotations table
	 *  @param array $data
	 *  @return integer last_inserted id
	 * 
	 */

	function insert_instantiation_annotations($data)
	{
		$this->db->insert($this->table_instantiation_annotations, $data);
		return $this->db->insert_id();
	}

	/*
	 *
	 *  Insert the record in table_events table
	 *  @param array $data
	 *  @return integer last_inserted id
	 * 
	 */

	function insert_event($data)
	{
		$this->db->insert($this->table_events, $data);
		return $this->db->insert_id();
	}

	/**
	 * update the events record
	 * 
	 * @param type $id
	 * @param array $data
	 * @return boolean 
	 */
	function update_event($id, $data)
	{
		$this->db->where('id', $id);
		return $this->db->update($this->table_events, $data);
	}

	/*
	 *
	 *  Insert the record in table_event_types table
	 *  @param array $data
	 *  @return integer last_inserted id
	 * 
	 */

	function insert_event_types($data)
	{
		$this->db->insert($this->table_event_types, $data);
		return $this->db->insert_id();
	}

	/**
	 * Insert or update event 
	 * 
	 * @param Integer $instantiation_id use to match instantiation
	 * @param Integer $event_types_id   use to event_types_id
	 * @param Array   $event_data       use to insert event type
	 * 
	 * @return helper
	 */
	function _insert_or_update_event($instantiation_id, $event_types_id, $event_data)
	{
		$is_exists = $this->is_event_exists($instantiation_id, $event_types_id);
		if ($is_exists)
		{
			echo '<strong><br/>Event migration already Exists against Instantiation Id: ' . $instantiation_id . '</strong><br/>';
			print_r($event_data);
			$this->update_event($is_exists->id, $event_data);
		}
		else
		{
			echo '<strong><br/>New migration event against Instantiation Id: ' . $instantiation_id . '</strong><br/>';
			print_r($event_data);
			$this->insert_event($event_data);
		}
	}

	/**
	 * Get event type Id on event_type base else store event  
	 * 
	 * @param string $event_type use to check/store event types
	 * 
	 * @return integer event_types_id
	 */
	function _get_event_type($event_type)
	{
		$event_type_data = $this->get_id_by_event_type($event_type);
		if ($event_type_data)
		{
			$event_types_id = $event_type_data->id;
		}
		else
		{
			$event_types_id = $this->insert_event_types(array(
				'event_type' => $event_type
			));
		}
		return $event_types_id;
	}

	function export_limited_csv($real_time = FALSE)
	{
		$this->db->select("identifiers.identifier as GUID", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT local.identifier SEPARATOR ' | ') AS unique_id", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT $this->asset_titles.title SEPARATOR ' | ') as titles", FALSE);
		$this->db->select("$this->table_instantiation_formats.format_name", FALSE);
		$this->db->select("$this->table_instantiations.projected_duration", FALSE);
		$this->db->select("$this->table_nomination_status.status", FALSE);

		$this->db->join($this->_assets_table, "$this->_assets_table.id = $this->table_instantiations.assets_id", 'left');
		$this->db->join("identifiers AS local", "$this->_assets_table.id = local.assets_id AND local.identifier_source!='http://americanarchiveinventory.org'", 'left');
		$this->db->join("identifiers", "$this->_assets_table.id = identifiers.assets_id AND identifiers.identifier_source='http://americanarchiveinventory.org'", 'left');
		$this->db->join($this->table_instantiation_formats, "$this->table_instantiation_formats.instantiations_id = $this->table_instantiations.id", 'left');
		$this->db->join($this->table_nominations, "$this->table_nominations.instantiations_id = $this->table_instantiations.id", 'left');
		$this->db->join($this->table_nomination_status, "$this->table_nomination_status.id = $this->table_nominations.nomination_status_id", 'left');
		$this->db->join('assets_subjects', "assets_subjects.assets_id = assets.id", 'left');
		$this->db->join('subjects', "subjects.id = assets_subjects.subjects_id", 'left');
		$this->db->join('coverages', "coverages.assets_id = assets.id", 'left');
		$this->db->join('assets_genres', "assets_genres.assets_id = assets.id", 'left');
		$this->db->join('genres', "genres.id = assets_genres.genres_id", 'left');
		$this->db->join('assets_publishers_role', "assets_publishers_role.assets_id = assets.id", 'left');
		$this->db->join('publisher_roles', "assets_publishers_role.publisher_roles_id = publisher_roles.id", 'left');
		$this->db->join('publishers', "assets_publishers_role.publishers_id = publishers.id", 'left');
		$this->db->join('asset_descriptions', "asset_descriptions.assets_id = assets.id", 'left');
		$this->db->join('description_types', "description_types.id = asset_descriptions.description_types_id", 'left');
		$this->db->join('assets_creators_roles', "assets_creators_roles.assets_id = assets.id", 'left');
		$this->db->join('creator_roles', "assets_creators_roles.creator_roles_id = creator_roles.id", 'left');
		$this->db->join('creators', "assets_creators_roles.creators_id = creators.id", 'left');
		$this->db->join('assets_contributors_roles', "assets_contributors_roles.assets_id = assets.id", 'left');
		$this->db->join('contributor_roles', "assets_contributors_roles.contributor_roles_id = contributor_roles.id", 'left');
		$this->db->join('contributors', "assets_contributors_roles.contributors_id = contributors.id", 'left');
		$this->db->join('instantiation_identifier', "instantiations.id = instantiation_identifier.instantiations_id", 'left');
		$this->db->join('instantiation_dimensions', "instantiation_dimensions.instantiations_id = instantiations.id", 'left');
		$this->db->join('essence_tracks', "essence_tracks.instantiations_id = instantiations.id", 'left');
		$this->db->join('essence_track_encodings', "essence_track_encodings.essence_tracks_id = essence_tracks.id", 'left');
		$this->db->join('essence_track_frame_sizes', "essence_track_frame_sizes.id = essence_tracks.essence_track_frame_sizes_id", 'left');
		$this->db->join('essence_track_annotations', "essence_track_annotations.essence_tracks_id = essence_tracks.id", 'left');
		$this->db->join('instantiation_annotations', "instantiation_annotations.instantiations_id = instantiations.id", 'left');
		$this->db->join($this->asset_titles, "$this->asset_titles.assets_id	 = $this->table_instantiations.assets_id", 'left');
		$this->db->join($this->stations, "$this->stations.id = $this->_assets_table.stations_id", 'left');
		$this->db->join($this->table_instantiation_dates, "$this->table_instantiation_dates.instantiations_id = $this->table_instantiations.id", 'left');
		$this->db->join($this->table_date_types, "$this->table_date_types.id = $this->table_instantiation_dates.date_types_id", 'left');
		$this->db->join($this->table_instantiation_media_types, "$this->table_instantiation_media_types.id = $this->table_instantiations.instantiation_media_type_id", 'left');
		$this->db->join($this->table_instantiation_generations, "$this->table_instantiation_generations.instantiations_id = $this->table_instantiations.id", 'left');
		$this->db->join($this->table_generations, "$this->table_generations.id = $this->table_instantiation_generations.generations_id", 'left');
		$this->db->join($this->table_events, "$this->table_events.instantiations_id	 = $this->table_instantiations.id", 'left');
		$this->db->join($this->table_event_types, "$this->table_event_types.id	 = $this->table_events.event_types_id", 'left');

		$session = $this->session->userdata;
		if (isset($session['organization']) && $session['organization'] != '')
		{
			$station_name = explode('|||', trim($session['organization']));
			$this->db->where_in("$this->stations.station_name", $station_name);
		}
		if (isset($session['nomination']) && $session['nomination'] != '')
		{
			$nomination = explode('|||', trim($session['nomination']));
			$this->db->where_in("$this->table_nomination_status.status", $nomination);
		}
		if (isset($session['media_type']) && $session['media_type'] != '')
		{
			$media_type = explode('|||', trim($session['media_type']));
			$this->db->where_in("$this->table_instantiation_media_types.media_type", $media_type);
		}
		if (isset($session['physical_format']) && $session['physical_format'] != '')
		{
			$physical_format = explode('|||', trim($session['physical_format']));
			$this->db->where_in("$this->table_instantiation_formats.format_name", $physical_format);
		}
		if (isset($session['digital_format']) && $session['digital_format'] != '')
		{
			$digital_format = explode('|||', trim($session['digital_format']));
			$this->db->where_in("$this->table_instantiation_formats.format_name", $digital_format);
		}
		if (isset($session['generation']) && $session['generation'] != '')
		{
			$generation = explode('|||', trim($session['generation']));
			$this->db->where_in("$this->table_generations.generation", $generation);
		}
		if (isset($session['digitized']) && $session['digitized'] === '1')
		{
			$this->db->where("$this->table_instantiations.digitized", '1');
			$this->db->where("$this->table_instantiations.actual_duration IS NULL");
		}
		if (isset($session['migration_failed']) && $session['migration_failed'] === '1')
		{

			$this->db->where("$this->table_event_types.event_type", 'migration');
			$this->db->where("$this->table_events.event_outcome", '0');
		}

		if (isset($session['custom_search']) && $session['custom_search'] != '')
		{
			$facet_columns = array(
				'guid_identifier' => 'identifiers.identifier',
				'asset_title' => 'asset_titles.title',
				'asset_subject' => 'subjects.subject',
				'asset_coverage' => 'coverages.coverage',
				'asset_genre' => 'genres.genre',
				'asset_publisher_name' => 'publishers.publisher',
				'asset_description' => 'description_types.description_type',
				'asset_creator_name' => 'creators.creator_name',
				'asset_creator_affiliation' => 'creators.creator_affiliation',
				'asset_contributor_name' => 'contributors.contributor_name',
				'asset_contributor_affiliation' => 'contributors.contributor_affiliation',
				'instantiation_identifier' => 'instantiation_identifier.instantiation_identifier',
				'instantiation_source' => 'instantiation_identifier.instantiation_source',
				'instantiation_dimension' => 'instantiation_dimensions.instantiation_dimension',
				'unit_of_measure' => 'instantiation_dimensions.unit_of_measure',
				'standard' => 'instantiations.standard',
				'location' => 'instantiations.location',
				'file_size' => 'instantiations.file_size',
				'actual_duration' => 'instantiations.actual_duration',
				'track_duration' => 'essence_tracks.duration',
				'data_rate' => 'instantiations.data_rate',
				'track_data_rate' => 'essence_tracks.data_rate',
				'tracks' => 'instantiations.tracks',
				'channel_configuration' => 'instantiations.channel_configuration',
				'language' => 'instantiations.language',
				'track_language' => 'essence_tracks.language',
				'alternative_modes' => 'instantiations.alternative_modes',
				'ins_annotation' => 'instantiation_annotations.annotation',
				'track_annotation' => 'essence_track_annotations.annotation',
				'ins_annotation_type' => 'instantiation_annotations.annotation_type',
				'track_essence_track_type' => 'essence_track_annotations.annotation_type',
				'track_encoding' => 'essence_track_encodings.encoding',
				'track_standard' => 'essence_tracks.standard',
				'track_frame_rate' => 'essence_tracks.frame_rate',
				'track_playback_speed' => 'essence_tracks.playback_speed',
				'track_sampling_rate' => 'essence_tracks.sampling_rate',
				'track_bit_depth' => 'essence_tracks.bit_depth',
				'track_width' => 'essence_track_frame_sizes.width',
				'track_height' => 'essence_track_frame_sizes.height',
				'track_aspect_ratio' => 'essence_tracks.aspect_ratio',
			);

			$keyword_json = $session['custom_search'];
			foreach ($keyword_json as $index => $key_columns)
			{
				$count = 0;
				foreach ($key_columns as $keys => $keywords)
				{
					$keyword = trim($keywords->value);
					if ($index == 'all')
					{

						foreach ($facet_columns as $column)
						{
							if ($count == 0)
								$this->db->like($column, $keyword);
							else
								$this->db->or_like($column, $keyword);
							$count ++;
						}
					}
					else
					{
						if ($count == 0)
							$this->db->like($index, $keyword);
						else
							$this->db->or_like($index, $keyword);
					}
					$count ++;
				}
			}
		}
		if (isset($session['date_range']) && $session['date_range'] != '')
		{
			$keyword_json = $this->session->userdata['date_range'];
			foreach ($keyword_json as $index => $key_columns)
			{
				$count = 0;
				foreach ($key_columns as $keys => $keywords)
				{

					$date_range = explode("to", $keywords->value);
					if (isset($date_range[0]) && trim($date_range[0]) != '')
					{
						$start_date = strtotime(trim($date_range[0]));
					}
					if (isset($date_range[1]) && trim($date_range[1]) != '')
					{
						$end_date = strtotime(trim($date_range[1]));
					}
					if ($start_date != '' && is_numeric($start_date) && isset($end_date) && is_numeric($end_date) && $end_date >= $start_date)
					{
						if ($count == 0)
						{
							$this->db->where("$this->table_instantiation_dates.$this->table_instantiation_dates >=", $start_date);
							$this->db->where("$this->table_instantiation_dates.$this->table_instantiation_dates <=", $end_date);
						}
						else
						{
							$this->db->or_where("$this->table_instantiation_dates.$this->table_instantiation_dates >=", $start_date);
							$this->db->or_where("$this->table_instantiation_dates.$this->table_instantiation_dates <=", $end_date);
						}


						if ($index != 'All')
						{
							$this->db->where_in("$this->table_date_types.date_type", $index);
						}
					}
					$count ++;
				}
			}
		}

		if ($this->is_station_user)
		{
			$this->db->where_in("$this->stations.station_name", $this->station_name);
		}

		$query = $this->db->group_by("$this->table_instantiations.id");
		$this->db->from($this->table_instantiations);
		if ($real_time)
		{
			return $this->db->return_query();
		}
		$result = $this->db->get();

		if (isset($result) && ! empty($result))
		{

			return $result->result();
		}
		return false;
	}

	function get_nomination_by_instantiation_id($ins_id)
	{
		$this->db->select("$this->table_nominations.*,$this->table_nomination_status.status,user_profile.first_name,user_profile.last_name", FALSE);
		$this->db->where("$this->table_nominations.instantiations_id", $ins_id);
		$this->db->join($this->table_nomination_status, "$this->table_nomination_status.id = $this->table_nominations.nomination_status_id", 'left');
		$this->db->join('user_profile', "user_profile.user_id = $this->table_nominations.nominated_by", 'left');
		return $result = $this->db->get($this->table_nominations)->row();
	}

	function get_identifier_by_instantiation_id($ins_id)
	{
		$this->db->select("$this->table_instantiation_identifier.instantiation_identifier", FALSE);
		$this->db->select("$this->table_instantiation_identifier.instantiation_source", FALSE);
		$this->db->where("$this->table_instantiation_identifier.instantiations_id", $ins_id);

		return $this->db->get($this->table_instantiation_identifier)->result();
	}

	function get_dates_by_instantiation_id($ins_id)
	{
		$this->db->select("$this->table_instantiation_dates.instantiation_date", FALSE);
		$this->db->select("$this->table_date_types.date_type", FALSE);
		$this->db->where("$this->table_instantiation_dates.instantiations_id", $ins_id);
		$this->db->join($this->table_date_types, "$this->table_date_types.id = $this->table_instantiation_dates.date_types_id", 'left');
		return $this->db->get($this->table_instantiation_dates)->result();
	}

	function get_media_type_by_instantiation_media_id($media_id)
	{
		$this->db->select("$this->table_instantiation_media_types.media_type", FALSE);
		$this->db->where("$this->table_instantiation_media_types.id", $media_id);
		return $result = $this->db->get($this->table_instantiation_media_types)->row();
	}

	function get_format_by_instantiation_id($ins_id)
	{
		$this->db->select("$this->table_instantiation_formats.id", FALSE);
		$this->db->select("$this->table_instantiation_formats.format_name,$this->table_instantiation_formats.format_type", FALSE);
		$this->db->where("$this->table_instantiation_formats.instantiations_id", $ins_id);
		return $result = $this->db->get($this->table_instantiation_formats)->row();
	}

	function get_generation_by_instantiation_id($ins_id)
	{
		$this->db->select("GROUP_CONCAT(DISTINCT $this->table_generations.generation SEPARATOR ' | ') AS generation", FALSE);
		$this->db->where("$this->table_instantiation_generations.instantiations_id", $ins_id);
		$this->db->join($this->table_generations, "$this->table_generations.id = $this->table_instantiation_generations.generations_id", 'left');

		return $result = $this->db->get($this->table_instantiation_generations)->row();
	}

	function get_demension_by_instantiation_id($ins_id)
	{
		$this->db->select("$this->table_instantiation_dimensions.instantiation_dimension,$this->table_instantiation_dimensions.unit_of_measure", FALSE);
		$this->db->where("$this->table_instantiation_dimensions.instantiations_id", $ins_id);
		return $this->db->get($this->table_instantiation_dimensions)->result();
	}

	function get_data_rate_unit_by_data_id($data_rate_id)
	{
		$this->db->select("$this->table_data_rate_units.unit_of_measure", FALSE);
		$this->db->where("$this->table_data_rate_units.id", $data_rate_id);
		return $result = $this->db->get($this->table_data_rate_units)->row();
	}

	function get_color_by_instantiation_colors_id($color_id)
	{
		$this->db->select("$this->table_instantiation_colors.color", FALSE);
		$this->db->where("$this->table_instantiation_colors.id", $color_id);
		return $result = $this->db->get($this->table_instantiation_colors)->row();
	}

	function get_annotation_by_instantiation_id($ins_id)
	{
		$this->db->select("$this->table_instantiation_annotations.annotation", FALSE);
		$this->db->select("$this->table_instantiation_annotations.annotation_type", FALSE);
		$this->db->where("$this->table_instantiation_annotations.instantiations_id", $ins_id);
		return $this->db->get($this->table_instantiation_annotations)->result();
	}

	function get_instantiations_by_asset_id($asset_id)
	{
		$this->db->select("$this->table_instantiations.id,$this->table_instantiations.actual_duration,$this->table_instantiations.projected_duration", FALSE);
		$this->db->select("$this->table_instantiation_formats.format_name,$this->table_instantiation_formats.format_type", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT $this->table_generations.generation SEPARATOR ' | ') AS generation", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL($this->table_instantiation_identifier.instantiation_identifier,'(**)')) SEPARATOR ' | ') AS instantiation_identifier", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL($this->table_instantiation_identifier.instantiation_source,'(**)')) SEPARATOR ' | ') AS instantiation_source", FALSE);
		$this->db->where("$this->table_instantiations.assets_id", $asset_id);
		$this->db->join($this->table_instantiation_formats, "$this->table_instantiation_formats.instantiations_id = $this->table_instantiations.id", 'left');
		$this->db->join($this->table_instantiation_generations, "$this->table_instantiation_generations.instantiations_id = $this->table_instantiations.id", 'left');
		$this->db->join($this->table_generations, "$this->table_generations.id = $this->table_instantiation_generations.generations_id", 'left');
		$this->db->join($this->table_instantiation_identifier, "$this->table_instantiation_identifier.instantiations_id = $this->table_instantiations.id", 'left');
		$this->db->group_by("$this->table_instantiations.id");
		return $result = $this->db->get($this->table_instantiations)->result();
	}

	function get_instantiation_by_asset_id($asset_id)
	{
		$this->db->where('assets_id', $asset_id);
		return $this->db->get($this->table_instantiations)->result();
	}

	function get_instantiation_with_event_by_asset_id($asset_id)
	{
		$this->db->select("$this->table_instantiations.id");
		$this->db->select("$this->table_events.event_types_id");
		$this->db->where("$this->table_instantiations.assets_id", $asset_id);
		$this->db->join($this->table_events, "$this->table_events.instantiations_id=$this->table_instantiations.id");

		return $this->db->get($this->table_instantiations)->row();
	}

}

?>