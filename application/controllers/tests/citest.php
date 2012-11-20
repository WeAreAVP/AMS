<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Citest extends CIUnit_TestCase
{

    public function index()
    {

        $this->users();

$out = output();
		
		// Check if the content is OK
		$this->assertSame(0, preg_match('/(error|notice)/i', $out));

//        echo $this->unit->report();
    }

    public function users()
    {
        $this->CI->load->model('dx_auth/users', 'users');
        $this->CI->load->model('dx_auth/user_profile', 'user_profile');
        $this->CI->load->model('dx_auth/roles', 'roles');
        $this->CI->load->model('station_model');
        $data['current_role'] = $currentRoleID = $this->role_id;
        $data['is_ajax'] = false;
        $roles = $this->roles->get_roles_list($currentRoleID)->result();
        $params = null;
        if (isAjax())
        {
            $data['is_ajax'] = true;
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
            echo $this->CI->load->view('settings/user', $data, TRUE);
            exit;
        }
        $this->CI->load->view('settings/user', $data);
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */