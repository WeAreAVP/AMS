<?php

class Station_Model extends CI_Model {

    function __construct() {
        parent::__construct();

        $this->_prefix = '';
        $this->_table = 'stations';
    }

    function get_all() {
        return $query = $this->db->get($this->_table)->result();
    }

    function get_station_by_id($station_id) {
        $this->db->where('id', $station_id);
        return $this->db->get($this->_table)->row();
    }

}

?>