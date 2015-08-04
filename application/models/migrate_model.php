<?php

/**
 * AMS Archive Management System
 * 
 * 
 * PHP version 5
 * 
 * @category   AMS
 * @package    CI
 * @subpackage Model
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @copyright  Copyright (c) WGBH (http://www.wgbh.org/). All Rights Reserved.
 * @license    http://www.gnu.org/licenses/gpl.txt GPLv3
 * @version    GIT: <$Id>
 * @link       https://github.com/avpreserve/AMS
 */

/**
 * Migrate_Model Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Model
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @copyright  Copyright (c) WGBH (http://www.wgbh.org/). All Rights Reserved.
 * @license    http://www.gnu.org/licenses/gpl.txt GPLv3
 * @link       https://ams.americanarchive.org
 */
class Migrate_Model extends CI_Model
{

	/**
	 * constructor. set table name amd prefix
	 * 
	 */
	function __construct()
	{
		parent::__construct();
$this->db->save_queries = FALSE;
		$this->_prefix = '';
		$this->_table = '';
	}

	/**
	 * insert the records in tracing_info
	 * 
	 * @param array $data
	 * @return boolean 
	 */
	function insert_record($data, $table_name)
	{
		$this->db->insert($table_name, $data);
		return $this->db->insert_id();
	}

}

?>