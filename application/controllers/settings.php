<?php

/**
 * Settings controller.
 *
 * @package    AMS
 * @subpackage Settings
 * @author     Nouman Tayyab
 */
class Settings extends MY_Controller {

    /**
     * constructor. Load layout,Model,Library and helpers
     * 
     */
    function __construct() {
        parent::__construct();
        $this->layout = 'main_layout.php';
        $this->load->model('dx_auth/users', 'users');
        $this->load->model('dx_auth/roles', 'roles');
        $this->load->model('station_model');
        $this->load->model('dx_auth/user_profile', 'user_profile');
    }

    /**
     * Redirect to users function
     *  
     */
    public function index() {
        $this->users();
    }

    /**
     * List all the users 
     *  
     */
    public function users() {
        $data['current_role'] = $currentRoleID = $this->session->userdata['DX_role_id'];
        $data['is_ajax'] = false;
        $roles = $this->roles->get_roles_list($currentRoleID)->result();
        $params = null;
        if (isAjax()) {
            $data['is_ajax'] = true;
            $params = array('station_id' => $this->input->post('station_id'), 'role_id' => $this->input->post('role_id'));
        }

        $data['users'] = $this->users->get_users($currentRoleID, $params)->result();
        $data['roles'][''] = 'Select';
        $data['stations'][''] = 'Select';
        foreach ($roles as $value) {
            $data['roles'][$value->id] = $value->name;
        }
        $stations = $this->station_model->get_all();

        foreach ($stations as $value) {
            $data['stations'][$value->id] = $value->station_name;
        }
        if (isAjax()) {
            echo $this->load->view('settings/user', $data, TRUE);
            exit;
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
    function email_check($email, $user_id = NULL) {

        $result = $this->dx_auth->is_email_available($email, $user_id);
        if (!$result) {
            $this->form_validation->set_message('email_check', 'Email is already used by another user. Please choose another email address.');
        }

        return $result;
    }

    /**
     * Create a new User
     *  
     */
    public function add_user() {
        $this->layout = 'default.php';
        $val = $this->form_validation;
        $currentRoleID = $this->session->userdata['DX_role_id'];
        if ($currentRoleID == 1 || $currentRoleID == 2 || $currentRoleID == 3) {
            $val->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email|callback_email_check');
            $val->set_rules('password', 'Password', 'required');
            $val->set_rules('first_name', 'First Name', 'trim|required|xss_clean');
            $val->set_rules('last_name', 'Last Name', 'trim|required|xss_clean');
            $val->set_rules('phone_no', 'Phone #', 'trim|xss_clean');
            $val->set_rules('role', 'Role', 'trim|xss_clean|required');
            $val->set_rules('station', 'Station', 'trim|xss_clean|required');

            if ($this->input->post()) {
                if ($val->run()) {

                    $record = array('email' => $val->set_value('email'),
                        'password' => crypt($this->dx_auth->_encode($val->set_value('password'))),
                        'role_id' => $val->set_value('role'),
                        'station_id' => $val->set_value('station'),
                    );
                    $profile_data = array('first_name' => $val->set_value('first_name'),
                        'last_name' => $val->set_value('last_name'),
                        'phone_no' => $val->set_value('phone_no'),
                    );
                    $id = $this->users->create_user($record);
                    $profile_data['user_id'] = $id;
                    $this->user_profile->insert_profile($profile_data);
                    $this->session->set_userdata('saved', 'Record is Successfully Saved');
                    echo 'done';
                    exit;
                } else {
                    $errors = $val->error_string();
                    $data['errors'] = $errors;
                }
            }
            $roles = $this->roles->get_roles_list($currentRoleID)->result();
            foreach ($roles as $value) {
                $data['roles'][$value->id] = $value->name;
            }
            $stations = $this->station_model->get_all();

            foreach ($stations as $value) {
                $data['stations_list'][$value->id] = $value->station_name;
            }
            echo $this->load->view('settings/add_user', $data, TRUE);
        } else {
            show_404();
        }
    }

    /**
     * Edit User
     * 
     * @param $user_id as uri segment 3 
     */
    public function edit_user() {
        $this->layout = 'default.php';
        $user_id = $this->uri->segment(3);
        $currentRoleID = $this->session->userdata['DX_role_id'];
        if ($currentRoleID == 1 || $currentRoleID == 2 || $currentRoleID == 3) {
            $val = $this->form_validation;

            $val->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email|callback_email_check[' . $user_id . ']');
            $val->set_rules('password', 'Password', 'trim|xss_clean');
            $val->set_rules('first_name', 'First Name', 'trim|required|xss_clean');
            $val->set_rules('last_name', 'Last Name', 'trim|required|xss_clean');
            $val->set_rules('phone_no', 'Phone #', 'trim|xss_clean');
            $val->set_rules('role', 'Role', 'trim|xss_clean|required');
            $val->set_rules('station', 'Station', 'trim|xss_clean|required');

            if ($this->input->post()) {
                if ($val->run()) {

                    $record = array('email' => $val->set_value('email'),
                        'role_id' => $val->set_value('role'),
                        'station_id' => $val->set_value('station'),
                    );
                    if ($val->set_value('password') != '')
                        $record['password'] = crypt($this->dx_auth->_encode($val->set_value('password')));

                    $profile_data = array('first_name' => $val->set_value('first_name'),
                        'last_name' => $val->set_value('last_name'),
                        'phone_no' => $val->set_value('phone_no'),
                    );
                    $this->users->set_user($user_id, $record);

                    $this->user_profile->set_profile($user_id, $profile_data);
                    $this->session->set_userdata('updated', 'Record is Successfully Updated');
                    echo 'done';
                    exit;
                } else {
                    $errors = $val->error_string();
                    $data['errors'] = $errors;
                }
            }
            $data['user_info'] = $this->users->get_user_detail($user_id)->row();


            $roles = $this->roles->get_roles_list($currentRoleID)->result();
            foreach ($roles as $value) {
                $data['roles'][$value->id] = $value->name;
            }
            $stations = $this->station_model->get_all();

            foreach ($stations as $value) { 
                $data['stations_list'][$value->id] = $value->station_name;
            }
            echo $this->load->view('settings/edit_user', $data, TRUE);
        } else {
            show_404();
        }
    }

    /**
     * Delete User
     * 
     * @param $user_id as uri segment 3 
     */
    public function delete_user() {
        $id = $this->uri->segment(3);
        $currentRoleID = $this->session->userdata['DX_role_id'];
        if ($currentRoleID == 1 || $currentRoleID == 2 || $currentRoleID == 3) {
            $currentUserID = $this->session->userdata['DX_user_id'];
            if ($currentUserID != $id) {
                $delete_user = $this->users->delete_user($id);
                $this->session->set_userdata('deleted', 'Record is Successfully Deleted');
            } else {
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
    public function edit_profile() {
        $user_id = $this->session->userdata['DX_user_id'];
        $val = $this->form_validation;

        $val->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email|callback_email_check[' . $user_id . ']');
        $val->set_rules('password', 'Password', 'trim|xss_clean');
        $val->set_rules('first_name', 'First Name', 'trim|required|xss_clean');
        $val->set_rules('last_name', 'Last Name', 'trim|required|xss_clean');
        $val->set_rules('phone_no', 'Phone #', 'trim|xss_clean');


        if ($this->input->post()) {
            if ($val->run()) {

                $record = array('email' => $val->set_value('email')
                );
                if ($val->set_value('password') != '')
                    $record['password'] = crypt($this->dx_auth->_encode($val->set_value('password')));

                $profile_data = array('first_name' => $val->set_value('first_name'),
                    'last_name' => $val->set_value('last_name'),
                    'phone_no' => $val->set_value('phone_no'),
                );
                $this->users->set_user($user_id, $record);

                $this->user_profile->set_profile($user_id, $profile_data);

                redirect('settings/edit_profile', 'location');
            } else {
                $errors = $val->error_string();
                $data['errors'] = $errors;
            }
        }
        $data['user_info'] = $this->users->get_user_detail($user_id)->row();

        $roles = $this->roles->get_all()->result();

        foreach ($roles as $value) {
            $data['roles'][$value->id] = $value->name;
        }
        $data['profile_edit'] = true;


        $this->load->view('settings/edit_user', $data);
    }

}

?>