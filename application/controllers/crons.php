<?php

/**
 * Settings controller.
 *
 * @package    AMS
 * @subpackage Scheduled Tasks
 * @author     Ali Raza
 */
class Crons extends CI_Controller
{

    /**
     *
     * constructor. Load layout,Model,Library and helpers
     * 
     */
    function __construct()
		{
  	  parent::__construct();
	    $this->load->model('station_model');
     	$this->ci->load->model('email_template_model','emailtmp');
    }
		function processemailqueues()
		{
			$this->emailtmp->get_pending_emails();
		}
}