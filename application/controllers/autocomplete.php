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
		$this->layout = 'main_layout.php';
		$this->load->model('dx_auth/user_profile', 'user_profile');
		$this->load->model('dx_auth/users', 'users');
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

		if ($this->user_detail)
		{
			if ($this->is_station_user)
				$station_id = $this->station_id;
			else
				$station_id = $this->uri->segment(3);
			$this->load->model('mint_model', 'mint');
			$this->mint->insert_record(array('user_id' => $this->user_id, 'station_id' => $station_id));
			$username = explode('@', $this->user_detail->email);
			$data['user_id'] = $this->user_id;
			$data['email'] = $this->user_detail->email;
			$data['first_name'] = $this->user_detail->first_name;
			$data['last_name'] = $this->user_detail->last_name;
			if ($this->user_detail->role_id == 1 || $this->user_detail->role_id == 2)
				$data['rights'] = 7;
			else
				$data['rights'] = 4;
			/* Already we have mint user */
			if ( ! empty($this->user_detail->mint_user_id) && $this->user_detail->mint_user_id != NULL)
			{
				$data['mint_id'] = $this->user_detail->mint_user_id;
			}
			else /* Need to Create a new mint user */
			{
				$data['mint_id'] = NULL;
			}
			$this->load->view('mint_login', $data);
		}
		else
		{
			show_error('Something went wrong please try again.');
		}
	}

	public function update_user()
	{
		if (isAjax())
		{
			$mint_id = $this->input->get('mint_id');
			$user_id = $this->input->get('user_id');
			$this->user_profile->set_profile($user_id, array('mint_user_id	' => $mint_id));
			echo json_encode(array('success' => 'true'));
			exit_function();
		}
		show_404();
	}

}

// END Dashboard Controller

// End of file dashboard.php 
/* Location: ./application/controllers/dashboard.php */