<?php

/**
 * AMS Archive Management System
 * 
 * 
 * 
 * PHP version 5
 * 
 * @category   AMS
 * @package    CI
 * @subpackage Library
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @copyright  Copyright (c) WGBH (http://www.wgbh.org/). All Rights Reserved.
 * @license    http://www.gnu.org/licenses/gpl.txt GPLv3
 * @version    GIT: <$Id>
 * @link       https://github.com/avpreserve/AMS
 */
/**
 * Excel Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Library
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @copyright  Copyright (c) WGBH (http://www.wgbh.org/). All Rights Reserved.
 * @license    http://www.gnu.org/licenses/gpl.txt GPLv3
 * @link       https://ams.americanarchive.org
 */
require_once APPPATH . "/third_party/PHPExcel/PHPExcel.php";

class Excel extends PHPExcel
{

	public function __construct()
	{
		parent::__construct();
	}

}
