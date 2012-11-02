<?php

/**
 * tracking controller.
 *
 * @package    AMS
 * @subpackage Tracking
 * @author     Nouman Tayyab
 */
class Tracking extends MY_Controller {

    /**
     * constructor. Load layout,Model,Library and helpers
     * 
     */
    function __construct() {
        parent::__construct();
        error_reporting(E_ALL);
        error_reporting(1);
        $this->layout = 'main_layout.php';
        $this->load->model('station_model', 'station');
        $this->load->model('tracking_model', 'tracking');
    }

    /**
     * Create a new Tracking Record
     * Get station_id as uri segment 3
     *  
     */
    public function add() {
        $this->layout = 'default.php';
        $data['station_id'] = $this->uri->segment(3);
        $val = $this->form_validation;

        $val->set_rules('ship_date', 'Ship Date', 'trim|required|xss_clean');
        $val->set_rules('ship_to', 'Password', 'trim|required|xss_clean');
        $val->set_rules('ship_via', 'First Name', 'trim|required|xss_clean');
        $val->set_rules('tracking_no', 'Last Name', 'trim|required|xss_clean');
        $val->set_rules('no_box_shipped', 'Phone #', 'trim|required|xss_clean');

        if ($this->input->post()) {
            if ($val->run()) {

                $record = array('ship_date' => date('Y-m-d', strtotime($val->set_value('ship_date'))),
                    'ship_to' => $val->set_value('ship_to'),
                    'ship_via' => $val->set_value('ship_via'),
                    'tracking_no' => $val->set_value('tracking_no'),
                    'no_box_shipped' => $val->set_value('no_box_shipped'),
                    'station_id' => $data['station_id'],
                );

                $this->tracking->insert_record($record);
                echo 'done';
                exit;
            } else {
                $errors = $val->error_string();
                $data['errors'] = $errors;
            }
        }
        echo $this->load->view('tracking/add', $data, TRUE);
        exit;
    }

    /**
     * Edit Tracking Information.
     * Get tracking_id as uri segment 3
     * 
     */
    public function edit() {
        $this->layout = 'default.php';
        $tracking_id = $this->uri->segment(3);
        $val = $this->form_validation;

        $val->set_rules('ship_date', 'Ship Date', 'trim|required|xss_clean');
        $val->set_rules('ship_to', 'Password', 'trim|required|xss_clean');
        $val->set_rules('ship_via', 'First Name', 'trim|required|xss_clean');
        $val->set_rules('tracking_no', 'Last Name', 'trim|required|xss_clean');
        $val->set_rules('no_box_shipped', 'Phone #', 'trim|required|xss_clean');

        if ($this->input->post()) {
            if ($val->run()) {

                $record = array('ship_date' => date('Y-m-d', strtotime($val->set_value('ship_date'))),
                    'ship_to' => $val->set_value('ship_to'),
                    'ship_via' => $val->set_value('ship_via'),
                    'tracking_no' => $val->set_value('tracking_no'),
                    'no_box_shipped' => $val->set_value('no_box_shipped'),
                );

                $this->tracking->update_record($tracking_id, $record);
                echo 'done';
                exit;
            } else {
                $errors = $val->error_string();
                $data['errors'] = $errors;
            }
        }
        $data['tracking_info'] = $this->tracking->get_by_id($tracking_id);
        echo $this->load->view('tracking/edit', $data, TRUE);
        exit;
    }

    /**
     * Delete the tracking info
     * Get station_id as uri segment 4
     * Get tracking_id as uri segment 3
     *  
     */
    public function delete() {
        $tracking_id = $this->uri->segment(3);
        $staion_id = $this->uri->segment(4);
        $this->tracking->delete_record($tracking_id);
        redirect('stations/detail/' . $staion_id, 'location');
    }

}

?>