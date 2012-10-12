<?php

/**
 * unit_testing controller.
 *
 * @package    ams
 * @subpackage unit_testing
 * @author     Nouman Tayyab, Ali Raza
 */
class Unit_Testing extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('unit_test');
    }

    /**
     * List all the stations
     *  
     */
    public function index() {
        $data['stations'] = $this->station_model->get_all();
        $this->load->view('stations/list', $data);
    }

    /**
     * Show Detail of specific station
     *  
     */
    public function detail() {
        $station_id = $this->uri->segment(3);
        $data['station_detail'] = $this->station_model->get_station_by_id($station_id);
        $this->load->view('stations/detail', $data);
    }

    

}

?>