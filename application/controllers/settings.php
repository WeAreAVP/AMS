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
        $this->load->model('dx_auth/user_profile', 'user_profile');

        if (!$this->dx_auth->is_logged_in()) {
            redirect('auth/login');
        }
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
        $data['users'] = $this->users->get_users()->result();
        $this->load->view('settings/user', $data);
    }

    function email_check($email) {
        $result = $this->dx_auth->is_email_available($email);
        if (!$result) {
            $this->form_validation->set_message('email_check', 'Email is already used by another user. Please choose another email address.');
        }

        return $result;
    }

    public function add_user() {
        $this->layout = 'default.php';
        $val = $this->form_validation;

        $val->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email|callback_email_check');
        $val->set_rules('password', 'Password', 'required');
        $val->set_rules('first_name', 'First Name', 'trim|required|xss_clean');
        $val->set_rules('last_name', 'Last Name', 'trim|required|xss_clean');
        $val->set_rules('phone_no', 'Phone #', 'trim|xss_clean');
        $val->set_rules('role', 'Role', 'trim|xss_clean|required');

        if ($this->input->post()) {
            if ($val->run()) {

                $record = array('email' => $val->set_value('email'),
                    'password' => $val->set_value('password'),
                    'role_id' => $val->set_value('role'),
                );
                $profile_data = array('first_name' => $val->set_value('first_name'),
                    'last_name' => $val->set_value('last_name'),
                    'phone_no' => $val->set_value('phone_no'),
                );
                $id = $this->users->create_user($record);
                $profile_data['user_id']=$id;
                $this->user_profile->insert_profile($profile_data);
                $this->session->set_userdata('saved', 'Record is Successfully Saved');
                echo 'done';
                exit;
            } else {
                $errors = $val->error_string();
                $data['errors'] = $errors;
            }
        }

        $roles = $this->roles->get_all()->result();
        foreach ($roles as $value) {
            $data['roles'][$value->id] = $value->name;
        }



        echo $this->load->view('settings/add_user', $data, TRUE);
    }

    public function edit_user() {
        $data['users'] = $this->users->get_users()->result();
        $this->load->view('settings/user', $data);
    }

    public function delete_user() {
        $data['users'] = $this->users->get_users()->result();
        $this->load->view('settings/user', $data);
    }

}

?>