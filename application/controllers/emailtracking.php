<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class EmailTracking extends CI_Controller {

    function EmailTracking() {
        parent::__construct();
        $this->load->model('email_template_model', 'email_templates');
    }

    function index($email_alert) {
        if (isset($email_alert) && $email_queue_data = $this->email_templates->get_email_queue_by_id($email_alert)) {
            header('Content-type: image/png');
            echo(base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAAApJREFUCNdjYAAAAAIAAeIhvDMAAAAASUVORK5CYII='));
            if ($email_queue_data->is_email_read != 2) {
                $this->email_templates->update_email_queue_by_id($email_queue_data->id, array("is_email_read" => 2, "read_at" => date("Y-m-d H:i:s")));
            }
            exit();
        }
    }

}