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
		{
			redirect('records/index');
		}
	}
	function new_reverse(){
		$array=array(1,2,3,4,5);
		$new=array();
		for ($i=0;$i<count($array);$i++)
		{
			$val=array_pop($array);
			array_push($new, $val);
		}
		debug($new);
		$reverse=  array_reverse($array);
		
		foreach ($reverse as $value)
		{
			array_push($new, $value);
		}
		debug($new);
	}
	function mode()
	{
		$random = array(5, 7, 10, 3, 1, 7, 5, 6, 5);
		$mode_array = array();
		foreach ($random as $value)
		{
			if (isset($mode_array[$value]))
				$mode_array[$value] ++;
			else
				$mode_array[$value] = 1;
		}
		$mode_count=0;
		$mode_no=0;
		foreach ($mode_array as $key => $value)
		{
			if($mode_count < $value){
				$mode_count=$value;
				$mode_no=$key;
			}
		}
		echo $mode_no.'<br/>'.$mode_count;exit;
	}
	function reverse(){
		$array=array(1,2,3,4,5,6,7,8,9,10);
		$count=  count($array);
		$reverse=array_fill(0, $count-1, '');
		for($i=0;$i<$count/2;$i++){
			$reverse[$i]=$array[$count-1-$i];
			$reverse[$count-1-$i]=$array[$i];
		}
		debug($array,FALSE);
		debug($reverse);
		
	}
	function min_max(){
		$array=array(5,7,10,13,2,1,5,76);
		$min=0;
		$max=0;
		foreach ($array as  $value)
		{
			if($value > $max)
				$max=$value;
			if($value < $min)
				$min=$value;
			
		}
		echo 'Max Value: '.$max.'<br/>';
		echo 'Min Value: '.$min.'<br/>';
		exit;
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