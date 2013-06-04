<?php

/**
 * Settings Controller
 * 
 * PHP version 5
 * 
 * @category   AMS
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @license    CPB http://nouman.com
 * @version    GIT: <$Id>
 * @link       http://amsqa.avpreserve.com
 */

/**
 * Settings Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Controller
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @license    CPB http://nouman.com
 * @link       http://amsqa.avpreserve.com
 */
class Settings extends MY_Controller
{

	/**
	 * Constructor
	 * 
	 * Load the layout.
	 * 
	 */
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * Redirect to users function
	 * 
	 * @return redirect to index method 
	 */
	public function index()
	{
		$this->users();
	}

	/**
	 * List all the users.
	 * 
	 * It receives 2 post parameters with ajax call for user filteration
	 * 
	 * @return settings/user view
	 */
	public function users()
	{
		$data['current_role'] = $currentRoleID = $this->role_id;
		$data['is_ajax'] = FALSE;
		$roles = $this->roles->get_roles_list($currentRoleID)->result();
		$params = null;
		if (isAjax())
		{
			$data['is_ajax'] = TRUE;
			$params = array('station_id' => $this->input->post('station_id'), 'role_id' => $this->input->post('role_id'));
		}
		$data['users'] = $this->users->get_users($currentRoleID, $params)->result();
		$data['roles'][''] = 'Select';
		$data['stations'][''] = 'Select';
		foreach ($roles as $value)
		{
			$data['roles'][$value->id] = $value->name;
		}
		$stations = $this->station_model->get_all();

		foreach ($stations as $value)
		{
			$data['stations'][$value->id] = $value->station_name;
		}
		if (isAjax())
		{
			echo $this->load->view('settings/user', $data, TRUE);
			return TRUE;
		}
		$this->load->view('settings/user', $data);
	}

	/**
	 * Check if email is already exist in database
	 * 
	 * @param $email 
	 * @param $user_id
	 * @return string 
	 */
	function email_check($email, $user_id = NULL)
	{

		$result = $this->dx_auth->is_email_available($email, $user_id);
		if ( ! $result)
		{
			$this->form_validation->set_message('email_check', 'Email is already used by another user. Please choose another email address.');
		}

		return $result;
	}

	/**
	 * Create a new User
	 *
	 * @return settings/user view
	 */
	public function add_user()
	{
		$this->layout = 'default.php';
		$val = $this->form_validation;
		$currentRoleID = $this->role_id;
		if ($currentRoleID == 1 || $currentRoleID == 2 || $currentRoleID == 3)
		{
			$val->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email|callback_email_check');
			$val->set_rules('password', 'Password', 'required');
			$val->set_rules('first_name', 'First Name', 'trim|required|xss_clean');
			$val->set_rules('last_name', 'Last Name', 'trim|required|xss_clean');
			$val->set_rules('phone_no', 'Phone #', 'trim|xss_clean');
			$val->set_rules('title', 'Title', 'trim|xss_clean');
			$val->set_rules('fax', 'Fax', 'trim|xss_clean');
			$val->set_rules('address', 'Address', 'trim|xss_clean');

			$val->set_rules('role', 'Role', 'trim|xss_clean|required');
			$val->set_rules('station', 'Station', 'trim|xss_clean');

			if ($this->input->post())
			{
				if ($val->run())
				{

					$record = array('email' => $val->set_value('email'),
						'password' => crypt($this->dx_auth->_encode($val->set_value('password'))),
						'role_id' => $val->set_value('role'),
					);
					if ($val->set_value('role') == 3 || $val->set_value('role') == 4)
						$record['station_id'] = $val->set_value('station');
					else
						$record['station_id'] = NULL;
					$profile_data = array('first_name' => $val->set_value('first_name'),
						'last_name' => $val->set_value('last_name'),
						'phone_no' => $val->set_value('phone_no'),
						'title' => $val->set_value('title'),
						'fax' => $val->set_value('fax'),
						'address' => $val->set_value('address'),
					);

					$id = $this->users->create_user($record);
					$profile_data['user_id'] = $id;
					$this->user_profile->insert_profile($profile_data);
					$this->session->set_userdata('saved', 'Record is Successfully Saved');
					echo 'done';
					exit;
				} else
				{
					$errors = $val->error_string();
					$data['errors'] = $errors;
				}
			}
			$roles = $this->roles->get_roles_list($currentRoleID)->result();
			foreach ($roles as $value)
			{
				$data['roles'][$value->id] = $value->name;
			}
			$stations = $this->station_model->get_all();

			foreach ($stations as $value)
			{
				$data['stations_list'][$value->id] = $value->station_name;
			}
			echo $this->load->view('settings/add_user', $data, TRUE);
		}
		else
		{
			show_404();
		}
	}

	/**
	 * Edit User
	 * 
	 * @param $user_id as uri segment 3 
	 */
	public function edit_user()
	{
		$this->layout = 'default.php';
		$user_id = $this->uri->segment(3);
		$currentRoleID = $this->role_id;
		if ($currentRoleID == 1 || $currentRoleID == 2 || $currentRoleID == 3)
		{
			$val = $this->form_validation;

			$val->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email|callback_email_check[' . $user_id . ']');
			$val->set_rules('password', 'Password', 'trim|xss_clean');
			$val->set_rules('first_name', 'First Name', 'trim|required|xss_clean');
			$val->set_rules('last_name', 'Last Name', 'trim|required|xss_clean');
			$val->set_rules('phone_no', 'Phone #', 'trim|xss_clean');
			$val->set_rules('title', 'Title', 'trim|xss_clean');
			$val->set_rules('fax', 'Fax', 'trim|xss_clean');
			$val->set_rules('address', 'Address', 'trim|xss_clean');
			$val->set_rules('role', 'Role', 'trim|xss_clean|required');
			$val->set_rules('station', 'Station', 'trim|xss_clean');

			if ($this->input->post())
			{
				if ($val->run())
				{

					$record = array('email' => $val->set_value('email'),
						'role_id' => $val->set_value('role'),
					);
					if ($val->set_value('role') == 3 || $val->set_value('role') == 4)
						$record['station_id'] = $val->set_value('station');
					else
						$record['station_id'] = NULL;
					if ($val->set_value('password') != '')
						$record['password'] = crypt($this->dx_auth->_encode($val->set_value('password')));

					$profile_data = array('first_name' => $val->set_value('first_name'),
						'last_name' => $val->set_value('last_name'),
						'phone_no' => $val->set_value('phone_no'),
						'title' => $val->set_value('title'),
						'fax' => $val->set_value('fax'),
						'address' => $val->set_value('address'),
					);
					$this->users->set_user($user_id, $record);

					$this->user_profile->set_profile($user_id, $profile_data);
					$this->session->set_userdata('updated', 'Record is Successfully Updated');
					echo 'done';
					exit;
				} else
				{
					$errors = $val->error_string();
					$data['errors'] = $errors;
				}
			}
			$data['user_info'] = $this->users->get_user_detail($user_id)->row();


			$roles = $this->roles->get_roles_list($currentRoleID)->result();
			foreach ($roles as $value)
			{
				$data['roles'][$value->id] = $value->name;
			}
			$stations = $this->station_model->get_all();

			foreach ($stations as $value)
			{
				$data['stations_list'][$value->id] = $value->station_name;
			}
			echo $this->load->view('settings/edit_user', $data, TRUE);
		}
		else
		{
			show_404();
		}
	}

	/**
	 * Delete User
	 * 
	 * @param $user_id as uri segment 3 
	 */
	public function delete_user()
	{
		$id = $this->uri->segment(3);
		$currentRoleID = $this->role_id;
		if ($currentRoleID == 1 || $currentRoleID == 2 || $currentRoleID == 3)
		{
			$currentUserID = $this->user_id;
			;
			if ($currentUserID != $id)
			{
				$this->user_profile->delete_profile($id);
				$this->users->delete_user($id);
				$this->session->set_userdata('deleted', 'Record is Successfully Deleted');
			}
			else
			{
				$this->session->set_userdata('deleted', 'You cannot delete currently active user.');
			}

			redirect('settings/index', 'location');
		}
		else
			show_404();
	}

	/**
	 * Update current user profile
	 *  
	 */
	public function edit_profile()
	{
		$user_id = $this->user_id;
		$val = $this->form_validation;
		$data['user_info'] = $this->users->get_user_detail($user_id)->row();
		$val->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email|callback_email_check[' . $user_id . ']');
		$val->set_rules('password', 'Password', 'trim|xss_clean');
		$val->set_rules('first_name', 'First Name', 'trim|required|xss_clean');
		$val->set_rules('last_name', 'Last Name', 'trim|required|xss_clean');
		$val->set_rules('phone_no', 'Phone #', 'trim|xss_clean');
		$val->set_rules('station', 'Station', 'trim|xss_clean|required');
		$val->set_rules('fax', 'Fax', 'trim|xss_clean');
		$val->set_rules('address', 'Address', 'trim|xss_clean|required');
		$val->set_rules('title', 'Title', 'trim|xss_clean');
		$val->set_rules('address', 'Address', 'trim|xss_clean');

		if ($this->input->post())
		{
			if ($val->run())
			{

				$record['email'] = $val->set_value('email');
				if ($data['user_info']->role_id == 3 || $data['user_info']->role_id == 4)
					$record['station_id'] = $val->set_value('station');
				else
					$record['station_id'] = NULL;
				if ($val->set_value('password') != '')
					$record['password'] = crypt($this->dx_auth->_encode($val->set_value('password')));

				$profile_data = array('first_name' => $val->set_value('first_name'),
					'last_name' => $val->set_value('last_name'),
					'phone_no' => $val->set_value('phone_no'),
					'title' => $val->set_value('title'),
					'fax' => $val->set_value('fax'),
					'address' => $val->set_value('address'),
				);
				$this->users->set_user($user_id, $record);

				$this->user_profile->set_profile($user_id, $profile_data);

				redirect('settings/edit_profile', 'location');
			} else
			{
				$errors = $val->error_string();
				$data['errors'] = $errors;
			}
		}


		$roles = $this->roles->get_all()->result();

		foreach ($roles as $value)
		{
			$data['roles'][$value->id] = $value->name;
		}
		$stations = $this->station_model->get_all();

		foreach ($stations as $value)
		{
			$data['stations_list'][$value->id] = $value->station_name;
		}
		$data['profile_edit'] = true;


		$this->load->view('settings/edit_user', $data);
	}

}

// END Settings Controller

/* End of file settings.php */
/* Location: ./application/controllers/settings.php */