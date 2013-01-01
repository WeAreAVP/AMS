<?php

/**
	* Cron Model.
	*
	* @package    AMS
	* @subpackage Cron_model
	* @author     Ali Raza
	*/
class	Cron_Model	extends	CI_Model
{

				/**
					* constructor. set table name amd prefix
					* 
					*/
				function	__construct	()
				{
								parent::__construct	();
								$this->_prefix	=	'';
								$this->_table	=	'process_pbcore_data';
								$this->_table_data_folders	=	'data_folders';
				}

				/*
					 @Get process_pbcore_data through file_path
					 @return object
					*/

				function	get_pbcore_file_by_path	($file_path)
				{
								$this->db->select	("*");
								$this->db->from	($this->_table);
								$this->db->where	("file_path",	$file_path);
								$res	=	$this->db->get	();
								if	(isset	($res)	&&	!	empty	($res))
								{
												return	$res->row	();
								}
								return	false;
				}
				/*
					 @Get process_pbcore_data through file_path
					 @return object
					*/

				function	is_pbcore_file_by_path	($file_path)
				{
								$this->db->select	("id");
								$this->db->from	($this->_table);
								$this->db->where	("file_path",	$file_path);
								$this->db->limit(1);
								$res	=	$this->db->get	();
								echo $this->db->last_query();
								exit();
								if	(isset	($res)	&&	!	empty	($res))
								{
												return	true;
								}
								return	false;
				}

				/*
					 @Get process_pbcore_data through data_folder_id
					 @return object
					*/

				function	get_pbcore_file_by_folder_id	($data_folder_id,	$offset	=	0,	$limit	=	20)
				{
								$this->db->select	("*");
								$this->db->from	($this->_table);
								$this->db->where	("data_folder_id ",	$data_folder_id);
								$this->db->where	("is_processed ",	0);
								$this->db->limit	($limit,	$offset);
								$res	=	$this->db->get	();
								if	(isset	($res))
								{
												return	$res->result	();
								}
								return	false;
				}

				/*
					 @Get process_pbcore_data un-processed files count through data_folder_id
					 @return object
					*/

				function	get_pbcore_file_count_by_folder_id	($data_folder_id)
				{
								$this->db->select	("COUNT(id) AS total");
								$this->db->from	($this->_table);
								$this->db->where	("data_folder_id ",	$data_folder_id);
								$this->db->where	("is_processed ",	0);
								$res	=	$this->db->get	();
								if	(isset	($res))
								{
												$row	=	$res->row	();
												return	$row->total;
								}
								return	false;
				}

				/*
					 @Get data folder through folder_path
					 @return object
					*/

				function	get_data_folder_id_by_path	($folder_path)
				{
								$this->db->select	("id");
								$this->db->from	($this->_table_data_folders);
								$this->db->where	("folder_path LIKE ",	$folder_path);
								$this->db->limit(1);
								$res	=	$this->db->get	();
								if	(isset	($res)	&&	!	empty	($res))
								{
												$folder	=	$res->row	();
												if	(isset	($folder)	&&	!	empty	($folder))
												{
																return	$folder->id;
												}
								}
								return	false;
				}

				/*
					 @Get all data folder
					 @return object
					*/

				function	get_all_data_folder	()
				{
								$this->db->select	("*");
								$this->db->from	($this->_table_data_folders);
								$this->db->order_by('id','ASC');
								$res	=	$this->db->get	();
								if	(isset	($res)	&&	!	empty	($res))
								{
												$folders	=	$res->result	();
												if	(isset	($folders)	&&	!	empty	($folders))
												{
																return	$folders;
												}
								}
								return	false;
				}

				/*
					 @Get all data folder
					 @return object
					*/

				function	get_data_folder_by_id	($id)
				{
								$this->db->select	('*');
								$this->db->from	($this->_table_data_folders);
								$this->db->where	('id',	$id);
								$res	=	$this->db->get	();
								if	(isset	($res)	&&	!	empty	($res))
								{
												$folders	=	$res->row	();
												if	(isset	($folders)	&&	!	empty	($folders))
												{
																return	$folders;
												}
								}
								return	false;
				}

				/*
					 @Insert data into process_pbcore_data
					 @Perm Array of table data
					 @return insert id
					*/

				function	insert_prcoess_data	($data)
				{
								$this->db->insert	($this->_table,	$data);
								return	$this->db->insert_id	();
				}

				/*
					 @Insert data folder
					 @Perm Array of table data
					 @return insert id
					*/

				function	insert_data_folder	($data)
				{
								$this->db->insert	($this->_table_data_folders,	$data);
								return	$this->db->insert_id	();
				}
				/*
					* Scan Directory and Store Path in process_pbcore_data
					* @Perm Path of Directory
					* @Perm type of data
					*/

				function	scan_directory	($dir,	&$my_data_array)
				{
								$dir	=	rtrim	(trim	($dir,	'\\'),	'/')	.	'/';
								$d	=	@opendir	($dir);

								if	(	!	$d)
												die	('The directory '	.	$dir	.	' does not exists or PHP have no access to it<br>');
								while	(false	!==	($file	=	@readdir	($d)))
								{
												if	($file	!=	'.'	&&	$file	!=	'..')
												{
																if	(is_file	($dir	.	$file)	&&	$file	===	'manifest-md5.txt')
																{
																				$my_data_array[]=$dir	;
																}
																else
																{
																				if	(is_dir	($dir	.	$file)	&&	strpos	($dir,	'data')===false)
																				{
																								$this->scan_directory	($dir	.	$file,	$my_data_array);
																				}
																				else
																				{
																								continue;
																				}
																}
												}
								}
								@closedir	($d);
				}

				/*
					 @Get process_pbcore_data by type and is_processed=0
					 @Return object
					*/

				function	get_pbcore_data_type_is_processed	($type,	$is_processed	=	0)
				{
								$this->db->select	("*");
								$this->db->from	($this->_table);
								$this->db->where	("type LIKE ",	$file_type);
								$this->db->where	("is_processed ",	$is_processed);
								$res	=	$this->db->get	();
								if	(isset	($res))
								{
												return	$res->result	();
								}
								return	false;
				}

}