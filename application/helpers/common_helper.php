<?php

function active_anchor($orignal_class, $orignal_method, $class = 'active') {
    $CI = & get_instance();

    if ($CI->router->class == $orignal_class && $CI->router->method == $orignal_method) {
        return $class;
    }

    if ($CI->router->class == $orignal_class && !$orignal_method) { 
        return $class;
    }
    return '';
}

function isAjax() {
    return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == "XMLHttpRequest");
}

