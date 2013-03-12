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

				function	create($path,	$filename,	$job_id)
				{

								$project_name	=	$filename;
								$file_path	=	$path;
								$data	=	$this->googlerefine->create_project($project_name,	$file_path);
								if($data)
								{
												$data['is_active']	=	1;
												$data['project_name']	=	$filename;
												$this->refine_modal->update_job($job_id,	$data);
												return	$data['project_url'];
								}
								return	FALSE;
				}

				function	export($type)
				{
								if($type	==	'instantiation')
								{

												$params	=	array('search'	=>	'');
												$query	=	$this->refine_modal->export_refine_csv(TRUE);
												$record	=	array('user_id'						=>	$this->user_id,	'is_active'				=>	0,	'export_query'	=>	$query);
												$job_id	=	$this->refine_modal->insert_job($record);
												$filename	=	'google_refine_'	.	time()	.	'.csv';
												$fp	=	fopen("uploads/google_refine/$filename",	'a');
												$line	=	"Organization,Asset Title,Description,Instantiation ID,Instantiation ID Source,Generation,Nomination,Nomination Reason,Media Type,Language,__Ins_id\n";
												fputs($fp,	$line);
												fclose($fp);
												$db_count	=	0;
												$offset	=	0;

												while	($db_count	==	0)
												{

																$query.=' LIMIT '	.	($offset	*	15000)	.	', 15000';

																$records	=	$this->refine_modal->get_csv_records($query);

																$fp	=	fopen("uploads/google_refine/$filename",	'a');
																$line	=	'';
																foreach($records	as	$value)
																{
																				$line.='"'	.	str_replace('"',	'""',	$value->organization)	.	'",';
																				$line.='"'	.	str_replace('"',	'""',	$value->asset_title)	.	'",';
																				$line.='"'	.	str_replace('"',	'""',	$value->description)	.	'",';
																				$line.='"'	.	str_replace('"',	'""',	$value->instantiation_identifier)	.	'",';
																				$line.='"'	.	str_replace('"',	'""',	$value->instantiation_source)	.	'",';
																				$line.='"'	.	str_replace('"',	'""',	$value->generation)	.	'",';
																				$line.='"'	.	str_replace('"',	'""',	$value->status)	.	'",';
																				$line.='"'	.	str_replace('"',	'""',	$value->nomination_reason)	.	'",';
																				$line.='"'	.	str_replace('"',	'""',	$value->media_type)	.	'",';
																				$line.='"'	.	str_replace('"',	'""',	$value->language)	.	'",';
																				$line.='"'	.	str_replace('"',	'""',	$value->ins_id)	.	'"';
																				$line	.=	"\n";
																}
																fputs($fp,	$line);
																fclose($fp);
																$offset	++;
																if(count($records)	<	15000)
																				$db_count	++;
												}

												$path	=	$this->config->item('path')	.	"uploads/google_refine/$filename";
												$data	=	array('export_csv_path'	=>	$path);
												$this->refine_modal->update_job($job_id,	$data);
												$project_url	=	$this->create($path,	$filename,	$job_id);
												echo	json_encode(array('project_url'	=>	$project_url));
												exit;
								}
								else
								{
												$params	=	array('search'	=>	'');
												$query	=	$this->refine_modal->export_asset_refine_csv(TRUE);
												echo $query;exit;
												$record	=	array('user_id'						=>	$this->user_id,	'is_active'				=>	0,	'export_query'	=>	$query);
												$job_id	=	$this->refine_modal->insert_job($record);
												$filename	=	'google_refine_'	.	time()	.	'.csv';
												$fp	=	fopen("uploads/google_refine/$filename",	'a');
												$line	=	"Organization,Asset Title,Description,Subject,Subject Source,Subject Ref,Genre,Genre Source,Genre Ref,Creator Name,Creator Affiliation,Creator Source,Creator Ref,";
												$line	.="Contributors Name,Contributors Affiliation,Contributors Source,Contributors Ref,Publisher,Publisher Affiliation,Publisher Ref,Coverage,Coverage Type,";
												$line	.="Audience Level,Audience Level Source,Audience Level Ref,";
												$line	.="Audience Rating,Audience Rating Source,Audience Rating Ref,";
												$line	.="Annotation,Annotation Type,Annotation Ref,";
												$line	.="Rights,Rights Link,Asset Type,Identifier,Identifier Source,Identifier Ref,Asset Date,";
												$line	.="__asset_id\n";
												fputs($fp,	$line);
												fclose($fp);
												$db_count	=	0;
												$offset	=	0;
												while	($db_count	==	0)
												{

																$query.=' LIMIT '	.	($offset	*	15000)	.	', 15000';

																$records	=	$this->refine_modal->get_csv_records($query);

																$fp	=	fopen("uploads/google_refine/$filename",	'a');
																$line	=	'';
																foreach($records	as	$value)
																{
																				foreach($value as $column){
																									$line.='"'	.	str_replace('"',	'""',	$column)	.	'",';
																				}
																				$line	.=	"\n";
																}
																fputs($fp,	$line);
																fclose($fp);
																$offset	++;
																if(count($records)	<	15000)
																				$db_count	++;
												}

												$path	=	$this->config->item('path')	.	"uploads/google_refine/$filename";
												$data	=	array('export_csv_path'	=>	$path);
												$this->refine_modal->update_job($job_id,	$data);
												$project_url	=	$this->create($path,	$filename,	$job_id);
												echo	json_encode(array('project_url'	=>	$project_url));
												exit;
								}
				}

				function	remove($project_id)
				{

								$this->googlerefine->delete_project($project_id);
								$db_detail	=	$this->refine_modal->get_by_project_id($project_id);
								if($db_detail)
								{
												$data	=	array('is_active'	=>	0);
												$this->refine_modal->update_job($db_detail->id,	$data);
								}

								redirect('records');
				}

				function	save()
				{
								
				}

// Location: ./controllers/refine.php
}

// END Google Refine Controller

// End of file refine.php
// Location: ./application/controllers/refine.php
