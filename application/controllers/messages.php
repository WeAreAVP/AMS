<?php

/**
 * Settings controller.
 *
 * @package    AMS
 * @subpackage Messeges Alerts
 * @author     Ali Raza
 */
class Messages extends MY_Controller {

    /**
     *
     * constructor. Load layout,Model,Library and helpers
     * 
     */
    function __construct() {
        parent::__construct();

        $this->layout = 'main_layout.php';
        $this->load->library('Form_validation');
        $this->load->helper('form');
        $this->load->model('station_model');
        $this->load->model('messages_model', 'msgs');
        $this->user_id = 1;
    }

    public function index() {
        redirect('messages/inbox', 'location');
    }

    public function inbox() {

        if ($_POST) {
            if ($_POST['message_type']) {
                $where['msg_type'] = $_POST['message_type'];
            }
            if ($_POST['stations']) {
                $where['sender_id'] = $_POST['stations'];
            }
        }
        $data['results'] = $this->msgs->get_inbox_msgs($this->user_id, $where);
        $data['station_records'] =$this->station_model->get_all();
        
           
        if (isAjax()) {
            $data['is_ajax'] = true;
            echo $this->load->view('messages/inbox', $data, true);
            exit;
        } else {
            $data['is_ajax'] = false;
            $this->load->view('messages/inbox', $data);
        }
    }

    public function sent() {
        if ($_POST) {
            if ($_POST['message_type']) {
                $where['msg_type'] = $_POST['message_type'];
            }
            if ($_POST['stations']) {
                $where['sender_id'] = $_POST['stations'];
            }
        }
        $data['results'] = $this->msgs->get_sent_msgs($this->user_id, $where);
        if (isAjax()) {
            $data['is_ajax'] = true;
            echo $this->load->view('messages/sent', $data, true);
            exit;
        } else {
            $data['is_ajax'] = false;
            $this->load->view('messages/sent', $data);
        }
    }

    public function compose() {
        echo '<pre>';
        print_r($_REQUEST);
        exit;
    }

}

?>