<?php
// @codingStandardsIgnoreFile
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * AMS Dashboard Controller
 *
 * @category	Controller
 * @package		AMS
 * @subpackage	Dashboard Controller
 * @author		Nouman Tayyab <nouman@geekschicago.com>
 */
class Dashboard extends MY_Controller
{

    /**
     * Constructor.
     * 
     * Load the layout for the dashboard.
     *  
     */
    function __construct()
    {
        parent::__construct();
        $this->layout = 'main_layout.php';
    }

    /**
     * Dashboard Functionality
     *  
     */
    function index()
    {
        $data = null;
        $this->load->view('dashboard/index', $data);
    }

}

// END Dashboard Controller

// End of file dashboard.php 
/* Location: ./application/controllers/dashboard.php */