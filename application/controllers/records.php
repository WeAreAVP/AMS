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
			$this->load->model('assets_model');
			$this->layout = 'main_layout.php';
		}
		/*
		*
		*To List All Assets
		*
		*/
		function index()
		{
			$data['assets']=$this->assets_model->get_all();
			$this->load->view('records/index',$data);
			
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