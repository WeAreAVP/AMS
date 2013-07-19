<?php

/**
 * Dashboard Controller
 * 
 * PHP version 5
 * 
 * @category   AMS
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @license    AVPS http://ams.avpreserve.com
 * @version    GIT: <$Id>
 * @link       http://ams.avpreserve.com
 */

/**
 * Dashboard Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @license    AVPS http://ams.avpreserve.com
 * @link       http://ams.avpreserve.com
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
	 * Dashboard Functionality
	 * 
	 * @return view dashboard/index
	 */
	public function index()
	{
		$data = $this->get_dashboard();
		$this->load->view('dashboard/index', $data);
	}
	
	private function get_dashboard()
	{
		$data=$this->get_digitized_formats();
		$data['material_goal'] = json_decode($this->memcached_library->get('material_goal'), TRUE);
		$data['at_crawford'] = json_decode($this->memcached_library->get('at_crawford'), TRUE);
		$data['total_hours'] = json_decode($this->memcached_library->get('total_hours'), TRUE);
		$data['percentage_hours'] = json_decode($this->memcached_library->get('percentage_hours'), TRUE);
		return $data;
	}

	private function get_digitized_formats()
	{
		$data=$this->get_scheduled_formats();
		$data['digitized_format_name'] = json_decode($this->memcached_library->get('graph_digitized_format_name'), TRUE);
		$data['digitized_total'] = json_decode($this->memcached_library->get('graph_digitized_total'), TRUE);
		return $data;
	}

	private function get_scheduled_formats()
	{
		$data=$this->pie_charts_detail();
		$data['scheduled_format_name'] = json_decode($this->memcached_library->get('graph_scheduled_format_name'), TRUE);
		$data['scheduled_total'] = json_decode($this->memcached_library->get('graph_scheduled_total'), TRUE);
		return $data;
	}

	private function pie_charts_detail()
	{
		$data=$this->get_records_by_region();
		$data['pie_total_completed'] = json_decode($this->memcached_library->get('pie_total_completed'), TRUE);
		$data['pie_total_scheduled'] = json_decode($this->memcached_library->get('pie_total_scheduled'), TRUE);
		$data['pie_total_radio_completed'] = json_decode($this->memcached_library->get('pie_total_radio_completed'), TRUE);
		$data['pie_total_radio_scheduled'] = json_decode($this->memcached_library->get('pie_total_radio_scheduled'), TRUE);
		$data['pie_total_tv_completed'] = json_decode($this->memcached_library->get('pie_total_tv_completed'), TRUE);
		$data['pie_total_tv_scheduled'] = json_decode($this->memcached_library->get('pie_total_tv_scheduled'), TRUE);
		return $data;
	}

	private function get_records_by_region()
	{
		$data['total_region_digitized'] = json_decode($this->memcached_library->get('total_region_digitized'), TRUE);
		$data['total_hours_region_digitized'] = json_decode($this->memcached_library->get('total_hours_region_digitized'), TRUE);
		return $data;
	}

}

// END Dashboard Controller

// End of file dashboard.php 
/* Location: ./application/controllers/dashboard.php */