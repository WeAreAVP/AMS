<?php

function active_anchor($orignal_class, $orignal_method, $class = 'active')
{
	$CI = & get_instance();

	if ($CI->router->class == $orignal_class)
	{
		if (is_array($orignal_method))
		{
			if (in_array($CI->router->method, $orignal_method))
			{
				return $class;
			}
		}
		else
		{
			if ($CI->router->method == $orignal_method)
			{
				return $class;
			}
		}
	}

	if ($CI->router->class == $orignal_class && ! $orignal_method)
	{
		return $class;
	}
	return '';
}

//get 2d array of key should be route and array of values should be method
function is_route_method($route_method)
{
	$CI = & get_instance();
	foreach ($route_method as $route => $method)
	{
		if ($CI->router->class == $route && in_array($CI->router->method, $method))
			return true;
	}
	return false;
}

function isAjax()
{
	return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == "XMLHttpRequest");
}

function link_js($file)
{
	return '<script type="text/javascript" src="' . base_url() . 'js/' . $file . '"></script>';
}

/* Sent Email if $this->sent_now is set true */

function send_email($to, $from, $subject, $message, $reply_to = '')
{
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
	if ( ! empty($reply_to))
	{
		$email->reply_to($reply_to);
	}
	$email->subject($subject);
	$email->message($message);
	echo $email->print_debugger();
	if ($email->send())
		return true;
	else
		return false;
}

function xmlObjToArr($obj)
{
	$namespace = $obj->getDocNamespaces(true);
	$namespace[NULL] = NULL;
	$children = array();
	$attributes = array();
	$name = strtolower((string) $obj->getName());
	$text = trim((string) $obj);
	if (strlen($text) <= 0)
	{
		$text = NULL;
	}
	// get info for all namespaces
	if (is_object($obj))
	{

		foreach ($namespace as $ns => $nsUrl)
		{
			// atributes
			$objAttributes = $obj->attributes($ns, true);
			foreach ($objAttributes as $attributeName => $attributeValue)
			{
				$attribName = strtolower(trim((string) $attributeName));
				$attribVal = trim((string) $attributeValue);
				if ( ! empty($ns))
				{
					$attribName = $ns . ':' . $attribName;
				}
				$attributes[$attribName] = $attribVal;
			}
			// children
			$objChildren = $obj->children($ns, true);
			foreach ($objChildren as $childName => $child)
			{
				$childName = strtolower((string) $childName);
				if ( ! empty($ns))
				{
					$childName = $ns . ':' . $childName;
				}
				$children[$childName][] = xmlObjToArr($child);
			}
		}
	}
	return array('name' => $name, 'text' => $text, 'attributes' => $attributes, 'children' => $children);
}

function duration($seconds_count)
{
	$delimiter = ':';
	$seconds = $seconds_count % 60;
	$minutes = floor($seconds_count / 60);
	$hours = floor($seconds_count / 3600);

	$seconds = str_pad($seconds, 2, "0", STR_PAD_LEFT);
	$minutes = str_pad($minutes, 2, "0", STR_PAD_LEFT) . $delimiter;

	if ($hours > 0)
	{
		$hours = str_pad($hours, 2, "0", STR_PAD_LEFT) . $delimiter;
	}
	else
	{
		$hours = '';
	}

	return $hours . ":" . $minutes . ":" . $seconds;
}

function name_slug($name)
{
	$random = rand(0, 1000365);
	$name = str_replace("/", "", trim($name));
	$name = str_replace("??", "q", trim($name));
	$name = str_replace(" ", "", trim($name));
	$name = str_replace("(", "", trim($name));
	$name = str_replace(")", "", trim($name));
	$name = str_replace(",", "", trim($name));
	$name = str_replace(".", "", trim($name));
//    $name = str_replace("'", "-", trim($name));
//    $name = str_replace("\"", "-", trim($name));
	$name = str_replace(";", "", trim($name));
	$name = str_replace(":", "", trim($name));
	$name = str_replace("&", "", trim($name));
	$name = strtolower($name);
	return $name . $random;
}

function exit_function()
{
	exit;
}

function debug($argument, $exit = TRUE)
{
	echo '<pre>';
	print_r($argument);
	echo '</pre>';
	if ($exit)
		exit;
}

function is_empty($str)
{
	if (isset($str) && ! empty($str) && $str !== NULL)
	{
		return FALSE;
	}
	return TRUE;
}

function sortByOneKey(array $array, $key, $manage_count = FALSE, $asc = TRUE)
{
	$CI = & get_instance();

	$result = array();

	$values = array();
	foreach ($array as $id => $value)
	{
		$values[$id] = isset($value[$key]) ? $value[$key] : '';
	}

	if ($asc)
	{
		asort($values);
	}
	else
	{
		arsort($values);
	}
	$count_greater = array();
	$count_lesser = array();
	foreach ($values as $index => $value)
	{
		if (trim(str_replace('(**)', '', $array[$index][$key])) != '')
		{
			if ($manage_count)
			{

				if ($array[$index]['@count'] >= 100)
				{
					$count_greater[] = $array[$index];
				}
				else
				{
					$count_lesser[] = $array[$index];
				}
			}
			else
			{
				$result[] = $array[$index];
			}
		}
	}
	if ($manage_count)
	{
		if ($CI->role_id != '20')
			$result = array_merge($count_greater, $count_lesser);
		else
			$result = $count_greater;
	}

	return $result;
}

/**
 * Display the output.
 * @global type $argc
 * @param type $s 
 */
function myLog($s)
{
	global $argc;
	if ($argc)
		$s.="\n";
	else
		$s.="<br>\n";
	echo date('Y-m-d H:i:s') . ' >> ' . $s;
	flush();
}

function deployment_display($msg, $status = 'FAILED')
{

	if ($status === 'FAILED')
		$color = 'color:red;';
	else
		$color = 'color:green;';

	return "$msg   [ <b style='$color'>$status</b> ] <br/>";
}

function flush_buffers()
{
	ob_end_flush();
	ob_flush();
	flush();
	ob_start();
}

function get_essence_track_annotation($essence_track_id)
{
	$CI = & get_instance();
	$CI->load->model('essence_track_model', 'essence_track');
	return $CI->essence_track->get_annotation_by_essence_track_id($essence_track_id);
}

function make_dir($folder_path, $filename = '')
{
	$complete_path = $folder_path . $filename;
	if ( ! is_dir($folder_path))
		mkdir($folder_path, 0777, TRUE);

	return $complete_path;
}
