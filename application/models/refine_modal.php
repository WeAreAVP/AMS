<?php

/**
 * Refine Model
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
 * Refine  Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Model
 * @author     Nouman Tayyab <nouman@geekschicago.com>
 * @license    AMS http://ams.avpreserve.com
 * @link       http://ams.avpreserve.com
 */
class Refine_modal extends CI_Model
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

	function insert_job($data)
	{
		$data['updated_at'] = date('Y-m-d H:i:s');
		$data['created_at'] = date('Y-m-d H:i:s');
		$this->db->insert('google_refine', $data);
		return $this->db->insert_id();
	}

	function update_job($job_id, $data)
	{
		$data['updated_at'] = date('Y-m-d H:i:s');
		$this->db->where('id', $job_id);
		return $this->db->update('google_refine', $data);
	}

	function get_by_project_id($project_id)
	{
		$this->db->where('project_id', $project_id);
		return $this->db->get('google_refine')->row();
	}

	function get_csv_records($query)
	{
		return $this->db->query($query)->result();
	}

	function get_active_refine()
	{
		$this->db->select("CONCAT(user_profile.first_name,' ', user_profile.last_name) AS name,google_refine.user_id,google_refine.project_id,google_refine.is_active", FALSE);
		$this->db->where_in('google_refine.is_active', array(1, 2));
		$this->db->join('user_profile', 'user_profile.user_id=google_refine.user_id');
		return $this->db->get('google_refine')->row();
	}

	function refine_update_records()
	{
		$this->db->where('google_refine.is_active', 2);
		return $this->db->get('google_refine')->row();
	}

	function get_job_for_refine()
	{
		$this->db->where('google_refine.is_active', 1);
		$this->db->where('google_refine.project_id IS NULL');
		return $this->db->get('google_refine')->row();
	}

	function export_refine_csv($real_time = FALSE)
	{

		$this->db->select("$this->stations.station_name as organization", FALSE);
		$this->db->select("$this->asset_titles.title as asset_title", FALSE);
		$this->db->select("asset_descriptions.description", FALSE);
		$this->db->select("instantiation_identifier.instantiation_identifier", FALSE);
		$this->db->select("instantiation_identifier.instantiation_source", FALSE);
		$this->db->select("$this->table_generations.generation", FALSE);
		$this->db->select("$this->table_nomination_status.status", FALSE);
		$this->db->select("$this->table_nominations.nomination_reason", FALSE);
		$this->db->select("$this->table_instantiation_media_types.media_type", FALSE);
		$this->db->select("$this->table_instantiations.language", FALSE);
		$this->db->select("$this->table_instantiations.id AS ins_id", FALSE);
		$this->db->select("instantiation_identifier.id AS identifier_id", FALSE);
		$this->db->select("$this->table_instantiation_generations.id AS gen_id", FALSE);



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
		$this->db->where_in("$this->table_instantiation_formats.format_type", 'physical');
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
//			$this->db->where("$this->table_instantiations.actual_duration IS NULL");
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
			$where='1=1 AND ( 1=1';
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
//							if ($count == 0)
//								$this->db->like($column, $keyword);
//							else
//								$this->db->or_like($column, $keyword);
//							$count ++;
							$where .=" OR $column LIKE '%$keyword%'";
						}
					}
					else
					{
//						if ($count == 0)
//							$this->db->like($index, $keyword);
//						else
//							$this->db->or_like($index, $keyword);
						$where .=" OR $index LIKE '%$keyword%'";
					}
					$count ++;
				}
			}
			$where .=' )';
			$this->db->where($where,NULL,FALSE);
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

//								$query	=	$this->db->group_by("$this->table_instantiations.id");
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

	function export_asset_refine_csv($real_time = FALSE)
	{

		$this->db->select("$this->stations.station_name as organization", FALSE);
		$this->db->select("$this->asset_titles.title as asset_title", FALSE);
		$this->db->select("asset_descriptions.description", FALSE);
		$this->db->select("subjects.subject,subjects.subject_source,subjects.subject_ref", FALSE);
		$this->db->select("genres.genre,genres.genre_source,genres.genre_ref", FALSE);
		$this->db->select("creators.creator_name,creators.creator_affiliation,creators.creator_source,creators.creator_ref", FALSE);
		$this->db->select("contributors.contributor_name,contributors.contributor_affiliation,contributors.contributor_source,contributors.contributor_ref", FALSE);
		$this->db->select("publishers.publisher,publishers.publisher_affiliation,publishers.publisher_ref", FALSE);
		$this->db->select("coverages.coverage,coverages.coverage_type", FALSE);
		$this->db->select("audience_levels.audience_level,audience_levels.audience_level_source,audience_levels.audience_level_ref", FALSE);
		$this->db->select("audience_ratings.audience_rating,audience_ratings.audience_rating_source,audience_ratings.audience_rating_ref", FALSE);
		$this->db->select("annotations.annotation,annotations.annotation_type,annotations.annotation_ref", FALSE);
		$this->db->select("rights_summaries.rights,rights_summaries.rights_link", FALSE);
		$this->db->select("asset_types.asset_type", FALSE);
		$this->db->select("identifiers.identifier,identifiers.identifier_source,identifiers.identifier_ref", FALSE);
		$this->db->select("asset_dates.asset_date", FALSE);
		$this->db->select("assets_subjects.subjects_id AS subject_id", FALSE);
		$this->db->select("assets_genres.genres_id AS genre_id", FALSE);
		$this->db->select("assets_creators_roles.creators_id AS creator_id", FALSE);
		$this->db->select("assets_contributors_roles.contributors_id AS contributor_id", FALSE);
		$this->db->select("assets_publishers_role.publishers_id AS publisher_id", FALSE);
		$this->db->select("coverages.id AS coverage_id", FALSE);
		$this->db->select("assets_audience_levels.audience_levels_id AS audience_levels_id", FALSE);
		$this->db->select("assets_audience_ratings.audience_ratings_id AS audience_ratings_id", FALSE);
		$this->db->select("annotations.id AS annotation_id", FALSE);
		$this->db->select("rights_summaries.id AS right_id", FALSE);
		$this->db->select("assets_asset_types.asset_types_id AS asset_types_id", FALSE);
		$this->db->select("identifiers.id AS identifier_id", FALSE);
		$this->db->select("asset_dates.id AS asset_date_id", FALSE);
		$this->db->select("$this->_assets_table.id AS asset_id", FALSE);



		$this->db->join($this->table_instantiations, "$this->table_instantiations.assets_id = $this->_assets_table.id", 'left');
		$this->db->join("identifiers", "$this->_assets_table.id = identifiers.assets_id AND identifiers.identifier_source!='http://americanarchiveinventory.org'", 'left');
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
		$this->db->join('annotations', "annotations.assets_id = assets.id", 'left');
		$this->db->join('rights_summaries', "rights_summaries.assets_id = assets.id", 'left');
		$this->db->join('assets_asset_types', "assets_asset_types.assets_id = assets.id", 'left');
		$this->db->join('asset_types', "asset_types.id = assets_asset_types.asset_types_id", 'left');
		$this->db->join('asset_dates', "asset_dates.assets_id = assets.id", 'left');
		$this->db->join('assets_audience_levels', "assets_audience_levels.assets_id = assets.id", 'left');
		$this->db->join('audience_levels', "audience_levels.id = assets_audience_levels.audience_levels_id", 'left');
		$this->db->join('assets_audience_ratings', "assets_audience_ratings.assets_id = assets.id", 'left');
		$this->db->join('audience_ratings', "audience_ratings.id = assets_audience_ratings.audience_ratings_id", 'left');
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

//								$query	=	$this->db->group_by("$this->table_instantiations.id");
		$this->db->from($this->_assets_table);
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

	function get_instantiation_generation_by_id($id)
	{

		$this->db->where('id', $id);
		$result = $this->db->get($this->table_instantiation_generations);
		if (isset($result) && ! empty($result))
		{
			return $result->row();
		}
		return FALSE;
	}

	function update_instantiation_generation_by_id($id, $data)
	{
		$this->db->where('id', $id);
		return $this->db->update($this->table_instantiation_generations, $data);
	}

	function get_instantiation_idetifier_by_id($id)
	{
		$this->db->where('id', $id);
		$result = $this->db->get($this->table_instantiation_identifier);
		if (isset($result) && ! empty($result))
		{
			return $result->row();
		}
		return FALSE;
	}

	function update_instantiation_idetifier_by_id($id, $data)
	{
		$this->db->where('id', $id);
		return $this->db->update($this->table_instantiation_identifier, $data);
	}

	function update_asset_subject($asset_id, $old_subject_id, $data)
	{
		$this->db->where('assets_id', $asset_id);
		$this->db->where('subjects_id', $old_subject_id);
		return $this->db->update('assets_subjects', $data);
	}

	function update_asset_genre($asset_id, $old_genre_id, $data)
	{
		$this->db->where('assets_id', $asset_id);
		$this->db->where('genres_id', $old_genre_id);
		return $this->db->update('assets_genres', $data);
	}

	function update_creator_role($asset_id, $creator_id, $data)
	{
		$this->db->where('assets_id', $asset_id);
		$this->db->where('creators_id', $creator_id);
		return $this->db->update('assets_creators_roles', $data);
	}

	function update_contributer_role($asset_id, $contributer_id, $data)
	{
		$this->db->where('assets_id', $asset_id);
		$this->db->where('contributors_id', $contributer_id);
		return $this->db->update('assets_contributors_roles', $data);
	}

	function update_publisher_role($asset_id, $publisher_id, $data)
	{
		$this->db->where('assets_id', $asset_id);
		$this->db->where('publishers_id', $publisher_id);
		return $this->db->update('assets_publishers_role', $data);
	}

	function update_asset_coverage($asset_id, $asset_coverage_id, $data)
	{
		$this->db->where('assets_id', $asset_id);
		$this->db->where('id', $asset_coverage_id);
		return $this->db->update('coverages', $data);
	}

	function update_asset_audience_level($asset_id, $audience_level_id, $data)
	{
		$this->db->where('assets_id', $asset_id);
		$this->db->where('audience_levels_id', $audience_level_id);
		return $this->db->update('assets_audience_levels', $data);
	}

	function update_asset_audience_rating($asset_id, $audience_rating_id, $data)
	{
		$this->db->where('assets_id', $asset_id);
		$this->db->where('audience_ratings_id', $audience_rating_id);
		return $this->db->update('assets_audience_ratings', $data);
	}

	function update_annotation_type($asset_id, $annotation_id, $data)
	{
		$this->db->where('assets_id', $asset_id);
		$this->db->where('id', $annotation_id);
		return $this->db->update('annotations', $data);
	}

	function update_right_summary($asset_id, $right_id, $data)
	{
		$this->db->where('assets_id', $asset_id);
		$this->db->where('id', $right_id);
		return $this->db->update('rights_summaries', $data);
	}

	function update_asset_type($asset_id, $type_id, $data)
	{
		$this->db->where('assets_id', $asset_id);
		$this->db->where('asset_types_id', $type_id);
		return $this->db->update('assets_asset_types', $data);
	}

	function update_asset_identifier($asset_id, $identifier_id, $data)
	{
		$this->db->where('assets_id', $asset_id);
		$this->db->where('id', $identifier_id);
		return $this->db->update('identifiers', $data);
	}

	function update_asset_date($asset_id, $date_id, $data)
	{
		$this->db->where('assets_id', $asset_id);
		$this->db->where('id', $date_id);
		return $this->db->update('asset_dates', $data);
	}

}

?>