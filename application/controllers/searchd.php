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
class Searchd extends MY_Controller
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

		$this->load->library('sphnixrt');
	}

	/**
	 * Dashboard Functionality
	 * 
	 * @return view dashboard/index
	 */
	function index()
	{


		$stations = $this->station_model->get_all();
		foreach ($stations as $key => $row)
		{
			if ($row->type == 0)
				$type = 'Radio';
			else if ($row->type == 1)
				$type = 'TV';
			else if ($row->type == 2)
				$type = 'JOINT';

			$record = array(
				's_station_name' => $row->station_name,
				'station_name' => $row->station_name,
				's_type' => $type,
				'type' => $type,
				's_address_primary' => $row->address_primary,
				'address_primary' => $row->address_primary,
				's_address_secondary' => $row->address_secondary,
				'address_secondary' => $row->address_secondary,
				's_city' => $row->city,
				'city' => $row->city,
				's_state' => $row->state,
				'state' => $row->state,
				's_zip' => $row->zip,
				'zip' => $row->zip,
				's_cpb_id' => $row->cpb_id,
				'cpb_id' => $row->cpb_id,
				'allocated_hours' => (int) $row->allocated_hours,
				'allocated_buffer' => (int) $row->allocated_buffer,
				'total_allocated' => (int) $row->total_allocated,
				'is_certified' => (int) $row->is_certified,
				'is_agreed' => (int) $row->is_agreed,
				'start_date' => (int) strtotime($row->start_date),
				'end_date' => (int) strtotime($row->end_date)
			);
			$this->sphnixrt->insert('stations', $record, $row->id);
		}
		$data = $this->sphnixrt->select('stations', array('start' => 0, 'limit' => 1000));
		debug($data, FALSE);
		echo count($data['records']);
		exit;
	}

}

// END Dashboard Controller

// End of file dashboard.php 
/* Location: ./application/controllers/dashboard.php */