<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

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
    function __construct()
		{
    	parent::__construct();
      if (!$this->dx_auth->is_logged_in())
			{
      	redirect('auth/login');
      }
			$this->load->model('messages_model', 'msgs');
    }
		function _assing_user_info()
		{
			$this->user_id = $this->dx_auth->get_user_id();
			$this->role_id = $this->dx_auth->get_role_id();
			$this->is_station_user = $this->dx_auth->is_station_user();
			if($this->is_station_user)
			{
				$this->station_id = $this->dx_auth->get_station_id();;
			}
			$this->total_unread = $this->msgs->get_unread_msgs_count($this->user_id);
		}

}

?>