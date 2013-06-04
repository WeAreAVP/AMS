<?php

if ( ! defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * AMS Migrate Controller
 * 
 * This controller migrate the changes into mysql database.
 *
 * @package		AMS
 * @subpackage	Migrate Controller
 * @category	Controllers
 * @author		Nouman Tayyab <nouman@avpreserve.com>
 */
class Migrate extends MY_Controller
{

	/**
	 * Constructor
	 * 
	 * Load the Migration Library.
	 * 
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->library('migration');
	}

	/**
	 * Migrate changes to mysql database.
	 *  
	 */
	function index()
	{
		if ( ! $this->migration->current())
		{
			show_error($this->migration->error_string());
		}
		else
		{
			echo 'Migrations changes are successfully applied.<br/>';
		}
	}

	/**
	 * Export Fixtures to database.
	 * 
	 */
	function export_fixtures()
	{

		$this->load->library('yaml');
		$this->load->model('migrate_model');
		$fixtures_folder = MAINPATH . '/tests/fixtures/';
		$default_fixtures = array('users', 'user_profile', 'stations');
		foreach ($default_fixtures as $value)
		{
			$input = $fixtures_folder . $value . '_fixt.yml';
			$fixture_array = $this->yaml->load($input);
			if (isset($fixture_array) && ! empty($fixture_array))
			{

				foreach ($fixture_array as $key => $fixture)
				{
					$this->migrate_model->insert_record($fixture, $value);
				}
			}
		}
	}

}

// END Migrate Controller

/* End of file migrate.php */
/* Location: ./application/controllers/migrate.php */