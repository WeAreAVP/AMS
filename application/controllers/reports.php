<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

/**
 * Reports controller.
 *
 * @package    AMS
 * @author     Ali Raza
 */
class Reports extends MY_Controller {
  /*
   *
   * Constructor
   * 
   */

  function __construct() {
    parent::__construct();
    $this->layout = 'main_layout.php';
  }

  /**
   * List All types of reports
   *  
   */
  function index() {
    $data['station_records'] = $this->station_model->get_all();
    $this->load->view('reports/index', $data);
  }

  /*
   *
   * To List All Email Sent Through this System With Possible Filters
   *
   */

  function emailalerts() {
    $this->report_model->get_email_queue();
  }

}