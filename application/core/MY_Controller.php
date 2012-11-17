<?php

if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

class MY_Controller extends CI_Controller
{

    /**
     * constructor. Load layout,Model,Library and helpers
     * 
     */
    public $total_unread;
    public $user_id;
    public $role_id;
    public $is_station_user;
    public $station_id;
    public $user_details;
    public $can_compose_alert;

    function __construct()
    {
        parent::__construct();
        if (!$this->dx_auth->is_logged_in())
        {
            redirect('auth/login');
        }
        $this->load->library('Form_validation');
        $this->load->helper('form');
        $this->load->model('messages_model', 'msgs');
        $this->load->model('station_model');
        $this->load->model('dx_auth/users', 'users');
        $this->load->model('dx_auth/user_profile', 'user_profile');
        $this->load->model('dx_auth/roles', 'roles');
        $this->load->model('email_template_model', 'email_template');
        $this->load->model('report_model');
        //if (!isset($this->user_id))
        {
            $this->_assing_user_info();
        }
    }

    /*
     * To Assign Current Login user info
     */

    function _assing_user_info()
    {
        $this->user_id = $this->dx_auth->get_user_id();
        $this->role_id = $this->dx_auth->get_role_id();
        $this->is_station_user = $this->dx_auth->is_station_user();
        if ($this->is_station_user)
        {
            $this->station_id = $this->dx_auth->get_station_id();
            $this->total_unread = $this->msgs->get_unread_msgs_count($this->station_id);
        }
        $this->user_detail = $this->users->get_user_detail($this->user_id)->row();
        $this->can_compose_alert = false;
        if (in_array($this->role_id, array(1, 2, 5)))
        {
            $this->can_compose_alert = true;
        }
    }

}

?>