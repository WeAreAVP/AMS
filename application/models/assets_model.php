<?php

/**
 * Assets Model.
 *
 * @package    AMS
 * @subpackage assets_model
 * @author     ALi RAza
 */
class Assets_Model extends CI_Model
{

  /**
   * constructor. set table name amd prefix
   * 
   */
  function __construct()
  {
    parent::__construct();
    $this->_prefix = '';
    $this->_assets_table = 'assets';
    $this->_table_backup = 'stations_backup';
    $this->_table_asset_title_types = 'asset_title_types';
    $this->_table_subjects = 'subjects';
    $this->_table_assets_subjects = 'assets_subjects';
    $this->_table_asset_descriptions = 'asset_descriptions';
    $this->_table_description_types = 'description_types';
    $this->_table_identifiers = 'identifiers';
    $this->_table_genres = 'genres';
    $this->_table_assets_genres = 'assets_genres';
    $this->_table_coverages = 'coverages';
    $this->_table_assets_audience_levels = 'assets_audience_levels';
    $this->_table_audience_levels = 'audience_levels';
    $this->_table_audience_ratings = 'audience_ratings';
    $this->_table_assets_audience_ratings = 'assets_audience_ratings';
    $this->_table_annotations = 'annotations';
    $this->_table_relation_types = 'relation_types';
    $this->_table_assets_relations = 'assets_relations';
  }

  /**
   * Get list of all the Assets
   * 
   * @return array 
   */
  function get_all()
  {
    return $query = $this->db->get($this->_assets_table)->result();
  }

  /**
   * search id by description_type
   * 
   * @param type $description_type
   * @return object 
   */
  function get_description_id_by_type($description_type)
  {
    $this->db->where('description_type', $description_type);
    $res = $this->db->get($this->_table_description_types);
    if (isset($res) && !empty($res))
    {
      return $res->row();
    }
    return false;
  }

  /**
   * search asset_title_types by title_type
   * 
   * @param type $title_type
   * @return object 
   */
  function get_subjects_id_by_subject($subject)
  {
    $this->db->where('subject', $subject);
    $res = $this->db->get($this->_table_subjects);
    if (isset($res) && !empty($res))
    {
      return $res->row();
    }
    return false;
  }

  /**
   * search asset_title_types by title_type
   * 
   * @param type $title_type
   * @return object 
   */
  function get_asset_title_types_by_title_type($title_type)
  {
    $this->db->where('title_type', $title_type);
    $res = $this->db->get($this->_table_asset_title_types);
    if (isset($res) && !empty($res))
    {
      return $res->row();
    }
    return false;
  }

  /**
   * search asset by id
   * 
   * @param type $id
   * @return object 
   */
  function get_asset_by_id($assets_id)
  {
    $this->db->where('id', $station_id);
    $res = $this->db->get($this->_assets_table);
    if (isset($res) && !empty($res))
    {
      return $res->row();
    }
    return false;
  }

  /**
   * get assets by staion id
   * 
   * @param type $station_id
   * @return array 
   */
  function get_assets_by_station_id($station_id)
  {
    $this->db->select('*');
    $this->db->where('stations_id', $station_id);
    return $this->db->get($this->_assets_table)->result();
  }

  /**
   * update the stations record
   * 
   * @param type $station_id
   * @param array $data
   * @return boolean 
   */
  function update_assets($id, $data)
  {
    $data['updated'] = date('Y-m-d H:i:s');
    $this->db->where('id', $id);
    return $this->db->update($this->_assets_table, $data);
  }

  /*
   *
   * insert the records in assets 
   * 
   * @param array $data
   * @return last inserted id 
   */

  function insert_assets($data)
  {
    $this->db->insert($this->_assets_table, $data);
    return $this->db->insert_id();
  }

  /*
   *
   * insert the records in asset_title_types 
   * 
   * @param array $data
   * @return last inserted id 
   */

  function insert_asset_title_types($data)
  {
    $this->db->insert($this->_table_asset_title_types, $data);
    return $this->db->insert_id();
  }

  /*
   *
   * insert the records in subjects 
   * 
   * @param array $data
   * @return last inserted id 
   */

  function insert_subjects($data)
  {
    $this->db->insert($this->_table_subjects, $data);
    return $this->db->insert_id();
  }

  /*
   *
   * insert the records in assets_subjects
   * 
   * @param array $data
   * @return last inserted id 
   */

  function insert_assets_subjects($data)
  {
    $this->db->insert($this->_table_assets_subjects, $data);
    return $this->db->insert_id();
  }

  /*
   *
   * insert the records in asset_descriptions
   * 
   * @param array $data
   * @return last inserted id 
   */

  function insert_asset_descriptions($data)
  {
    $this->db->insert($this->_table_asset_descriptions, $data);
    return $this->db->insert_id();
  }

  /*
   *
   * insert the records in description_types
   * 
   * @param array $data
   * @return last inserted id 
   */

  function insert_description_types($data)
  {
    $this->db->insert($this->_table_description_types, $data);
    return $this->db->insert_id();
  }

  /*
   *
   * insert the records in identifiers
   * 
   * @param array $data
   * @return last inserted id 
   */

  function insert_identifiers($data)
  {
    $this->db->insert($this->_table_identifiers, $data);
    return $this->db->insert_id();
  }

  /*
   *
   * insert the records in asset_titles
   * 
   * @param array $data
   * @return last inserted id 
   */

  function insert_asset_titles($data)
  {
    $this->db->insert($this->_table_asset_titles, $data);
    return $this->db->insert_id();
  }

  //By Nouman Tayyab
  /**
   *  Insert get genre type for genres table
   *  @param integer $genre
   *  @param object 
   * 
   */
  function get_genre_type($genre)
  {
    $this->db->where('genre', $genre);
    $result = $this->db->get($this->_table_genres);
    if (isset($result) && !empty($result))
    {
      return $result->row();
    }
    return false;
  }

  /**
   *  Insert the record in genre table
   *  @param array $data
   *  @param integer last_inserted id
   * 
   */
  function insert_genre($data)
  {
    $this->db->insert($this->_table_genres, $data);
    return $this->db->insert_id();
  }

  /**
   *  Insert the record in assets_genres table
   *  @param array $data
   *  @param integer last_inserted id
   * 
   */
  function insert_asset_genre($data)
  {
    $this->db->insert($this->_table_assets_genres, $data);
    return $this->db->insert_id();
  }

  /**
   *  Insert the record in coverages table
   *  @param array $data
   *  @return integer last_inserted id
   * 
   */
  function insert_coverage($data)
  {
    $this->db->insert($this->_table_coverages, $data);
    return $this->db->insert_id();
  }
  
  /**
   *  Insert get genre type for audience_levels table
   *  @param integer $audience_level
   *  @return object 
   * 
   */
  function get_audience_level($audience_level)
  {
    $this->db->where('audience_level', $audience_level);
    $result = $this->db->get($this->_table_audience_levels);
    if (isset($result) && !empty($result))
    {
      return $result->row();
    }
    return false;
  }

  /**
   *  Insert the record in _table_audience_levels table
   *  @param array $data
   *  @return integer last_inserted id
   * 
   */
  function insert_audience_level($data)
  {
    $this->db->insert($this->_table_audience_levels, $data);
    return $this->db->insert_id();
  }

  /**
   *  Insert the record in assets_audience_levels table
   *  @param array $data
   *  @return integer last_inserted id
   * 
   */
  function insert_asset_audience($data)
  {
    $this->db->insert($this->_table_assets_audience_levels, $data);
    return $this->db->insert_id();
  }
  /**
   *  Insert get genre type for audience_levels table
   *  @param integer $audience_rating
   *  @return object 
   * 
   */
  function get_audience_rating($audience_rating)
  {
    $this->db->where('audience_rating', $audience_rating);
    $result = $this->db->get($this->_table_audience_ratings);
    if (isset($result) && !empty($result))
    {
      return $result->row();
    }
    return false;
  }

  /**
   *  Insert the record in _table_audience_levels table
   *  @param array $data
   *  @return integer last_inserted id
   * 
   */
  function insert_audience_rating($data)
  {
    $this->db->insert($this->_table_audience_ratings, $data);
    return $this->db->insert_id();
  }

  /**
   *  Insert the record in assets_audience_ratings table
   *  @param array $data
   *  @return integer last_inserted id
   * 
   */
  function insert_asset_audience_rating($data)
  {
    $this->db->insert($this->_table_assets_audience_ratings, $data);
    return $this->db->insert_id();
  }
  /**
   *  Insert the record in annotations table
   *  @param array $data
   *  @return integer last_inserted id
   * 
   */
  function insert_annotation($data)
  {
    $this->db->insert($this->_table_annotations, $data);
    return $this->db->insert_id();
  }
   /**
   *  Insert get genre type for relation_types table
   *  @param integer $relation_type
   *  @return object 
   * 
   */
  function get_relation_types($relation_type)
  {
    $this->db->where('relation_type', $relation_type);
    $result = $this->db->get($this->_table_relation_types);
    if (isset($result) && !empty($result))
    {
      return $result->row();
    }
    return false;
  }

  /**
   *  Insert the record in relation_types table
   *  @param array $data
   *  @return integer last_inserted id
   * 
   */
  function insert_relation_types($data)
  {
    $this->db->insert($this->_table_relation_types, $data);
    return $this->db->insert_id();
  }

  /**
   *  Insert the record in assets_relations table
   *  @param array $data
   *  @return integer last_inserted id
   * 
   */
  function insert_asset_relation($data)
  {
    $this->db->insert($this->_table_assets_relations, $data);
    return $this->db->insert_id();
  }
  // End Nouman Tayyab
}

?>