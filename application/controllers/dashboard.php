<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

/**
 * dashboard controller.
 *
 * @package    AMS
 * @subpackage dashboard
 * @author     Nouman Tayyab
 */
class Dashboard extends MY_Controller {

  /**
   * Constructor. 
   */
  function __construct() {
    parent::__construct();
    $this->layout = 'main_layout.php';
  }

  /**
   * Dashboard Functionality
   *  
   */
  function index() {
    $data=null;
    $this->load->view('dashboard/index', $data);
  }

}