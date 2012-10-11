<?php

/**
 * stations controller.
 *
 * @package    ams
 * @subpackage stations
 * @author     Nouman Tayyab, Ali Raza
 */
class Stations extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->layout = 'main_layout.php';
        $this->load->model('station_model');
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
        $data['station_detail']= $this->station_model->get_station_by_id($station_id);
        $this->load->view('stations/detail', $data);
        
    }

}

?>