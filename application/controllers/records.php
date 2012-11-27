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
			$data['isAjax'] = FALSE;
			$offset = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        	$param = array('search' => '','index'=>'assets_list');
		    $records=$this->sphinx->assets_listing($param,$offset);
        	$data['total'] = $records['total_count'];
        	$config['total_rows'] = $data['total'];
        	$config['per_page'] = 100;
        	$data['records'] = $records['records'];
        	$data['count'] = count($data['records']);
			if ($data['count'] > 0 && $offset == 0)
			{
				$data['start'] = 1;
				$data['end'] = $data['count'];
			}
			else
			{
				$data['start'] = $offset;
				$data['end'] = intval($offset) + intval($data['count']);
			}
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
			if (isAjax())
			{
				$data['isAjax'] = TRUE;
				echo $this->load->view('instantiations/index', $data, TRUE);
				exit;
			}
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
		function details($asset_id)
		{
			if($asset_id)
			{
				$data['asset_details']=$this->assets_model->get_asset_by_asset_id($asset_id);
				//print_r($data['asset_details']);
				$data['asset_subjects']=$this->assets_model->get_subjects_by_assets_id($asset_id);
				$data['asset_dates']=$this->assets_model->get_assets_dates_by_assets_id($asset_id);
				$data['asset_genres']=$this->assets_model->get_assets_genres_by_assets_id($asset_id);
				$data['asset_creators_roles']=$this->assets_model->get_assets_creators_roles_by_assets_id($asset_id);
				$data['asset_contributor_roles']=$this->assets_model->get_assets_contributor_roles_by_assets_id($asset_id);
				$data['asset_publishers_roles']=$this->assets_model->get_assets_publishers_role_by_assets_id($asset_id);
								
				$this->load->view('records/assets_details',$data);
			}
			else
			{
				show_404();
			}
		}
}
