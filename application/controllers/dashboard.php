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
		$data=array('digitized_format_name'=>NULL,
			'digitized_total'=>NULL,
			'scheduled_format_name'=>NULL,
			'scheduled_total'=>NULL,
			'material_goal'=>NULL,
			'at_crawford'=>NULL,
			'total_hours'=>NULL,
			'percentage_hours'=>NULL,
			);
		$data['digitized_format_name'] = json_decode($this->memcached_library->get('graph_digitized_format_name'), TRUE);
		$data['digitized_total'] = json_decode($this->memcached_library->get('graph_digitized_total'), TRUE);
		$data['scheduled_format_name'] = json_decode($this->memcached_library->get('graph_scheduled_format_name'), TRUE);
		$data['scheduled_total'] = json_decode($this->memcached_library->get('graph_scheduled_total'), TRUE);
		$data['material_goal'] = json_decode($this->memcached_library->get('material_goal'), TRUE);
		$data['at_crawford'] = json_decode($this->memcached_library->get('at_crawford'), TRUE);
		$data['total_hours'] = json_decode($this->memcached_library->get('total_hours'), TRUE);
		$data['percentage_hours'] = json_decode($this->memcached_library->get('percentage_hours'), TRUE);
		
		$this->load->view('dashboard/index', $data);
	}

}

// END Dashboard Controller

// End of file dashboard.php 
/* Location: ./application/controllers/dashboard.php */