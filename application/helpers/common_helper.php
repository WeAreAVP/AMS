<?php

function	active_anchor	($orignal_class,	$orignal_method,	$class	=	'active')
{
				$CI	=	&	get_instance	();

				if	($CI->router->class	==	$orignal_class)
				{
								if	(is_array	($orignal_method))
								{
												if	(in_array	($CI->router->method,	$orignal_method))
												{
																return	$class;
												}
								}
								else
								{
												if	($CI->router->method	==	$orignal_method)
												{
																return	$class;
												}
								}
				}

				if	($CI->router->class	==	$orignal_class	&&	!$orignal_method)
				{
								return	$class;
				}
				return	'';
}

//get 2d array of key should be route and array of values should be method
function	is_route_method	($route_method)
{
				$CI	=	&	get_instance	();
				foreach	($route_method	as	$route	=>	$method)
				{
								if	($CI->router->class	==	$route	&&	in_array	($CI->router->method,	$method))
												return	true;
				}
				return	false;
}

function	isAjax	()
{
				return	(isset	($_SERVER['HTTP_X_REQUESTED_WITH'])	&&	$_SERVER['HTTP_X_REQUESTED_WITH']	==	"XMLHttpRequest");
}

function	link_js	($file)
{
				return	'<script type="text/javascript" src="'	.	base_url	()	.	'js/'	.	$file	.	'"></script>';
}

/* Sent Email if $this->sent_now is set true */

function	send_email	($to,	$from,	$subject,	$message,	$reply_to	=	'')
{
				$CI	=	&	get_instance	();
				$CI->load->library	('Email');
				$config['wordwrap']	=	TRUE;
				$config['mailtype']	=	'html';
				$config['charset']	=	'utf-8';
				$config['protocol']	=	'sendmail';
				$email	=	$CI->email;
				$email->clear	();
				$email->initialize	($config);
				$email->from	($from);
				$email->to	($to);
				if	(!empty	($reply_to))
				{
								$email->reply_to	($reply_to);
				}
				$email->subject	($subject);
				$email->message	($message);
				if	($email->send	())
								return	true;
				else
								return	false;
}

function	xmlObjToArr	($obj)
{
				$namespace	=	$obj->getDocNamespaces	(true);
				$namespace[NULL]	=	NULL;
				$children	=	array	();
				$attributes	=	array	();
				$name	=	strtolower	((string)	$obj->getName	());
				$text	=	trim	((string)	$obj);
				if	(strlen	($text)	<=	0)
				{
								$text	=	NULL;
				}
				// get info for all namespaces
				if	(is_object	($obj))
				{
								foreach	($namespace	as	$ns	=>	$nsUrl)
								{
												// atributes
												$objAttributes	=	$obj->attributes	($ns,	true);
												foreach	($objAttributes	as	$attributeName	=>	$attributeValue)
												{
																$attribName	=	strtolower	(trim	((string)	$attributeName));
																$attribVal	=	trim	((string)	$attributeValue);
																if	(!empty	($ns))
																{
																				$attribName	=	$ns	.	':'	.	$attribName;
																}
																$attributes[$attribName]	=	$attribVal;
												}
												// children
												$objChildren	=	$obj->children	($ns,	true);
												foreach	($objChildren	as	$childName	=>	$child)
												{
																$childName	=	strtolower	((string)	$childName);
																if	(!empty	($ns))
																{
																				$childName	=	$ns	.	':'	.	$childName;
																}
																$children[$childName][]	=	xmlObjToArr	($child);
												}
								}
				}
				return	array	('name'							=>	$name,	'text'							=>	$text,	'attributes'	=>	$attributes,	'children'			=>	$children);
}

function	duration	($seconds_count)
{
				$delimiter	=	':';
				$seconds	=	$seconds_count	%	60;
				$minutes	=	floor	($seconds_count	/	60);
				$hours	=	floor	($seconds_count	/	3600);

				$seconds	=	str_pad	($seconds,	2,	"0",	STR_PAD_LEFT);
				$minutes	=	str_pad	($minutes,	2,	"0",	STR_PAD_LEFT)	.	$delimiter;

				if	($hours	>	0)
				{
								$hours	=	str_pad	($hours,	2,	"0",	STR_PAD_LEFT)	.	$delimiter;
				}
				else
				{
								$hours	=	'';
				}

				return	$hours	.	":"	.	$minutes	.	":"	.	$seconds;
}

function	name_slug	($name)
{
				$random	=	rand	(0,	1000365);
				$name	=	str_replace	("/",	"",	trim	($name));
				$name	=	str_replace	("??",	"q",	trim	($name));
				$name	=	str_replace	(" ",	"",	trim	($name));
				$name	=	str_replace	("(",	"",	trim	($name));
				$name	=	str_replace	(")",	"",	trim	($name));
				$name	=	str_replace	(",",	"",	trim	($name));
				$name	=	str_replace	(".",	"",	trim	($name));
//    $name = str_replace("'", "-", trim($name));
//    $name = str_replace("\"", "-", trim($name));
				$name	=	str_replace	(";",	"",	trim	($name));
				$name	=	str_replace	(":",	"",	trim	($name));
				$name	=	str_replace	("&",	"",	trim	($name));
				$name	=	strtolower	($name);
				return	$name	.	$random;
}

function	exit_function	()
{
				exit;
}

function	debug	($argument,	$exit	=	TRUE)
{
				echo	'<pre>';
				print_r	($argument);
				echo	'</pre>';
				if	($exit)
								exit;
}
function	is_empty	($str)
				{
								if	(isset	($str)	&&	!	empty	($str)	&&	$str	!==	NULL)
								{
												return	false;
								}
								return	true;
				}
				function sortByOneKey(array $array, $key, $asc = true) {
    $result = array();
        
    $values = array();
    foreach ($array as $id => $value) {
        $values[$id] = isset($value[$key]) ? $value[$key] : '';
    }
        
    if ($asc) {
        asort($values);
    }
    else {
        arsort($values);
    }
        
    foreach ($values as $key => $value) {
        $result[$key] = $array[$key];
    }
        
    return $result;
}