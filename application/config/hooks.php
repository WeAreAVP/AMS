<?php

if ( ! defined('BASEPATH'))
	exit('No direct script access allowed');
/*
  | -------------------------------------------------------------------------
  | Hooks
  | -------------------------------------------------------------------------
  | This file lets you define "hooks" to extend CI without hacking the core
  | files.  Please see the user guide for info:
  |
  |	http://codeigniter.com/user_guide/general/hooks.html
  |
 */
$hook['pre_system'][] = array(
	'class' => 'CI_Autoloader',
	'function' => 'register',
	'filename' => 'CI_Autoloader.php',
	'filepath' => 'hooks',
	'params' => array(APPPATH . 'base/')
);
//$hook['pre_controller'][] = array(
//	'class' => 'Pear_hook',
//	'function' => 'index',
//	'filename' => 'pear_hook.php',
//	'filepath' => 'hooks'
//);
$hook['display_override'][] = array('class' => 'Yield',
	'function' => 'doYield',
	'filename' => 'Yield.php',
	'filepath' => 'hooks'
);



/* End of file hooks.php */
/* Location: ./application/config/hooks.php */