<?php

/**
	* Google Doc Controller
	*  
	* PHP version 5
	* 
	* @category   Controllers
	* @package    AMS
	* @subpackage Google_Documents_Controller
	* @author     Ali Raza <ali@geekschicago.com>
	* @author     Nouman Tayyab <nouman@avpreserve.com>
	* @license    ams http://ams.appreserve.com
	* @link       http://ams.appreserve.com
	*/

/**
	* Googledoc   controller.
	* 
	* @category   Controllers
	* @package    AMS
	* @subpackage Google_Documents_Controller
	* @author     Ali Raza <ali@geekschicago.com>
	* @author     Nouman Tayyab <nouman@avpreserve.com>
	* @license    ams http://ams.appreserve.com
	* @link       http://ams.appreserve.com
	*/
class	Refine	extends	MY_Controller
{

				/**
					* Constructor.
					* 
					* Load the Models,Library
					* 
					* @return 
					*/
				function	__construct()
				{
								parent::__construct();
								$this->load->library('googlerefine');
								$this->load->model('refine_modal');
								$this->load->model('sphinx_model',	'sphinx');
				}

				function	create()
				{

								$project_name	=	'Refine_'	.	time();
								$file_path	=	'/var/www/html/uploads/Workbook7.csv';
								$this->googlerefine->create_project($project_name,	$file_path);
				}

				function	export()
				{
								$params	=	array('search'				=>	'');
								$records	=	$this->sphinx->instantiations_list($params);
								$total_loop	=	ceil($records['total_count']	/	15000);
								$query	=	$this->refine_modal->export_refine_csv(TRUE);

								$record	=	array('user_id'						=>	$this->user_id,	'is_active'				=>	0,	'export_query'	=>	$query,	'query_loop'			=>	$total_loop);
								$job_id	=	$this->refine_modal->insert_job($record);
								$filename	=	'google_refine_'	.	time()	.	'.csv';
								$fp	=	fopen("uploads/google_refine/$filename",	'a');
								$line	=	"Organization,Asset Title,Description,Instantiation ID,Instantiation ID Source,Generation,Nomination,Nomination Reason,Media Type,Language,Ins_id\n";
								fputs($fp,	$line);
								fclose($fp);
//								while($cnt=0){
//												
//												
//								}
								for($i	=	0;	$i	<	$total_loop;	$i	++	)
								{
												$query	=	$query;
												$query.='LIMIT '	.	($i	*	15000)	.	', 15000';
												$records	=	$this->refine_modal->get_csv_records($query);
												
												$fp	=	fopen("uploads/google_refine/$filename",	'a');
												$line	=	'';
												foreach($records	as	$value)
												{
																$line.= $value->organization	.	',';
																$line.=	$value->asset_title	.	',';
																$line.=	$value->description	.	',';
																$line.= $value->instantiation_identifier	.	',';
																$line.= $value->instantiation_source	.	',';
																$line.= $value->generation	.	',';
																$line.= $value->status	.	',';
																$line.= $value->nomination_reason	.	',';
																$line.= $value->media_type	.	',';
																$line.= $value->language	.	',';
																$line.=	$value->ins_id	.	'';
																$line	.=	"\n";
												}
												fputs($fp,	$line);
												fclose($fp);
												$mem	=	memory_get_usage()	/	1024;
												$mem	=	$mem	/	1024;
												$mem	=	$mem	/	1024;
//												$this->myLog($mem	.	' GB');
								}
								$path	=	$this->config->item('path')	.	"uploads/google_refine/$filename";
								$data	=	array('export_csv_path'	=>	$path);
								$this->refine_modal->update_job($job_id,	$data);
				}

// Location: ./controllers/refine.php
}

// END Google Doc Controller

// End of file googledoc.php
// Location: ./application/controllers/refine.php
