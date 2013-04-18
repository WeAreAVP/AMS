<?php

/**
 * Manage_Asset_Model Model
 * 
 * PHP version 5
 * 
 * @category   AMS
 * @package    CI
 * @subpackage Model
 * @author     Ali Raza <ali@geekschicago.com>
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @license    AVPS http://ams.avpreserve.com
 * @version    GIT: <$Id>
 * @link       http://ams.avpreserve.com
 */

/**
 * Manage_Asset_Model Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Model
 * @author     Ali Raza <ali@geekschicago.com.com>
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @version    GIT: <$Id>
 * @link       http://ams.avpreserve.com
 */
class Manage_Asset_Model extends CI_Model
{

	/**
	 * constructor. set table name amd prefix
	 * 
	 */
	function __construct()
	{
		parent::__construct();
	}

	function get_asset_detail_by_id($asset_id)
	{
		$this->db->select('assets.id,assets.stations_id,stations.station_name');
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(asset_types.asset_type,'(**)'))  SEPARATOR ' | ') AS asset_type", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(asset_dates.asset_date,'(**)'))  SEPARATOR ' | ') AS asset_date", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(date_types.date_type,'(**)'))  SEPARATOR ' | ') AS date_type", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(identifiers.identifier,'(**)'))  SEPARATOR ' | ') AS identifier", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(identifiers.identifier_source,'(**)'))  SEPARATOR ' | ') AS identifier_source", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(identifiers.identifier_ref,'(**)'))  SEPARATOR ' | ') AS identifier_ref", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(asset_titles.title,'(**)'))  SEPARATOR ' | ') AS title", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(asset_titles.title_source,'(**)'))  SEPARATOR ' | ') AS title_source", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(asset_titles.title_ref,'(**)'))  SEPARATOR ' | ') AS title_ref", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(asset_title_types.title_type,'(**)'))  SEPARATOR ' | ') AS title_type", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(subjects.subject,'(**)'))  SEPARATOR ' | ') AS subject", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(subjects.subject_source,'(**)'))  SEPARATOR ' | ') AS subject_source", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(subjects.subject_ref,'(**)'))  SEPARATOR ' | ') AS subject_ref", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(subject_types.subject_type,'(**)'))  SEPARATOR ' | ') AS subject_type", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(asset_descriptions.description,'(**)'))  SEPARATOR ' | ') AS description", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(description_types.description_type,'(**)'))  SEPARATOR ' | ') AS description_type", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(genres.genre,'(**)'))  SEPARATOR ' | ') AS genre", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(genres.genre_source,'(**)'))  SEPARATOR ' | ') AS genre_source", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(genres.genre_ref,'(**)'))  SEPARATOR ' | ') AS genre_ref", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(coverages.coverage,'(**)'))  SEPARATOR ' | ') AS coverage", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(coverages.coverage_type,'(**)'))  SEPARATOR ' | ') AS coverage_type", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(audience_levels.audience_level,'(**)'))  SEPARATOR ' | ') AS audience_level", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(audience_levels.audience_level_source,'(**)'))  SEPARATOR ' | ') AS audience_level_source", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(audience_levels.audience_level_ref,'(**)'))  SEPARATOR ' | ') AS audience_level_ref", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(audience_ratings.audience_rating,'(**)'))  SEPARATOR ' | ') AS audience_rating", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(audience_ratings.audience_rating_source,'(**)'))  SEPARATOR ' | ') AS audience_rating_source", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(audience_ratings.audience_rating_ref,'(**)'))  SEPARATOR ' | ') AS audience_rating_ref", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(annotations.annotation,'(**)'))  SEPARATOR ' | ') AS annotation", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(annotations.annotation_type,'(**)'))  SEPARATOR ' | ') AS annotation_type", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(annotations.annotation_ref,'(**)'))  SEPARATOR ' | ') AS annotation_ref", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(assets_relations.relation_identifier,'(**)'))  SEPARATOR ' | ') AS relation_identifier", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(relation_types.relation_type,'(**)'))  SEPARATOR ' | ') AS relation_type", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(relation_types.relation_type_source,'(**)'))  SEPARATOR ' | ') AS relation_type_source", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(relation_types.relation_type_ref,'(**)'))  SEPARATOR ' | ') AS relation_type_ref", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(creators.creator_name,'(**)'))  SEPARATOR ' | ') AS creator_name", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(creators.creator_affiliation,'(**)'))  SEPARATOR ' | ') AS creator_affiliation", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(creators.creator_ref,'(**)'))  SEPARATOR ' | ') AS creator_ref", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(creator_roles.creator_role,'(**)'))  SEPARATOR ' | ') AS creator_role", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(creator_roles.creator_role_source,'(**)'))  SEPARATOR ' | ') AS creator_role_source", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(creator_roles.creator_role_ref,'(**)'))  SEPARATOR ' | ') AS creator_role_ref", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(contributors.contributor_name,'(**)'))  SEPARATOR ' | ') AS contributor_name", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(contributors.contributor_affiliation,'(**)'))  SEPARATOR ' | ') AS contributor_affiliation", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(contributors.contributor_ref,'(**)'))  SEPARATOR ' | ') AS contributor_ref", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(contributor_roles.contributor_role,'(**)'))  SEPARATOR ' | ') AS contributor_role", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(contributor_roles.contributor_role_source,'(**)'))  SEPARATOR ' | ') AS contributor_role_source", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(contributor_roles.contributor_role_ref,'(**)'))  SEPARATOR ' | ') AS contributor_role_ref", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(publishers.publisher,'(**)'))  SEPARATOR ' | ') AS publisher", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(publishers.publisher_affiliation,'(**)'))  SEPARATOR ' | ') AS publisher_affiliation", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(publishers.publisher_ref,'(**)'))  SEPARATOR ' | ') AS publisher_ref", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(publisher_roles.publisher_role,'(**)'))  SEPARATOR ' | ') AS publisher_role", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(publisher_roles.publisher_role_source,'(**)'))  SEPARATOR ' | ') AS publisher_role_source", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(publisher_roles.publisher_role_ref,'(**)'))  SEPARATOR ' | ') AS publisher_role_ref", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(rights_summaries.rights,'(**)'))  SEPARATOR ' | ') AS rights", FALSE);
		$this->db->select("GROUP_CONCAT(DISTINCT(IFNULL(rights_summaries.rights_link,'(**)'))  SEPARATOR ' | ') AS rights_link", FALSE);


		$this->db->join('stations', 'stations.id=assets.stations_id');
		$this->db->join('assets_asset_types', 'assets_asset_types.assets_id=assets.id', 'LEFT');
		$this->db->join('asset_types', 'asset_types.id=assets_asset_types.asset_types_id', 'LEFT');
		$this->db->join('asset_dates', 'asset_dates.assets_id=assets.id', 'LEFT');
		$this->db->join('date_types', 'date_types.id=asset_dates.date_types_id', 'LEFT');
		$this->db->join('identifiers', 'identifiers.assets_id=assets.id AND identifiers.identifier_source != "http://americanarchiveinventory.org"', 'LEFT');
		$this->db->join('asset_titles', 'asset_titles.assets_id=assets.id', 'LEFT');
		$this->db->join('asset_title_types', 'asset_title_types.id=asset_titles.asset_title_types_id', 'LEFT');
		$this->db->join('assets_subjects', 'assets_subjects.assets_id=assets.id', 'LEFT');
		$this->db->join('subjects', 'subjects.id=assets_subjects.subjects_id', 'LEFT');
		$this->db->join('subject_types', 'subject_types.id=subjects.subjects_types_id', 'LEFT');
		$this->db->join('asset_descriptions', 'asset_descriptions.assets_id=assets.id', 'LEFT');
		$this->db->join('description_types', 'description_types.id=asset_descriptions.description_types_id', 'LEFT');
		$this->db->join('assets_genres', 'assets_genres.assets_id=assets.id', 'LEFT');
		$this->db->join('genres', 'genres.id=assets_genres.genres_id', 'LEFT');
		$this->db->join('coverages', 'coverages.assets_id=assets.id', 'LEFT');
		$this->db->join('assets_audience_levels', 'assets_audience_levels.assets_id=assets.id', 'LEFT');
		$this->db->join('audience_levels', 'audience_levels.id=assets_audience_levels.audience_levels_id', 'LEFT');
		$this->db->join('assets_audience_ratings', 'assets_audience_ratings.assets_id=assets.id', 'LEFT');
		$this->db->join('audience_ratings', 'audience_ratings.id=assets_audience_ratings.audience_ratings_id', 'LEFT');
		$this->db->join('annotations', 'annotations.assets_id=assets.id', 'LEFT');
		$this->db->join('assets_relations', 'assets_relations.assets_id=assets.id', 'LEFT');
		$this->db->join('relation_types', 'relation_types.id=assets_relations.relation_types_id', 'LEFT');
		$this->db->join('assets_creators_roles', 'assets_creators_roles.assets_id=assets.id', 'LEFT');
		$this->db->join('creators', 'creators.id=assets_creators_roles.creators_id', 'LEFT');
		$this->db->join('creator_roles', 'creator_roles.id=assets_creators_roles.creator_roles_id', 'LEFT');
		$this->db->join('assets_contributors_roles', 'assets_contributors_roles.assets_id=assets.id', 'LEFT');
		$this->db->join('contributors', 'contributors.id=assets_contributors_roles.contributors_id', 'LEFT');
		$this->db->join('contributor_roles', 'contributor_roles.id=assets_contributors_roles.contributor_roles_id', 'LEFT');
		$this->db->join('assets_publishers_role', 'assets_publishers_role.assets_id=assets.id', 'LEFT');
		$this->db->join('publishers', 'publishers.id=assets_publishers_role.publishers_id', 'LEFT');
		$this->db->join('publisher_roles', 'publisher_roles.id=assets_publishers_role.publisher_roles_id', 'LEFT');
		$this->db->join('rights_summaries', 'rights_summaries.assets_id=assets.id', 'LEFT');


		$this->db->where('assets.id', $asset_id);
		$result = $this->db->get('assets');
		if (isset($result) && ! empty($result))
		{
			return $result->row();
		}
		return FALSE;
	}

	function get_picklist_values($element_id)
	{
		$this->db->where('element_type_id', $element_id);
		$this->db->order_by('value');
		$result = $this->db->get('pbcore_picklist_value_by_type');
		return $result->result();
	}

	function get_subject_types()
	{

		$this->db->order_by('subject_type');
		$result = $this->db->get('subject_types');
		return $result->result();
	}

	function insert_picklist_value($data)
	{
		$this->db->insert('pbcore_picklist_value_by_type', $data);
		return $this->db->insert_id();
	}

	function delete_local_identifiers($asset_id)
	{
		$this->db->where('assets_id', $asset_id);
		$this->db->where("identifier_source !=", "http://americanarchiveinventory.org");
		$this->db->delete('identifiers');
		return $this->db->affected_rows() > 0;
	}

	

	function delete_row($delete_id, $table, $match_column)
	{
		$this->db->where($match_column, $delete_id);
		$this->db->delete($table);
		return $this->db->affected_rows() > 0;
	}

	function get_identifier_by_instantiation_id($ins_id)
	{
		$this->db->select('instantiation_identifier.id');
		$this->db->select('instantiation_identifier.instantiation_source');
		$this->db->select('instantiation_identifier.instantiation_identifier');
		$this->db->where('instantiation_identifier.instantiations_id', $ins_id);

		return $result = $this->db->get('instantiation_identifier')->result();
	}

	function get_dates_by_instantiation_id($ins_id)
	{
		$this->db->select('instantiation_dates.id');
		$this->db->select('instantiation_dates.instantiation_date');
		$this->db->select('date_types.date_type');
		$this->db->where('instantiation_dates.instantiations_id', $ins_id);
		$this->db->join('date_types', 'date_types.id = instantiation_dates.date_types_id', 'left');
		$result = $this->db->get('instantiation_dates');
		if (isset($result) && ! empty($result))
			return $result->row();
		return FALSE;
	}

	function get_demension_by_instantiation_id($ins_id)
	{
		$this->db->select('instantiation_dimensions.instantiation_dimension,instantiation_dimensions.unit_of_measure');
		$this->db->where('instantiation_dimensions.instantiations_id', $ins_id);
		return $result = $this->db->get('instantiation_dimensions')->result();
	}

	function get_annotation_by_instantiation_id($ins_id)
	{
		$this->db->select('instantiation_annotations.annotation');
		$this->db->select('instantiation_annotations.annotation_type');
		$this->db->where('instantiation_annotations.instantiations_id', $ins_id);
		return $result = $this->db->get('instantiation_annotations')->result();
	}

	function get_relation_by_instantiation_id($ins_id)
	{
		$this->db->select('instantiation_relations.relation_identifier');
		$this->db->select('relation_types.relation_type');
		$this->db->select('relation_types.relation_type_source');
		$this->db->select('relation_types.relation_type_ref');
		$this->db->join('relation_types', 'relation_types.id = instantiation_relations.relation_types_id', 'left');
		$this->db->where('instantiation_relations.instantiations_id', $ins_id);
		return $result = $this->db->get('instantiation_relations')->result();
	}

	function get_single_essence_tracks_by_instantiations_id($ins_id)
	{

		$this->db->select('essence_tracks.id');
		$this->db->select('essence_tracks.frame_rate');
		$this->db->select('essence_tracks.playback_speed,essence_tracks.sampling_rate');
		$this->db->select('essence_tracks.aspect_ratio');
		$this->db->select('essence_track_frame_sizes.width,essence_track_frame_sizes.height');
		$this->db->join('essence_track_frame_sizes', 'essence_track_frame_sizes.id=essence_tracks.essence_track_frame_sizes_id', 'LEFT');
		$this->db->where('essence_tracks.instantiations_id', $ins_id);
		$result = $this->db->get('essence_tracks');
		if (isset($result) && ! empty($result))
			return $result->row();
		return FALSE;
	}

}

?>