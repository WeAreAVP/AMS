<?php

/**
 * MY_Model Core
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
 * MY_Model Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Core
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @license    AMS http://ams.avpreserve.com
 * @link       http://ams.avpreserve.com
 */
class MY_Model extends CI_Model
{

	public $_prefix = '';
	public $table_date_types = 'date_types';
	public $table_generations = 'generations';
	public $table_instantiations = 'instantiations';
	public $table_relation_types = 'relation_types';
	public $table_data_rate_units = 'data_rate_units';
	public $table_instantiation_dates = 'instantiation_dates';
	public $table_instantiation_colors = 'instantiation_colors';
	public $table_instantiation_formats = 'instantiation_formats';
	public $table_instantiation_relations = 'instantiation_relations';
	public $table_instantiation_identifier = 'instantiation_identifier';
	public $table_instantiation_dimensions = 'instantiation_dimensions';
	public $table_instantiation_media_types = 'instantiation_media_types';
	public $table_instantiation_generations = 'instantiation_generations';
	public $table_instantiation_annotations = 'instantiation_annotations';
	public $_assets_table = 'assets';
	public $asset_titles = 'asset_titles';
	public $table_asset_title_types = 'asset_title_types';
	public $stations = 'stations';
	public $table_nominations = 'nominations';
	public $table_nomination_status = 'nomination_status';
	public $table_events = 'events';
	public $table_event_types = 'event_types';
	public $table_identifers = 'identifiers';
	public $table_asset_types = 'asset_types';
	public $table_assets_asset_types = 'assets_asset_types';
	public $table_asset_dates = 'asset_dates';
	public $table_assets_subjects = 'assets_subjects';
	public $table_subjects = 'subjects';
	public $table_subject_types = 'subject_types';
	public $table_asset_descriptions = 'asset_descriptions';
	public $table_description_types = 'description_types';
	public $table_assets_genres = 'assets_genres';
	public $table_genres = 'genres';
	public $table_coverages = 'coverages';
	public $table_assets_audience_levels = 'assets_audience_levels';
	public $table_audience_levels = 'audience_levels';
	public $table_assets_audience_ratings = 'assets_audience_ratings';
	public $table_audience_ratings = 'audience_ratings';
	public $table_annotations = 'annotations';
	public $table_assets_relations = 'assets_relations';
	public $table_assets_creators_roles = 'assets_creators_roles';
	public $table_creators = 'creators';
	public $table_creator_roles = 'creator_roles';
	public $table_assets_contributors_roles = 'assets_contributors_roles';
	public $table_contributors = 'contributors';
	public $table_contributor_roles = 'contributor_roles';
	public $table_assets_publishers_role = 'assets_publishers_role';
	public $table_publishers = 'publishers';
	public $table_publisher_roles = 'publisher_roles';
	public $table_rights_summaries = 'rights_summaries';
	public $table_extensions = 'extensions';
	public $table_essence_tracks = 'essence_tracks';
	public $table_essence_track_types = 'essence_track_types';
	public $table_essence_track_frame_sizes = 'essence_track_frame_sizes';
	public $table_essence_track_identifiers = 'essence_track_identifiers';
	public $table_essence_track_encodings = 'essence_track_encodings';
	public $table_essence_track_annotations = 'essence_track_annotations';

	function __construct()
	{
		parent::__construct();
	}

	/**
	 * Find single record by column of a table.
	 * 
	 * @param string $table
	 * @param array  $data
	 * 
	 * @return boolean
	 */
	function get_one_by($table, array $data, $use_like = FALSE)
	{
		foreach ($data as $column => $value)
		{
			if ($use_like)
				$this->db->like($column, $value);
			else
				$this->db->where($column, $value);
		}
		$result = $this->db->get($table);
		if (isset($result) && ! empty($result))
		{
			return $result->row();
		}
		return FALSE;
	}

	/**
	 * Find records by column of a table.
	 * 
	 * @param string $table
	 * @param array  $data
	 * 
	 * @return boolean
	 */
	function get_by($table, array $data)
	{
		foreach ($data as $column => $value)
		{
			$this->db->where($column, $value);
		}
		$result = $this->db->get($table);
		if (isset($result) && ! empty($result))
		{
			return $result->result();
		}
		return FALSE;
	}

	/**
	 * Insert data into table.
	 * 
	 * @param string $table
	 * @param array $data
	 * @return integer
	 */
	function insert($table, array $data)
	{
		$this->db->insert($table, $data);
		return $this->db->insert_id();
	}
	/**
	 * Insert data into table.
	 * 
	 * @param string $table
	 * @param array $data
	 * @return integer
	 */
	function insert_record($table, array $data)
	{
		$this->db->insert($table, $data);
		return $this->db->insert_id();
	}

}
