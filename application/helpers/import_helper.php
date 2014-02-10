<?php

function enable_errors()
{
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
}

function convert_file_to_xml($file_path)
{
	$data = file_get_contents($file_path);
	$x = @simplexml_load_string($data);
	return xmlObjToArr($x);
}
