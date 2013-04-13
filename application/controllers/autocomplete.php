<?php

/**
 * Autocomplete Controller
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
 * Autocomplete Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@geekschicago.com>
 * @license    AVPS http://ams.avpreserve.com
 * @link       http://ams.avpreserve.com
 */
class Autocomplete extends MY_Controller
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
		$this->load->model('autocomplete_model', 'autocomplete');
	}

	public function local_idenifier()
	{
		$source = $this->autocomplete->get_identifier_source($this->input->get('term'));
		$autoSource = array();

		foreach ($source as $key => $value)
		{
			$autoSource[$key] = $value->identifier_source;
		}
		echo json_encode($autoSource);
		exit_function();
	}
	public function title_source()
	{
		$source = $this->autocomplete->get_title_source($this->input->get('term'));
		$autoSource = array();

		foreach ($source as $key => $value)
		{
			$autoSource[$key] = $value->title_source;
		}
		echo json_encode($autoSource);
		exit_function();
	}

}

// END Dashboard Controller

// End of file dashboard.php 
/* Location: ./application/controllers/dashboard.php */