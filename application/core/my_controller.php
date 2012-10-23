<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    /**
     * constructor. Load layout,Model,Library and helpers
     * 
     */
		var $total_unread;
    function __construct()
		{
        parent::__construct();
        if (!$this->dx_auth->is_logged_in()) {
            redirect('auth/login');
        }
			$this->load->model('messages_model', 'msgs');
			$this->total_unread=$this->msgs->get_unread_msgs_count(); 
		}

}

?>