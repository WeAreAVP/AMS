<?php

/**
 * AMS Archive Management System
 * 
 * PHP version 5
 * 
 * @category   AMS
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @copyright  Copyright (c) WGBH (http://www.wgbh.org/). All Rights Reserved.
 * @license    http://www.gnu.org/licenses/gpl.txt GPLv3
 * @version    GIT: <$Id>
 * @link       https://github.com/avpreserve/AMS
 */

/**
 * Dashboard Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @copyright  Copyright (c) WGBH (http://www.wgbh.org/). All Rights Reserved.
 * @license    http://www.gnu.org/licenses/gpl.txt GPLv3
 * @link       https://ams.americanarchive.org
 */
class Dashboard extends MY_Controller
{

	/**
	 * Constructor.
	 * 
	 * Load the layout for the dashboard.
	 *  
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->model('dashboard_model');
		$this->load->library('memcached_library');
		if ($this->is_station_user)
			redirect('records/index');
	}

	/**
	 * Help
	 */
	public function help()
	{
		$this->load->view('dashboard/help');
	}

	/**
	 * Dashboard Functionality
	 * 
	 * @return view dashboard/index
	 */
	public function index()
	{
		$data['digitized_format_name'] = json_decode($this->memcached_library->get('graph_digitized_format_name'), TRUE);
		$data['digitized_total'] = json_decode($this->memcached_library->get('graph_digitized_total'), TRUE);
		$data['scheduled_format_name'] = json_decode($this->memcached_library->get('graph_scheduled_format_name'), TRUE);
		$data['scheduled_total'] = json_decode($this->memcached_library->get('graph_scheduled_total'), TRUE);
		$data['material_goal'] = json_decode($this->memcached_library->get('material_goal'), TRUE);
//                echo '<pre>';print_r($data['material_goal']);exit;
		$data['at_crawford'] = json_decode($this->memcached_library->get('at_crawford'), TRUE);
		$data['total_hours'] = json_decode($this->memcached_library->get('total_hours'), TRUE);
		$data['percentage_hours'] = json_decode($this->memcached_library->get('percentage_hours'), TRUE);
		$data['total_region_digitized'] = json_decode($this->memcached_library->get('total_region_digitized'), TRUE);
		$data['total_hours_region_digitized'] = json_decode($this->memcached_library->get('total_hours_region_digitized'), TRUE);
		$data['pie_total_completed'] = json_decode($this->memcached_library->get('pie_total_completed'), TRUE);
		$data['pie_total_scheduled'] = json_decode($this->memcached_library->get('pie_total_scheduled'), TRUE);
		$data['pie_total_radio_completed'] = json_decode($this->memcached_library->get('pie_total_radio_completed'), TRUE);
		$data['pie_total_radio_scheduled'] = json_decode($this->memcached_library->get('pie_total_radio_scheduled'), TRUE);
		$data['pie_total_tv_completed'] = json_decode($this->memcached_library->get('pie_total_tv_completed'), TRUE);
		$data['pie_total_tv_scheduled'] = json_decode($this->memcached_library->get('pie_total_tv_scheduled'), TRUE);

		$this->load->view('dashboard/index', $data);
	}

}

// END Dashboard Controller

// End of file dashboard.php 
/* Location: ./application/controllers/dashboard.php */