<?php

/**
	* Migrate Model.
	*
	* @package    AMS
	* @subpackage Migrate Model
	* @author     Nouman Tayyab
	*/
class	Migrate_Model	extends	CI_Model
{

				/**
					* constructor. set table name amd prefix
					* 
					*/
				function	__construct	()
				{
								parent::__construct	();

								$this->_prefix	=	'';
								$this->_table	=	'';
				}

				/**
					* insert the records in tracing_info
					* 
					* @param array $data
					* @return boolean 
					*/
				function	insert_record	($data,	$table_name)
				{
								$this->db->insert	($table_name,	$data);
								return	$this->db->insert_id	();
				}

}

?>