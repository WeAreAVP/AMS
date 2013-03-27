<?php

/**
 * Dashboard Controller
 * 
 * PHP version 5
 * 
 * @category   Time Tracking
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@geekschicago.com>
 * @license    XohoTech http://xohotech.com
 * @version    GIT: <$Id>
 * @link       http://timetracking.xohotech.com
 */

/**
 * Dashboard Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@geekschicago.com>
 * @license    XohoTech http://xohotech.com
 * @link       http://timetracking.xohotech.com
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
		$this->load->model('instantiations_model', 'instantiation');
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
		/* Start Graph Get Digitized Formats  */
		$total_digitized = $this->instantiation->get_digitized_formats();
		$data['digitized_format_name'] = NULL;
		$data['digitized_total'] = NULL;
		$dformat_array = array();
		foreach ($total_digitized as $digitized)
		{
			if ( ! isset($dformat_array[$digitized->format_name]))
				$dformat_array[$digitized->format_name] = 1;
			else
				$dformat_array[$digitized->format_name] = $dformat_array[$digitized->format_name] + 1;
		}
		foreach ($dformat_array as $index => $format)
		{
			$data['digitized_format_name'][] = $index;
			$data['digitized_total'][] = (int) $format;
		}

		/* End Graph Get Digitized Formats  */
		/* Start Graph Get Scheduled Formats  */
		$total_scheduled = $this->instantiation->get_scheduled_formats();
		$data['scheduled_format_name'] = NULL;
		$data['scheduled_total'] = NULL;

		$format_array = array();
		foreach ($total_scheduled as $scheduled)
		{

			if ( ! isset($format_array[$scheduled->format_name]))
				$format_array[$scheduled->format_name] = 1;
			else
				$format_array[$scheduled->format_name] = $format_array[$scheduled->format_name] + 1;
		}
		foreach ($format_array as $index => $format)
		{
			$data['scheduled_format_name'][] = $index;
			$data['scheduled_total'][] = (int) $format;
		}
		/* End Graph Get Scheduled Formats  */
		/* Start Meterial Goal  */
		$data['material_goal'] = $this->instantiation->get_digitized_hours();
		/* End Meterial Goal  */
		/* Start Hours at crawford  */
		foreach ($this->config->item('messages_type') as $index => $msg_type)
		{
			if ($msg_type === 'Materials Received Digitization Vendor')
			{
				$data['msg_type'] = $index;
			}
		}

		$hours_at_craword = $this->station_model->get_hours_at_crawford($data['msg_type']);

		$data['at_crawford'] = 0;
		foreach ($hours_at_craword as $hours)
		{
			$data['at_crawford'] = $data['at_crawford'] + $hours->total;
		}
		/* End Hours at crawford  */
		/* Start goal hours  */
		$data['total_goal'] = $this->instantiation->get_material_goal();
		$digitized_hours = $this->instantiation->get_digitized_hours();
		$data['total_hours'] = $this->abbr_number((isset($data['total_goal']->total)) ? $data['total_goal']->total : 0);
		$data['percentage_hours'] = round(((isset($digitized_hours->total)) ? $digitized_hours->total : 0 * 100) / (isset($data['total_goal']->total)) ? $data['total_goal']->total : 0);

		/* End goal hours  */


		$this->load->view('dashboard/index', $data);
	}

	function abbr_number($size)
	{
		$size = preg_replace('/[^0-9]/', '', $size);
		$sizes = array("", "K", "M");
		if ($size == 0)
		{
			return('n/a');
		}
		else
		{
			return (round($size / pow(1000, ($i = floor(log($size, 1000)))), 0) . $sizes[$i]);
		}
	}

}

// END Dashboard Controller

// End of file dashboard.php 
/* Location: ./application/controllers/dashboard.php */