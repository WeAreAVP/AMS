<?php

/**
 * unit_testing controller.
 *
 * @package    AMS
 * @subpackage unit_testing
 * @author     Nouman Tayyab
 */
class Unit_Testing extends CI_Controller {

    /**
     * constructor. Load Model,Library and helpers
     * 
     */
    function __construct() {
        parent::__construct();
        $this->load->library('unit_test');
        $this->load->model('station_model');
        $this->load->model('sphinx_model');
        $this->load->model('dx_auth/users', 'users');
        $this->load->model('dx_auth/roles', 'roles');
        $this->load->model('dx_auth/user_profile', 'user_profile');
    }

    /**
     * Test Stations
     *  
     */
    public function station_test() {
        $test1 = $this->station_model->get_station_count();
        $expected_result = 98;
        $test_name = 'Station list count';
        echo $this->unit->run($test1, $expected_result, $test_name);
        echo '<br/>';
        $test2 = $this->station_model->get_all();
        $test_name = 'Station type';
        echo $this->unit->run($test2, 'is_array', $test_name);
        echo '<br/>';
        $test3 = $this->station_model->update_station(1, array('start_date' => '2011-10-12'));
        $test_name = 'Digitilization Start Date';
        echo $this->unit->run($test3, 'is_true', $test_name);

        echo '<br/>';
        $test4 = $this->station_model->apply_filter(1, 1);
        $test4 = count($test4);
        $test_name = 'Filter (Certified=Yes, Agree=Yes)';
        echo $this->unit->run($test4, 0, $test_name);

        echo '<br/>';
        $test5 = $this->station_model->apply_filter(1, 0);
        $test5 = count($test5);
        $expected_result = 8;
        $test_name = 'Filter (Certified=Yes, Agree=No)';
        echo $this->unit->run($test5, $expected_result, $test_name);

        echo '<br/>';
        $test6 = $this->station_model->apply_filter(0, 1);
        $test6 = count($test6);
        $expected_result = 0;
        $test_name = 'Filter (Certified=No, Agree=Yes)';
        echo $this->unit->run($test6, $expected_result, $test_name);

        echo '<br/>';
        $test7 = $this->station_model->apply_filter(0, 0);
        $test7 = count($test7);
        $expected_result = 90;
        $test_name = 'Filter (Certified=No, Agree=No)';
        echo $this->unit->run($test7, $expected_result, $test_name);

        echo '<br/>';
        $test8 = $this->station_model->apply_filter('', '');
        $test8 = count($test8);
        $expected_result = 98;
        $test_name = 'Filter (Certified=\'\', Agree=\'\')';
        echo $this->unit->run($test8, $expected_result, $test_name);
    }

    /**
     * User Testing Function will be called from this function
     *  
     */
    function usertesting() {
        $this->userlisttesting();
        $this->addusertesting();
        $this->editusertesting();
        $this->deleteusertesting();
    }

    /**
     * List users testing
     *  
     */
    function userlisttesting() {
        $test1 = $this->users->get_users(1, null)->num_rows;
        $expected_result = 2;
        $test_name = 'User list count';
        echo $this->unit->run($test1, $expected_result, $test_name);
        echo '<br/>';
    }

    /**
     * Add user testing
     *  
     */
    function addusertesting() {
        $record = array('email' => 'testing@abc.com',
            'password' => crypt($this->dx_auth->_encode('nouman')),
            'role_id' => '1',
            'station_id' => '1',
        );
        $profile_data = array('first_name' => 'Test',
            'last_name' => 'Case',
            'phone_no' => '1234567',
        );
        $id = $this->users->create_user($record);
        $profile_data['user_id'] = $id;
        $result = $this->user_profile->insert_profile($profile_data);

        $expected_result = 'is_numeric';
        $test_name = 'Add New User';
        echo $this->unit->run($result, $expected_result, $test_name);
        echo '<br/>';
    }

    /**
     * Edit user/ Edit Profile testing
     *  
     */
    function editusertesting() {
        $record = array('email' => 'testing@abc.com',
            'role_id' => '1',
            'station_id' => '1',
        );

        $profile_data = array('first_name' => 'Test Edit',
            'last_name' => 'Case',
            'phone_no' => '1234567',
        );
        $this->users->set_user('18', $record);

        $result = $this->user_profile->set_profile('17', $profile_data);
        $expected_result = 'is_true';
        $test_name = 'Edit User';
        echo $this->unit->run($result, $expected_result, $test_name);
        echo '<br/>';
    }

    /**
     * Delete User Tesing
     *  
     */
    function deleteusertesting() {
        $this->user_profile->delete_profile('18');
        $result = $delete_user = $this->users->delete_user('18');

        $expected_result = 'is_true';
        $test_name = 'Delete User';
        echo $this->unit->run($result, $expected_result, $test_name);
        echo '<br/>';
    }

    /*
      Test For Sation Filter Search
     */

    function searchsphinxtest() {
        echo '<br/>';
        $test1 = $this->sphinx_model->search_stations('AK');
        $test1 = count($test1['records']);
        $expected_result = 5;
        $test_name = 'State=AK ';
        echo $this->unit->run($test1, $expected_result, $test_name);

        echo '<br/>';
        $test2 = $this->sphinx_model->search_stations('99576');
        $test2 = count($test2['records']);
        $expected_result = 1;
        $test_name = 'Zip=99576 ';
        echo $this->unit->run($test2, $expected_result, $test_name);


        echo '<br/>';
        $test3 = $this->sphinx_model->search_stations('General Manager');
        $test3 = count($test3['records']);
        $expected_result = 14;
        $test_name = 'Contact Title=General Manager ';
        echo $this->unit->run($test3, $expected_result, $test_name);
    }

    public function templatetesting() {
        $this->emailtemplates->sent_now = true;
        $this->emailtemplates->queue_email('ForgetPassword', 'ali.raza@purelogics.net', array("web_name" => $this->config->item('DX_website_name'), "uri" => "test_uri", "new_password" => "testpassword",
            "key" => "testkey", "web_master" => $this->config->item('DX_webmaster_email')));
    }

}

?>