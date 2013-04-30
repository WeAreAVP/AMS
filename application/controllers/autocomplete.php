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

	public function values()
	{
		$term = $this->input->get('term');
		$table = $this->input->get('table');
		$column = $this->input->get('column');
		$source = $this->autocomplete->get_autocomplete_value($table, $column, $term);
		$autoSource = array();

		foreach ($source as $key => $value)
		{
			$autoSource[$key] = $value->value;
		}
		echo json_encode($autoSource);
		exit_function();
	}

	public function mint_login()
	{
		debug($this->user_detail);exit;
	}

}

// END Dashboard Controller

// End of file dashboard.php 
/* Location: ./application/controllers/dashboard.php */