<?php

/**
 * station Model.
 *
 * @package    AMS
 * @subpackage station_model
 * @author     Nouman Tayyab
 */
class Station_Model extends CI_Model {

    /**
     * constructor. set table name amd prefix
     * 
     */
    function __construct() {
        parent::__construct();

        $this->_prefix = '';
        $this->_table = 'stations';
    }

    /**
     * Get list of all the stations
     * 
     * @return array 
     */
    function get_all() {
        return $query = $this->db->get($this->_table)->result();
    }

    /**
     * search station by station_id
     * 
     * @param type $station_id
     * @return array 
     */
    function get_station_by_id($station_id) {
        $this->db->where('id', $station_id);
        return $this->db->get($this->_table)->row();
    }

    /**
     * update the stations record
     * 
     * @param type $station_id
     * @param array $data
     * @return boolean 
     */
    function update_station($station_id, $data) {
        $data['updated_at'] = date('Y-m-d H:i:s', time());
        $this->db->where('id', $station_id);
        return $this->db->update($this->_table, $data);
    }

    /**
     * Get count stations
     * 
     * @return integer 
     */
    function get_station_count() {
         $query = $this->db->get($this->_table);
        return  $query->num_rows;
        
    }
    /**
     * Filter stations
     * 
     * @param type $certified
     * @param type $agreed
     * @return type 
     */
    function apply_filter($certified,$agreed) {
        
        if(trim($certified)!='')
            $this->db->where('is_certified',$certified);
        if(trim($certified)!='')
            $this->db->where('is_agreed',$agreed);
        
        return $query = $this->db->get($this->_table)->result();
        
    }

}

?>