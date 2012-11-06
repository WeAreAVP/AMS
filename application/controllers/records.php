<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * Records controller.
 *
 * @package    AMS
 * @author     Ali Raza
 */
class Records extends MY_Controller 
{
		/*
		 *
     * Constructor
     * 
     */
    function Records()
		{
			parent::__construct();
			$this->layout = 'main_layout.php';
    }
		/*
		*
		*To List All Email Sent Through this System With Possible Filters
		*
		*/
		function index()
		{
			$this->load->view('records/index');
		}
}