<?php

/**
 * AMS Archive Management System
 * 
 * import_helper
 * 
 * PHP version 5
 * 
 * @category   AMS
 * @package    CI
 * @subpackage helper
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @copyright  Copyright (c) WGBH (http://www.wgbh.org/). All Rights Reserved.
 * @license    http://www.gnu.org/licenses/gpl.txt GPLv3
 * @version    GIT: <$Id>
 * @link       https://github.com/avpreserve/AMS
 */
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
