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
			$this->load->model('sphinx_model', 'sphinx');
			$this->load->library('pagination');
			$this->layout = 'main_layout.php';
		}
		/*
		*
		*To List All Assets
		*
		*/
		function index()
		{
			$offset = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        	$param = array('search' => '','index'=>'assets_list');
		    $records=$this->sphinx->assets_listing($param,$offset);
        	$data['total'] = $records['total_count'];
        	$config['total_rows'] = $data['total'];
        	$config['per_page'] = 100;
        	$data['records'] = $records['records'];
        	$data['count'] = count($data['records']);
	        $config['base_url'] = $this->config->item('base_url') . $this->config->item('index_page') . "records/index/";
    	    $config['prev_link'] = '<i class="icon-chevron-left"></i>';
        	$config['prev_tag_open'] = '<span class="btn">';
        	$config['prev_tag_close'] = '</span>';
        	$config['next_link'] = '<i class="icon-chevron-right"></i>';
        	$config['next_tag_open'] = '<span class="btn">';
        	$config['next_tag_close'] = '</span>';
        	$config['use_page_numbers'] = FALSE;
	        $config['first_link'] = FALSE;
    	    $config['last_link'] = FALSE;
      		$config['display_pages'] = FALSE;
      	  	$this->pagination->initialize($config);
			
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
		/*
		*To Display Assets details
		*
		*/
		function details()
		{
			$this->load->view('records/assets_details');
		}
}
