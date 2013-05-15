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

}

?>
