<?php

/**
 * Assets Model.
 *
 * @package    AMS
 * @subpackage assets_model
 * @author     ALi RAza
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
    }

    function list_all()
    {
        $this->db->select("$this->table_instantiations.*", FALSE);
        $this->db->select("$this->_assets_table.id as asset_id", FALSE);
        $this->db->select("$this->asset_titles.title AS asset_title", FALSE);
        $this->db->select("$this->stations.station_name", FALSE);
        $this->db->join($this->_assets_table, "$this->_assets_table.id = $this->table_instantiations.assets_id");
        $this->db->join($this->asset_titles, "$this->asset_titles.assets_id	 = $this->table_instantiations.assets_id");
        $this->db->join($this->stations, "$this->stations.id = $this->_assets_table.stations_id	");
        $this->db->limit(10);
        $result = $this->db->get($this->table_instantiations)->result();
        return $result;
    }

    /**
     * search generations by @generation
     * 
     * @param type $status
     * @return object 
     */
    function get_generations_by_generation($generation)
    {
        $this->db->where('generation LIKE', $generation);
        $res = $this->db->get($this->table_generations);
        if (isset($res) && !empty($res))
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
        if (isset($res) && !empty($res))
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
        if (isset($res) && !empty($res))
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
        if (isset($res) && !empty($res))
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
        if (isset($res) && !empty($res))
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
        $this->db->insert($this->table_instantiations, $data);
        return $this->db->insert_id();
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

}

?>