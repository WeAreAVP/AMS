<?php

/**
 * tracking Model.
 *
 * @package    AMS
 * @subpackage tracking_model
 * @author     Nouman Tayyab
 */
class Tracking_Model extends CI_Model {

    /**
     * constructor. set table name amd prefix
     * 
     */
    function __construct() {
        parent::__construct();

        $this->_prefix = '';
        $this->_table = 'tracking_info';
        $this->_table_station = 'stations';
    }

    /**
     * Get list of all the stations
     * 
     * @return array 
     */
    function get_all($station_id) {
        $this->db->where('station_id', $station_id);
        return $query = $this->db->get($this->_table)->result();
    }

    /**
     * insert the records in tracing_info
     * 
     * @param array $data
     * @return boolean 
     */
    function insert_record($data) {
        $this->db->insert($this->_table, $data);
        return $this->db->insert_id(); 
    }

    /**
     * Update the tracking info
     * 
     * @param integer $tracking_id
     * @param array $data
     * @return boolean 
     */
    function update_record($tracking_id, $data) {
        $this->db->where('id', $tracking_id);
        return $this->db->update($this->_table, $data);
    }

    /**
     * Get Tracking info by ID
     * 
     * @param integer $traking_id
     * @return array 
     */
    function get_by_id($traking_id) {
        $this->db->where('id', $traking_id);
        return $this->db->get($this->_table)->row();
    }

    /**
     * Delete the tracking info
     * 
     * @param integer $tracking_id
     * @return interger 
     */
    function delete_record($tracking_id) {
        $this->db->where('id', $tracking_id);
        $this->db->delete($this->_table);
        return $this->db->affected_rows() > 0;
    }

}

?>