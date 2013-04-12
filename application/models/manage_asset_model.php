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

}

?>