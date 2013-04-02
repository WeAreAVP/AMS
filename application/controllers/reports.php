<?php

/**
 * Reports Controller
 * 
 * PHP version 5
 * 
 * @category   AMS
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@geekschicago.com>
 * @license    CPB http://nouman.com
 * @version    GIT: <$Id>
 * @link       http://amsqa.avpreserve.com
 */

/**
 * Reports Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@geekschicago.com>
 * @license    CPB http://nouman.com
 * @link       http://amsqa.avpreserve.com
 */
class Reports extends MY_Controller
{

	/**
	 * Constructor
	 * 
	 * Load the layout
	 * 
	 */
	function __construct()
	{
		parent::__construct();

		if ($this->is_station_user)
		{
			redirect('records/index');
		}
	}

	/**
	 * List All types of reports
	 * 
	 * @return reports/index view 
	 */
	function index()
	{
		$data['station_records'] = $this->station_model->get_all();
		$this->load->view('reports/index', $data);
	}

	/**
	 * To List All Email Sent Through this System With Possible Filters
	 * 
	 * @return mixed
	 *
	 */
	function emailalerts()
	{
		$this->report_model->get_email_queue();
	}

	public function alerts_report()
	{
		$this->load->library('dompdf_lib');
		$html = '<div>Test</div>Test 123';
		$this->dompdf_lib->convert_html_to_pdf($html);
	}

}

// END Reports Controller

// End of file reports.php
/* Location: ./application/controllers/reports.php */