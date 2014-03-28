<?php

/**
 * AMS Archive Management System
 * 
 * PHP version 5
 * 
 * @category   AMS
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @copyright  Copyright (c) WGBH (http://www.wgbh.org/). All Rights Reserved.
 * @license    http://www.gnu.org/licenses/gpl.txt GPLv3
 * @version    GIT: <$Id>
 * @link       https://github.com/avpreserve/AMS
 */

/**
 * Migrate Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @copyright  Copyright (c) WGBH (http://www.wgbh.org/). All Rights Reserved.
 * @license    http://www.gnu.org/licenses/gpl.txt GPLv3
 * @link       https://ams.americanarchive.org
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