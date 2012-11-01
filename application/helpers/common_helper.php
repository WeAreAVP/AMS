<?php

function active_anchor($orignal_class, $orignal_method, $class = 'active') {
    $CI = & get_instance();

    if ($CI->router->class == $orignal_class) {
        if (is_array($orignal_method)) {
            if (in_array($CI->router->method, $orignal_method)) {
                return $class;
            }
        } else {
            if ($CI->router->method == $orignal_method) {
                return $class;
            }
        }
    }

    if ($CI->router->class == $orignal_class && !$orignal_method) {
        return $class;
    }
    return '';
}

//get 2d array of key should be route and array of values should be method
function is_route_method($route_method) {

    $CI = & get_instance();
    foreach ($route_method as $route => $method) {
        if ($CI->router->class == $route && in_array($CI->router->method, $method))
            return true;
    }
    return false;
}

function isAjax() {
    return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == "XMLHttpRequest");
}

function link_js($file) {

    return '<script type="text/javascript" src="' . base_url() . 'js/' . $file . '"></script>';
}

/* Sent Email if $this->sent_now is set true */

function send_email($to, $from, $subject, $message, $reply_to = '') {
    $CI = & get_instance();
    $CI->load->library('Email');
    $config['wordwrap'] = TRUE;
    $config['mailtype'] = 'html';
    $config['charset'] = 'utf-8';
    $config['protocol'] = 'sendmail';
    $email = $CI->email;
    $email->clear();
    $email->initialize($config);
    $email->from($from);
    $email->to($to);
    if (!empty($reply_to)) {
        $email->reply_to($reply_to);
    }
    $email->subject($subject);
    $email->message($message);
    if ($email->send())
        return true;
    else
        return false;
}

