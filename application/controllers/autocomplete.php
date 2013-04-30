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
//		$this->layout = 'default.php';
		if ($this->user_detail)
		{
			$username = explode('@', $this->user_detail->email);
			$data['email'] = $this->user_detail->email;
			$data['first_name'] = $this->user_detail->first_name;
			$data['last_name'] = $this->user_detail->last_name;
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
			$this->user_profile->set_profile($this->user_id, array('mint_user_id	' => $mint_id));
			echo json_encode(array('success' => 'true'));
			exit_function();
		}
		show_404();
	}

}

// END Dashboard Controller

// End of file dashboard.php 
/* Location: ./application/controllers/dashboard.php */