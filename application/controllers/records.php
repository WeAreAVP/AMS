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
		*To List All Assets
		*
		*/
		function index()
		{
			$this->load->view('records/index');
		}
	 /*
		*
		*To List All flagged
		*
		*/
		function flagged()
		{
			$this->load->view('records/flagged');
		}
}