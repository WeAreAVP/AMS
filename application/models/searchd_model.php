<?php

/**
 * Searchd Model
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
 * Searchd Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Model
 * @author     Nouman Tayyab <nouman@geekschicago.com>
 * @license    AMS http://ams.avpreserve.com
 * @link       http://ams.avpreserve.com
 */
class Searchd_Model extends CI_Model
{
	/*
	 *
	 * constructor. Load Sphinx Search Library
	 * 
	 */

	function __construct()
	{
		parent::__construct();
		$this->sphnix_db = $this->load->database('sphnix', TRUE);
	}

	function check_sphnix()
	{

		$this->sphnix_db->reconnect();

		$query = $this->sphnix_db->query('SELECT * FROM stations limit 10');
		debug($query->result());
	}

	function get_instantiation($record_ids)
	{
		$search_ids = implode(',', $record_ids);
		return $this->db->query("SELECT  instantiations.id,
GROUP_CONCAT(DISTINCT(instantiations.language) SEPARATOR ' | ') AS language, 
  IFNULL(TIME_TO_SEC(instantiations.actual_duration),0) AS actual_duration, 
  IFNULL(instantiations.projected_duration,'0') AS projected_duration, 
  instantiations.file_size_unit_of_measure, instantiations.file_size,
  GROUP_CONCAT(DISTINCT(asset_titles.title) SEPARATOR ' | ') AS asset_title, 
  GROUP_CONCAT(DISTINCT(IFNULL(`asset_titles`.`title_source`,'(**)')) SEPARATOR ' | ') AS asset_title_source, 
  GROUP_CONCAT(DISTINCT(IFNULL(`asset_titles`.`title_ref`,'(**)')) SEPARATOR ' | ') AS asset_title_ref, 
  GROUP_CONCAT(DISTINCT(IFNULL(`asset_title_types`.`title_type`,'(**)')) SEPARATOR ' | ') AS asset_title_type, 
  stations.station_name AS organization, 
  
  IFNULL(UNIX_TIMESTAMP(instantiation_dates.instantiation_date ),0) AS dates, 
  date_types.date_type AS date_type, 
  GROUP_CONCAT(DISTINCT(instantiation_media_types.media_type) SEPARATOR ' | ') AS media_type, 
  instantiation_formats.format_type, 
  instantiation_formats.format_name, 
  instantiation_colors.color, 
  GROUP_CONCAT(DISTINCT(`generations`.`generation`) SEPARATOR ' | ') AS generation, 
  nomination_status.status, 
  GROUP_CONCAT(DISTINCT(IFNULL(instantiation_identifier.instantiation_identifier,'(**)')) SEPARATOR ' | ') AS instantiation_identifier, 
  GROUP_CONCAT(DISTINCT(IFNULL(instantiation_identifier.instantiation_source,'(**)')) SEPARATOR ' | ') AS instantiation_source
  FROM (`instantiations`) 
  LEFT JOIN `assets` ON `assets`.`id` = `instantiations`.`assets_id` 
 JOIN `stations` ON `stations`.`id` = `assets`.`stations_id` 
 LEFT JOIN `asset_titles` ON `asset_titles`.`assets_id` = `instantiations`.`assets_id` 
   LEFT JOIN `asset_title_types` ON `asset_titles`.`asset_title_types_id` = `asset_title_types`.`id` 
  LEFT JOIN `instantiation_dates` ON `instantiation_dates`.`instantiations_id` = `instantiations`.`id` 
  LEFT JOIN `date_types` ON `date_types`.`id` = `instantiation_dates`.`date_types_id` 
  LEFT JOIN `instantiation_media_types` ON `instantiation_media_types`.`id` = `instantiations`.`instantiation_media_type_id` 
  LEFT JOIN `instantiation_formats` ON `instantiation_formats`.`instantiations_id` = `instantiations`.`id` 
  LEFT JOIN `instantiation_colors` ON `instantiation_colors`.`id` = `instantiations`.`instantiation_colors_id` 
  LEFT JOIN `instantiation_identifier` ON `instantiations`.`id` = `instantiation_identifier`.`instantiations_id` 
  LEFT JOIN `instantiation_generations` ON `instantiation_generations`.`instantiations_id` = `instantiations`.`id`
  LEFT JOIN `generations` ON `generations`.`id` = `instantiation_generations`.`generations_id`
  LEFT JOIN `nominations` ON `nominations`.`instantiations_id` = `instantiations`.`id`
  LEFT JOIN `nomination_status` ON `nomination_status`.`id` = `nominations`.`nomination_status_id`
 WHERE instantiations.id in ($search_ids)
 GROUP BY `instantiations`.`id`")->result();
	}

	function get_assets($record_ids)
	{
		$search_ids = implode(',', $record_ids);
		return $this->db->query("SELECT `assets`.`id`, `identifiers`.`identifier` AS guid_identifier, `asset_descriptions`.`description`,stations.station_name AS organization, 
		IFNULL(`description_types`.`description_type`,'') AS description_type, 
		GROUP_CONCAT(DISTINCT(IFNULL(`local`.`identifier`,'(**)')) SEPARATOR ' | ') AS local_identifier, 
		GROUP_CONCAT(DISTINCT(IFNULL(`asset_titles`.`title`,'(**)')) SEPARATOR ' | ') AS asset_title, 
		GROUP_CONCAT(DISTINCT(IFNULL(`asset_titles`.`title_source`,'(**)')) SEPARATOR ' | ') AS asset_title_source, 
		GROUP_CONCAT(DISTINCT(IFNULL(`asset_titles`.`title_ref`,'(**)')) SEPARATOR ' | ') AS asset_title_ref, 
		GROUP_CONCAT(DISTINCT(IFNULL(`asset_title_types`.`title_type`,'(**)')) SEPARATOR ' | ') AS asset_title_type, 
		GROUP_CONCAT(DISTINCT(IFNULL(`subjects`.`subject`,'(**)')) SEPARATOR ' | ') AS asset_subject, 
		GROUP_CONCAT(DISTINCT(IFNULL(`subjects`.subject_source,'(**)')) SEPARATOR ' | ') AS asset_subject_source, 
		GROUP_CONCAT(DISTINCT(IFNULL(`subjects`.subject_ref,'(**)')) SEPARATOR ' | ') AS asset_subject_ref, 
		GROUP_CONCAT(DISTINCT(IFNULL(`genres`.`genre`,'(**)')) SEPARATOR ' | ') AS asset_genre, 
		GROUP_CONCAT(DISTINCT(IFNULL(`genres`.`genre_source`,'(**)')) SEPARATOR ' | ') AS asset_genre_source, 
		GROUP_CONCAT(DISTINCT(IFNULL(`genres`.`genre_ref`,'(**)')) SEPARATOR ' | ') AS asset_genre_ref, 
		GROUP_CONCAT(DISTINCT(IFNULL(`creators`.`creator_name`,'(**)')) SEPARATOR ' | ') AS asset_creator_name, 
		GROUP_CONCAT(DISTINCT(IFNULL(`creators`.`creator_affiliation`,'(**)')) SEPARATOR ' | ') AS asset_creator_affiliation, 
		GROUP_CONCAT(DISTINCT(IFNULL(`creators`.`creator_source`,'(**)')) SEPARATOR ' | ') AS asset_creator_source, 
		GROUP_CONCAT(DISTINCT(IFNULL(`creators`.`creator_ref`,'(**)')) SEPARATOR ' | ') AS asset_creator_ref, 
 GROUP_CONCAT(DISTINCT(IFNULL(`creator_roles`.`creator_role`,'(**)')) SEPARATOR ' | ') AS asset_creator_role, 
 GROUP_CONCAT(DISTINCT(IFNULL(`creator_roles`.`creator_role_source`,'(**)')) SEPARATOR ' | ') AS asset_creator_role_source, 
 GROUP_CONCAT(DISTINCT(IFNULL(`creator_roles`.`creator_role_ref`,'(**)')) SEPARATOR ' | ') AS asset_creator_role_ref, 
 GROUP_CONCAT(DISTINCT(IFNULL(`contributors`.`contributor_name`,'(**)')) SEPARATOR ' | ') AS asset_contributor_name, 
 GROUP_CONCAT(DISTINCT(IFNULL(`contributors`.`contributor_affiliation`,'(**)')) SEPARATOR ' | ') AS asset_contributor_affiliation, 
 GROUP_CONCAT(DISTINCT(IFNULL(`contributors`.`contributor_source`,'(**)')) SEPARATOR ' | ') AS asset_contributor_source, 
 GROUP_CONCAT(DISTINCT(IFNULL(`contributors`.`contributor_ref`,'(**)')) SEPARATOR ' | ') AS asset_contributor_ref, 
 GROUP_CONCAT(DISTINCT(IFNULL(`contributor_roles`.`contributor_role`,'(**)')) SEPARATOR ' | ') AS asset_contributor_role, 
 GROUP_CONCAT(DISTINCT(IFNULL(`contributor_roles`.`contributor_role_source`,'(**)')) SEPARATOR ' | ') AS asset_contributor_role_source, 
 GROUP_CONCAT(DISTINCT(IFNULL(`contributor_roles`.`contributor_role_ref`,'(**)')) SEPARATOR ' | ') AS asset_contributor_role_ref, 
 GROUP_CONCAT(DISTINCT(IFNULL(`publishers`.`publisher`,'(**)')) SEPARATOR ' | ') AS asset_publisher_name, 
 GROUP_CONCAT(DISTINCT(IFNULL(`publishers`.`publisher_affiliation`,'(**)')) SEPARATOR ' | ') AS asset_publisher_affiliation, 
 GROUP_CONCAT(DISTINCT(IFNULL(`publishers`.`publisher_ref`,'(**)')) SEPARATOR ' | ') AS asset_publisher_ref, 
 GROUP_CONCAT(DISTINCT(IFNULL(`publisher_roles`.`publisher_role`,'(**)')) SEPARATOR ' | ') AS asset_publisher_role, 
 GROUP_CONCAT(DISTINCT(IFNULL(`publisher_roles`.`publisher_role_source`,'(**)')) SEPARATOR ' | ') AS asset_publisher_role_source, 
 GROUP_CONCAT(DISTINCT(IFNULL(`publisher_roles`.`publisher_role_ref`,'(**)')) SEPARATOR ' | ') AS asset_publisher_role_ref, 
 GROUP_CONCAT(DISTINCT(IFNULL(UNIX_TIMESTAMP(`asset_dates`.`asset_date`),0)) SEPARATOR ' | ') AS dates, 
 GROUP_CONCAT(DISTINCT(IFNULL(`date_types`.`date_type`,'(**)')) SEPARATOR ' | ') AS date_type, 
 GROUP_CONCAT(DISTINCT(IFNULL(`coverages`.`coverage`,'(**)')) SEPARATOR ' | ') AS asset_coverage, 
 GROUP_CONCAT(DISTINCT(IFNULL(`coverages`.`coverage_type`,'(**)')) SEPARATOR ' | ') AS asset_coverage_type, 
 GROUP_CONCAT(DISTINCT(IFNULL(`audience_levels`.`audience_level`,'(**)')) SEPARATOR ' | ') AS asset_audience_level, 
 GROUP_CONCAT(DISTINCT(IFNULL(`audience_levels`.`audience_level_source`,'(**)')) SEPARATOR ' | ') AS asset_audience_level_source, 
 GROUP_CONCAT(DISTINCT(IFNULL(`audience_levels`.`audience_level_ref`,'(**)')) SEPARATOR ' | ') AS asset_audience_level_ref, 
 GROUP_CONCAT(DISTINCT(IFNULL(`audience_ratings`.`audience_rating`,'(**)')) SEPARATOR ' | ') AS asset_audience_rating, 
 GROUP_CONCAT(DISTINCT(IFNULL(`audience_ratings`.`audience_rating_source`,'(**)')) SEPARATOR ' | ') AS asset_audience_rating_source, 
 GROUP_CONCAT(DISTINCT(IFNULL(`audience_ratings`.`audience_rating_ref`,'(**)')) SEPARATOR ' | ') AS asset_audience_rating_ref, 
 GROUP_CONCAT(DISTINCT(IFNULL(`annotations`.`annotation`,'(**)')) SEPARATOR ' | ') AS asset_annotation, 
 GROUP_CONCAT(DISTINCT(IFNULL(`annotations`.`annotation_type`,'(**)')) SEPARATOR ' | ') AS asset_annotation_type, 
 GROUP_CONCAT(DISTINCT(IFNULL(`annotations`.`annotation_ref`,'(**)')) SEPARATOR ' | ') AS asset_annotation_ref, 
 GROUP_CONCAT(DISTINCT(IFNULL(`rights_summaries`.`rights`,'(**)')) SEPARATOR ' | ') AS asset_rights, 
 GROUP_CONCAT(DISTINCT(IFNULL(`rights_summaries`.`rights_link`,'(**)')) SEPARATOR ' | ') AS asset_rights_link
 FROM (`assets`) 
 LEFT JOIN `identifiers` AS `local` ON `local`.`assets_id` = `assets`.`id` AND `local`.`identifier_source` != 'http://americanarchiveinventory.org' 
 LEFT JOIN `identifiers` ON `identifiers`.`assets_id` = `assets`.`id`  AND `identifiers`.`identifier_source` = 'http://americanarchiveinventory.org'  
 LEFT JOIN `asset_descriptions` ON `asset_descriptions`.`assets_id` = `assets`.`id` 
 LEFT JOIN `description_types` ON `description_types`.`id` = `asset_descriptions`.`description_types_id` 
 LEFT JOIN `asset_titles` ON `asset_titles`.`assets_id` = `assets`.`id` 
 LEFT JOIN `asset_title_types` ON `asset_titles`.`asset_title_types_id` = `asset_title_types`.`id` 
 LEFT JOIN `assets_subjects` ON `assets_subjects`.`assets_id` = `assets`.`id` 
 LEFT JOIN `subjects` ON `subjects`.`id` = `assets_subjects`.`subjects_id` 
 LEFT JOIN `assets_genres` ON `assets_genres`.`assets_id` = `assets`.`id` 
 LEFT JOIN `genres` ON `genres`.`id` = `assets_genres`.`genres_id` 
 LEFT JOIN `assets_creators_roles` ON `assets_creators_roles`.`assets_id`=`assets`.`id` 
 LEFT JOIN `creator_roles` ON `assets_creators_roles`.`creator_roles_id`=`creator_roles`.`id` 
 LEFT JOIN `creators` ON `assets_creators_roles`.`creators_id`=`creators`.`id` 
 LEFT JOIN `assets_contributors_roles` ON `assets_contributors_roles`.`assets_id`=`assets`.`id` 
 LEFT JOIN `contributor_roles` ON `assets_contributors_roles`.`contributor_roles_id`=`contributor_roles`.`id` 
 LEFT JOIN `contributors` ON `assets_contributors_roles`.`contributors_id`=`contributors`.`id` 
 LEFT JOIN `assets_publishers_role` ON `assets_publishers_role`.`assets_id`=`assets`.`id` 
 LEFT JOIN `publisher_roles` ON `assets_publishers_role`.`publisher_roles_id`=`publisher_roles`.`id` 
 LEFT JOIN `publishers` ON `assets_publishers_role`.`publishers_id`=`publishers`.`id` 
 LEFT JOIN `asset_dates` ON `asset_dates`.`assets_id`=`assets`.`id` 
 LEFT JOIN `date_types` ON `asset_dates`.`date_types_id`=`date_types`.`id` 
 LEFT JOIN `coverages` ON `coverages`.`assets_id`=`assets`.`id` 
 LEFT JOIN `assets_audience_levels` ON `assets_audience_levels`.`assets_id`=`assets`.`id` 
 LEFT JOIN `audience_levels` ON `assets_audience_levels`.`audience_levels_id`=`audience_levels`.`id` 
 LEFT JOIN `assets_audience_ratings` ON `assets_audience_ratings`.`assets_id`=`assets`.`id` 
 LEFT JOIN `audience_ratings` ON `assets_audience_ratings`.`audience_ratings_id`=`audience_ratings`.`id` 
 LEFT JOIN `annotations` ON `annotations`.`assets_id`=`assets`.`id` 
 LEFT JOIN `rights_summaries` ON `rights_summaries`.`assets_id`=`assets`.`id` 

 LEFT JOIN `stations` ON `stations`.`id` = `assets`.`stations_id` 
 WHERE assets.id in ($search_ids)
 GROUP BY `assets`.`id` ")->result();
	}

}

?>
