<?php

/**
 * Settings controller.
 *
 * @package    AMS
 * @subpackage Settings
 * @author     Nouman Tayyab
 */
class Settings extends CI_Controller {

    /**
     * constructor. Load layout,Model,Library and helpers
     * 
     */
    function __construct() {
        parent::__construct();
        $this->layout = 'main_layout.php';
        $this->load->model('dx_auth/users', 'users');

        if (!$this->dx_auth->is_logged_in()) {
            redirect('auth/login');
        }
    }

    /**
     * List all the stations
     *  
     */
    public function index() {
        $this->users();
    }

    public function users() {
        $data['users'] = $this->users->get_users()->result();
        echo '<pre>';
        print_r($data['users']);
        exit;

        $this->load->view('settings/index', $data);
    }

}

?>