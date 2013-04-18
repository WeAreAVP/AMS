<?php

/**
 * Dashboard Controller
 * 
 * PHP version 5
 * 
 * @category   AMS
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@geekschicago.com>
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
 * @author     Nouman Tayyab <nouman@geekschicago.com>
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
		{
			redirect('records/index');
		}
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

		$data['at_crawford'] = json_decode($this->memcached_library->get('at_crawford'), TRUE);
		$data['total_hours'] = json_decode($this->memcached_library->get('total_hours'), TRUE);
		$data['percentage_hours'] = json_decode($this->memcached_library->get('percentage_hours'), TRUE);
		$data['total_region_digitized'] = json_decode($this->memcached_library->get('total_region_digitized'), TRUE);
		$data['total_hours_region_digitized'] = json_decode($this->memcached_library->get('total_hours_region_digitized'), TRUE);
		/* Pie Chart for All Formats Start */
		$pie_total_completed = $this->dashboard_model->pie_total_completed();
		$pie_total_scheduled = $this->dashboard_model->pie_total_scheduled();
		$pie_total = $pie_total_completed->total + $pie_total_scheduled->total;
		$pie_total = ($pie_total == 0) ? 1 : $pie_total;
		$data['pie_total_completed'] = (int) ($pie_total_completed->total * 100) / $pie_total;
		$data['pie_total_scheduled'] = (int) ($pie_total_scheduled->total * 100) / $pie_total;
		/* Pie Chart for All Formats End */
		/* Pie Chart for Radio Formats Start */
		$pie_total_completed = $this->dashboard_model->pie_total_radio_completed();
		$pie_total_scheduled = $this->dashboard_model->pie_total_radio_scheduled();
		$pie_total = $pie_total_completed->total + $pie_total_scheduled->total;
		$pie_total = ($pie_total == 0) ? 1 : $pie_total;
		$data['pie_total_radio_completed'] = (int) round(($pie_total_completed->total * 100) / $pie_total);
		$data['pie_total_radio_scheduled'] = (int) round(($pie_total_scheduled->total * 100) / $pie_total);
		/* Pie Chart for Radio Formats End */
		/* Pie Chart for Radio Formats Start */
		$pie_total_completed = $this->dashboard_model->pie_total_tv_completed();
		$pie_total_scheduled = $this->dashboard_model->pie_total_tv_scheduled();
		$pie_total = $pie_total_completed->total + $pie_total_scheduled->total;
		$pie_total = ($pie_total == 0) ? 1 : $pie_total;
		$data['pie_total_tv_completed'] = round((int) ($pie_total_completed->total * 100) / $pie_total);
		$data['pie_total_tv_scheduled'] = round((int) ($pie_total_scheduled->total * 100) / $pie_total);
		/* Pie Chart for Radio Formats End */
		$this->load->view('dashboard/index', $data);
	}

}

// END Dashboard Controller

// End of file dashboard.php 
/* Location: ./application/controllers/dashboard.php */