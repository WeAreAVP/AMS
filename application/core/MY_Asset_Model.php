<?php

if ( ! defined('BASEPATH'))
{
	exit('No direct script access allowed');
}

class MY_Asset_Model extends MY_Instantiation_Model
{

	function __construct()
	{
		parent::__construct();
	}

	/**
	 * Get Assets type by asset ID.
	 * 
	 * @param integer $asset_id
	 * @return stdObject
	 */
	function get_asset_type($asset_id)
	{
		return $this->db->select("{$this->table_asset_types}.asset_type")
		->join($this->table_asset_types, "{$this->table_asset_types}.id = {$this->table_assets_asset_types}.asset_types_id")
		->where("{$this->table_assets_asset_types}.assets_id", $asset_id)
		->get($this->table_assets_asset_types)->result();
	}

	/**
	 * Get Assets dates by asset ID.
	 * 
	 * @param integer $asset_id
	 * @return stdObject
	 */
	function get_asset_date($asset_id)
	{
		return $this->db->select("{$this->table_asset_dates}.asset_date,{$this->table_date_types}.date_type")
		->join($this->table_date_types, "{$this->table_date_types}.id = {$this->table_asset_dates}.date_types_id", 'LEFT')
		->where("{$this->table_asset_dates}.assets_id", $asset_id)
		->get($this->table_asset_dates)->result();
	}

	/**
	 * Get Assets titles by asset ID.
	 * 
	 * @param integer $asset_id
	 * @return stdObject
	 */
	function get_asset_title($asset_id)
	{
		return $this->db->select("{$this->asset_titles}.*,{$this->table_asset_title_types}.title_type")
		->join($this->table_asset_title_types, "{$this->table_asset_title_types}.id = {$this->asset_titles}.asset_title_types_id", 'LEFT')
		->where("{$this->asset_titles}.assets_id", $asset_id)
		->get($this->asset_titles)->result();
	}

	/**
	 * Get Assets subjects by asset ID.
	 * 
	 * @param integer $asset_id
	 * @return stdObject
	 */
	function get_asset_subject($asset_id)
	{
		return $this->db->select("{$this->table_subjects}.*,{$this->table_subject_types}.subject_type")
		->join($this->table_subjects, "{$this->table_subjects}.id = {$this->table_assets_subjects}.subjects_id", 'LEFT')
		->join($this->table_subject_types, "{$this->table_subject_types}.id = {$this->table_subjects}.subjects_types_id", 'LEFT')
		->where("{$this->table_assets_subjects}.assets_id", $asset_id)
		->get($this->table_assets_subjects)->result();
	}

	/**
	 * Get Assets description by asset ID.
	 * 
	 * @param integer $asset_id
	 * @return stdObject
	 */
	function get_asset_description($asset_id)
	{
		return $this->db->select("{$this->table_asset_descriptions}.description,{$this->table_description_types}.description_type")
		->join($this->table_description_types, "{$this->table_description_types}.id = {$this->table_asset_descriptions}.description_types_id", 'LEFT')
		->where("{$this->table_asset_descriptions}.assets_id", $asset_id)
		->get($this->table_asset_descriptions)->result();
	}

	/**
	 * Get Assets genres by asset ID.
	 * 
	 * @param integer $asset_id
	 * @return stdObject
	 */
	function get_asset_genre($asset_id)
	{
		return $this->db->select("{$this->table_genres}.*")
		->join($this->table_genres, "{$this->table_genres}.id = {$this->table_assets_genres}.genres_id", 'LEFT')
		->where("{$this->table_assets_genres}.assets_id", $asset_id)
		->get($this->table_assets_genres)->result();
	}

	/**
	 * Get Assets Audience level by asset ID.
	 * 
	 * @param integer $asset_id
	 * @return stdObject
	 */
	function get_asset_audience_level($asset_id)
	{
		return $this->db->select("{$this->table_audience_levels}.*")
		->join($this->table_audience_levels, "{$this->table_audience_levels}.id = {$this->table_assets_audience_levels}.audience_levels_id", 'LEFT')
		->where("{$this->table_assets_audience_levels}.assets_id", $asset_id)
		->get($this->table_assets_audience_levels)->result();
	}

	/**
	 * Get Assets Audience rating by asset ID.
	 * 
	 * @param integer $asset_id
	 * @return stdObject
	 */
	function get_asset_audience_rating($asset_id)
	{
		return $this->db->select("{$this->table_audience_ratings}.*")
		->join($this->table_audience_ratings, "{$this->table_audience_ratings}.id = {$this->table_assets_audience_ratings}.audience_ratings_id", 'LEFT')
		->where("{$this->table_assets_audience_ratings}.assets_id", $asset_id)
		->get($this->table_assets_audience_ratings)->result();
	}

	/**
	 * Get Assets Relations by asset ID.
	 * 
	 * @param integer $asset_id
	 * @return stdObject
	 */
	function get_asset_relation($asset_id)
	{
		return $this->db->select("{$this->table_assets_relations}.relation_identifier,{$this->table_relation_types}.*")
		->join($this->table_relation_types, "{$this->table_relation_types}.id = {$this->table_assets_relations}.relation_types_id", 'LEFT')
		->where("{$this->table_assets_relations}.assets_id", $asset_id)
		->get($this->table_assets_relations)->result();
	}

	/**
	 * Get Assets Creators and its role by asset ID.
	 * 
	 * @param integer $asset_id
	 * @return stdObject
	 */
	function get_asset_creator_and_role($asset_id)
	{
		return $this->db->select("{$this->table_creators}.*,{$this->table_creator_roles}.*")
		->join($this->table_creators, "{$this->table_creators}.id = {$this->table_assets_creators_roles}.creators_id", 'LEFT')
		->join($this->table_creator_roles, "{$this->table_creator_roles}.id = {$this->table_assets_creators_roles}.creator_roles_id", 'LEFT')
		->where("{$this->table_assets_creators_roles}.assets_id", $asset_id)
		->get($this->table_assets_creators_roles)->result();
	}

	/**
	 * Get Assets Contributors and its role by asset ID.
	 * 
	 * @param integer $asset_id
	 * @return stdObject
	 */
	function get_asset_contributor_and_role($asset_id)
	{
		return $this->db->select("{$this->table_contributors}.*,{$this->table_contributor_roles}.*")
		->join($this->table_contributors, "{$this->table_contributors}.id = {$this->table_assets_contributors_roles}.contributors_id", 'LEFT')
		->join($this->table_contributor_roles, "{$this->table_contributor_roles}.id = {$this->table_assets_contributors_roles}.contributor_roles_id", 'LEFT')
		->where("{$this->table_assets_contributors_roles}.assets_id", $asset_id)
		->get($this->table_assets_contributors_roles)->result();
	}

	/**
	 * Get Assets Publishers and its role by asset ID.
	 * 
	 * @param integer $asset_id
	 * @return stdObject
	 */
	function get_asset_publisher_and_role($asset_id)
	{
		return $this->db->select("{$this->table_publishers}.*,{$this->table_publisher_roles}.*")
		->join($this->table_publishers, "{$this->table_publishers}.id = {$this->table_assets_publishers_role}.publishers_id", 'LEFT')
		->join($this->table_publisher_roles, "{$this->table_publisher_roles}.id = {$this->table_assets_publishers_role}.publisher_roles_id", 'LEFT')
		->where("{$this->table_assets_publishers_role}.assets_id", $asset_id)
		->get($this->table_assets_publishers_role)->result();
	}

	/**
	 * Get assets by digitization and last modified date.
	 * 
	 * @param string $date
	 * @param integer $digitized
	 * @return stdObject
	 */
	function get_assets_by_date_digitized($date, $digitized)
	{

		$this->db->select("{$this->_assets_table}.id")
		->join($this->table_instantiations, "{$this->table_instantiations}.assets_id = {$this->_assets_table}.id", 'LEFT');
		if ( ! empty($date))
		{
			$date = date('Y-m-d', strtotime($date));
			$this->db->where("({$this->_assets_table}.created LIKE '{$date}%' OR {$this->_assets_table}.updated LIKE '{$date}%')");
		}
		if ( ! empty($digitized))
		{
			if ($digitized == 0)
				$this->db->where("({$this->table_instantiations}.digitized = $digitized  OR {$this->table_instantiations}.digitized IS NULL)");
			else
				$this->db->where("{$this->table_instantiations}.digitized", $digitized);
		}
		$this->db->limit(100);

		$this->db->get($this->_assets_table);
		
		return $this->db->get($this->_assets_table)->result();
	}

}

?>