<?php

/**
 * Messages controller.
 *
 * @package    AMS
 * @subpackage Messeges Alerts
 * @author     Ali Raza, Nouman Tayyab
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
        $this->load->model('dx_auth/users', 'users');
        $this->load->model('messages_model', 'msgs');
        $this->user_id = 1;
    }
    /**
     * Redirect to inbox
     *  
     */
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
        $data['station_records'] = $this->station_model->get_all();


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
                $where['receiver_id'] = $_POST['stations'];
            }
        }
        $data['station_records'] = $this->station_model->get_all();
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
    /**
     * Get the message type and load the respective view. Receive an ajax call
     * 
     * @param $type as post parameter
     * @return html view for message type
     *  
     */
    public function get_message_type() {
        if (isAjax()) {
            $type = $this->input->post('type');
            $messagesType = $this->config->item('messages_type');
            $messageType = '_' . str_replace(' ', '_', strtolower($messagesType[$type]));
            $data['is_ajax'] = true;
            echo $this->load->view('messages/' . $messageType, $data, TRUE);
            exit;
        }
        show_404();
    }
    /**
     * Recieve the compose message parameteres. Store in database and send email
     * Receive an ajax call
     * 
     * @param $to receiver id
     * @param $from send id
     * @param $html for email body
     * @param $type message type
     * @param $subject email subject
     * @param $extaras receive the remaining fields as an array
     *  
     */
    public function compose() {
        if ($this->input->post()) {
            $to = $this->input->post('to');
            $html = $this->input->post('html');
            $from = $this->input->post('from');
            $type = $this->input->post('type');
            $subject = $this->input->post('subject');
            $extra = $this->input->post('extras');
            $extra = json_encode($extra);

            $this->load->library('email');

            $this->session->set_userdata('sent', 'Message Sent');
            if ($this->config->item('demo') == true) {
                $station_email = $this->config->item('to_email');
                $user_detail = $this->config->item('from_email');
            } else {
                $station_email = $this->station_model->get_station_by_id($to)->contact_email;
                $user_detail = $this->users->get_user_detail($from)->row()->email;
            }
            $this->email->from($user_detail);
            $this->email->to($station_email);

            $this->email->subject($subject);
            $this->email->message($html);

            $this->email->send();
            $data = array('sender_id' => $from, 'receiver_id' => $to, 'msg_type' => $type, 'subject' => $subject, 'msg_extras' => $extra, 'created_at' => date('Y-m-d h:m:i'));

            $this->msgs->add_msg($data);
            echo json_encode(array('success' => true));
            exit;
        } else {
            show_404();
        }
    }

    public function readmessage($message_id = '') {
        if ($message_id != '') {
            $data['result'] = $this->msgs->get_inbox_msgs($this->user_id, array("id" => $message_id));
						if(isset($data['result'] ) && !empty($data['result'] ) && $data['result'][0]->msg_status=='unread')
						{
							$this->msgs->update_msg_by_id($message_id, array("msg_status" =>'read',"read_at"=>date('Y-m-d H:i:s')));
							$this->total_unread = $this->msgs->get_unread_msgs_count($this->user_id);
						}
            $this->load->view('messages/read_msg', $data);
        }else {
            show_404();
        }
    }
		 public function readsentmessage($message_id = '') {
        if ($message_id != '') {
            $data['result'] = $this->msgs->get_inbox_msgs($this->user_id, array("id" => $message_id));
            $this->load->view('messages/read_msg', $data);
        }else {
            show_404();
        }
    }
		

}

?>