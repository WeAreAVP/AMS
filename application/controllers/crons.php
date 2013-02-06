<?php

// @codingStandardsIgnoreFile
/**
	* Settings controller.
	*
	* @package    AMS
	* @subpackage Scheduled Tasks
	* @author     Ali Raza
	*/
class	Crons	extends	CI_Controller
{

				/**
					*
					* constructor. Load layout,Model,Library and helpers
					* 
					*/
				public	$assets_path;

				function	__construct()
				{
								parent::__construct();
								$this->load->model('email_template_model',	'email_template');
								$this->load->model('cron_model');
									$this->load->model('dx_auth/users',	'users');
								$this->load->model('assets_model');
								$this->load->model('instantiations_model',	'instant');
								$this->load->model('essence_track_model',	'essence');
								$this->load->model('station_model');
								$this->assets_path	=	'assets/export_pbcore/';
				}

				/**
					* Process all pending email 
					*  
					*/
				function	processemailqueues()
				{
								$email_queue	=	$this->email_template->get_all_pending_email();

								foreach($email_queue	as	$queue)
								{
												$now_queue_body	=	$queue->email_body	.	'<img src="'	.	site_url('emailtracking/'	.	$queue->id	.	'.png')	.	'" height="1" width="1" />';
												if(send_email($queue->email_to,	$queue->email_from,	$queue->email_subject,	$now_queue_body))
												{
																$this->email_template->update_email_queue_by_id($queue->id,	array("is_sent"	=>	2,	"sent_at"	=>	date('Y-m-d H:i:s')));
																echo	"Email Sent To "	.	$queue->email_to	.	" <br/>";
												}
								}
				}

				function	csv_export_job()
				{
								
								$this->load->model('export_csv_job_model',	'csv_job');
								$job	=	$this->csv_job->get_incomplete_jobs();

								if(count($job)	>	0)
								{
												$row	=	2;
												@ini_set("memory_limit",	"3000M");	# 1GB
												@ini_set("max_execution_time",	999999999999);	# 1GB
												$this->load->library('excel');
												PHPExcel_Settings::setCacheStorageMethod( PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip );
												$this->excel->getActiveSheetIndex();
												$this->excel->getActiveSheet()->setTitle('Limited CSV');
												$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
												$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
												$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(45);
												$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
												$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
												$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
												$this->excel->getActiveSheet()->getStyle("A1:F1")->getFont()->setBold(true);
												$this->excel->getActiveSheet()->setCellValueExplicitByColumnAndRow(0,	1,	'GUID');
												$this->excel->getActiveSheet()->setCellValueExplicitByColumnAndRow(1,	1,	'Unique ID');
												$this->excel->getActiveSheet()->setCellValueExplicitByColumnAndRow(2,	1,	'Title');
												$this->excel->getActiveSheet()->setCellValueExplicitByColumnAndRow(3,	1,	'Format');
												$this->excel->getActiveSheet()->setCellValueExplicitByColumnAndRow(4,	1,	'Duration');
												$this->excel->getActiveSheet()->setCellValueExplicitByColumnAndRow(5,	1,	'Priority');
												$this->excel->getActiveSheet()->freezePaneByColumnAndRow();
												for($i	=	0;	$i	<	$job->query_loop;	$i	++	)
												{
																$query	=	$job->export_query;
																$query.='LIMIT '	.	($i	*	15000)	.	', 15000';
																$records	=	$this->csv_job->get_csv_records($query);
																
																foreach($records	as	$value)
																{
																				$col	=	0;
																				foreach($value	as	$field)
																				{

																								$this->excel->getActiveSheet()->setCellValueExplicitByColumnAndRow($col,	$row,	$field);
																								
																								$col	++;
																				}
																				$row	++;
																}
																unset($records);
																 
																echo memory_get_usage() . "\n";
												}
												$filename	=	'csv_export_'	.	time()	.	'.csv';
												$objWriter	=	PHPExcel_IOFactory::createWriter($this->excel,	'Excel2007');
												$objWriter->save("uploads/$filename");
												$url	=	site_url()	.	"uploads/$filename";
												$this->csv_job->update_job($job->id,	array('status'	=>	'1'));
												$user=$this->users->get_user_by_id($job>user_id)->row();
												send_email($user->email,	'ssapienza@cpb.org',	'Limited CSV Export',	$url);
												exit;
								}
				}

				/**
					* Store All Assets Data Files Structure in database
					*  
					*/
				function	process_dir()
				{
								set_time_limit(0);
								$this->cron_model->scan_directory($this->assets_path,	$dir_files);
								$count	=	count($dir_files);

								if(isset($count)	&&	$count	>	0)
								{
												$this->myLog("Total Number of process "	.	$count);
												$loop_counter	=	0;
												$maxProcess	=	10;
												foreach($dir_files	as	$dir)
												{
																$cmd	=	escapeshellcmd('/usr/bin/php '	.	$this->config->item('path')	.	'index.php crons process_dir_child '	.	base64_encode($dir));
																$this->config->item('path')	.	"cronlog/process_dir_child.log";
																$pidFile	=	$this->config->item('path')	.	"PIDs/process_dir_child/"	.	$loop_counter	.	".txt";
																@exec('touch '	.	$pidFile);
																$this->runProcess($cmd,	$pidFile,	$this->config->item('path')	.	"cronlog/process_dir_child.log");
																$file_text	=	file_get_contents($pidFile);
																$this->arrPIDs[$file_text]	=	$loop_counter;
																$proc_cnt	=	$this->procCounter();
																$loop_counter	++;
																while	($proc_cnt	==	$maxProcess)
																{
																				$this->myLog('Number of Processes running : '	.	$loop_counter	.	'/.'	.	$count	.	' Sleeping ...');
																				sleep(30);
																				$proc_cnt	=	$this->procCounter();
																}
												}
												$this->myLog("Waiting for all process to complete");
												$proc_cnt	=	$this->procCounter();
												while	($proc_cnt	>	0)
												{
																echo	"Sleeping....\n";
																sleep(10);
																echo	"\010\010\010\010\010\010\010\010\010\010\010\010";
																echo	"\n";
																$proc_cnt	=	$this->procCounter();
																echo	"Number of Processes running : $proc_cnt/$maxProcess\n";
												}
								}
								echo	"All Data Path Under {$this->assets_path} Directory Stored ";
								exit(0);
				}

				/**
					* Store All Assets Data Files Structure in database
					*  
					*/
				function	process_dir_child($path)
				{

								$type	=	'assets';
								$file	=	'manifest-md5.txt';
								$directory	=	base64_decode($path);
								$folder_status	=	'complete';
								if(	!	$data_folder_id	=	$this->cron_model->get_data_folder_id_by_path($directory))
								{
												$data_folder_id	=	$this->cron_model->insert_data_folder(array("folder_path"	=>	$directory,	"created_at"		=>	date('Y-m-d H:i:s'),	"data_type"			=>	$type));
								}
								if(isset($data_folder_id)	&&	$data_folder_id	>	0)
								{
												$data_result	=	file($directory	.	$file);
												if(isset($data_result)	&&	!	is_empty($data_result))
												{
																foreach($data_result	as	$value)
																{
																				$data_file	=	(explode(" ",	$value));
																				$data_file_path	=	trim(str_replace(array('\r\n',	'\n',	'<br>'),	'',	trim($data_file[1])));
																				$this->myLog('Checking File '	.	$data_file_path);
																				if(isset($data_file_path)	&&	!	is_empty($data_file_path))
																				{
																								$file_path	=	trim($directory	.	$data_file_path);
																								if(strpos($data_file_path,	'organization.xml')	===	false)
																								{
																												if(file_exists($file_path))
																												{
																																if(	!	$this->cron_model->is_pbcore_file_by_path($data_file_path))
																																{
																																				$this->cron_model->insert_prcoess_data(array('file_type'						=>	$type,	'file_path'						=>	($data_file_path),	'is_processed'			=>	0,	'created_at'					=>	date('Y-m-d H:i:s'),	"data_folder_id"	=>	$data_folder_id));
																																}
																												}
																												else
																												{
																																if(	!	$this->cron_model->is_pbcore_file_by_path($data_file_path))
																																{
																																				$this->cron_model->insert_prcoess_data(array('file_type'						=>	$type,	'file_path'						=>	($data_file_path),	'is_processed'			=>	0,	'created_at'					=>	date('Y-m-d H:i:s'),	"data_folder_id"	=>	$data_folder_id,	'status_reason'		=>	'file_not_found'));
																																}
																																$folder_status	=	'incomplete';
																												}
																								}
																				}
																}
												}
												$this->myLog('folder Id '	.	$data_folder_id	.	' => folder_status '	.	$folder_status);
												$this->cron_model->update_data_folder(array('updated_at'				=>	date('Y-m-d H:i:s'),	'folder_status'	=>	$folder_status),	$data_folder_id);
								}
				}

				/**
					* 
					* Process all pending assets Data Files
					*
					*/
				function	process_xml_file()
				{
								$folders	=	$this->cron_model->get_all_data_folder();
								if(isset($folders)	&&	!	empty($folders))
								{
												foreach($folders	as	$folder)
												{
																$data1	=	file_get_contents($folder->folder_path	.	'data/organization.xml');
																$x	=	@simplexml_load_string($data1);
																unset($data1);
																$data	=	xmlObjToArr($x);
																$station_cpb_id	=	$data['children']['cpb-id'][0]['text'];
																if(isset($station_cpb_id))
																{
																				$count	=	$this->cron_model->get_pbcore_file_count_by_folder_id($folder->id);
																				if(isset($count)	&&	$count	>	0)
																				{
																								$maxProcess	=	50;
																								$limit	=	500;
																								$loop_end	=	ceil($count	/	$limit);
																								$this->myLog("Run $loop_end times  $maxProcess at a time");
																								for($loop_counter	=	0;	$loop_end	>	$loop_counter;	$loop_counter	++	)
																								{
																												$offset	=	$loop_counter	*	$limit;
																												$this->myLog("Started $offset~$limit of $count");
																												$cmd	=	escapeshellcmd('/usr/bin/php '	.	$this->config->item('path')	.	'index.php crons process_xml_file_child '	.	$folder->id	.	' '	.	$station_cpb_id	.	' '	.	$offset	.	' '	.	$limit);
																												$pidFile	=	$this->config->item('path')	.	"PIDs/processxmlfile/"	.	$loop_counter	.	".txt";
																												@exec('touch '	.	$pidFile);
																												$this->runProcess($cmd,	$pidFile,	$this->config->item('path')	.	"cronlog/processxmlfile.log");
																												$file_text	=	file_get_contents($pidFile);
																												$this->arrPIDs[$file_text]	=	$loop_counter;
																												$proc_cnt	=	$this->procCounter();
																												while	($proc_cnt	==	$maxProcess)
																												{
																																$this->myLog("Sleeping ...");
																																sleep(30);
																																$proc_cnt	=	$this->procCounter();
																																echo	"Number of Processes running : $proc_cnt/$maxProcess\n";
																												}
																								}
																								$this->myLog("Waiting for all process to complete");
																								$proc_cnt	=	$this->procCounter();
																								while	($proc_cnt	>	0)
																								{
																												echo	"Sleeping....\n";
																												sleep(10);
																												echo	"\010\010\010\010\010\010\010\010\010\010\010\010";
																												echo	"\n";
																												$proc_cnt	=	$this->procCounter();
																												echo	"Number of Processes running : $proc_cnt/$maxProcess\n";
																								}
																				}
																}
																unset($x);
																unset($data);
												}
								}
				}

				function	process_xml_file_child($folder_id,	$station_cpb_id,	$offset	=	0,	$limit	=	100)
				{
								error_reporting(E_ALL);
								ini_set('display_errors',	1);
								$station_data	=	$this->station_model->get_station_by_cpb_id($station_cpb_id);
								if(isset($station_data)	&&	!	empty($station_data)	&&	isset($station_data->id))
								{
												$folder_data	=	$this->cron_model->get_data_folder_by_id($folder_id);
												if($folder_data)
												{
																$data_files	=	$this->cron_model->get_pbcore_file_by_folder_id($folder_data->id,	$offset,	$limit);
																if(isset($data_files)	&&	!	is_empty($data_files))
																{
																				foreach($data_files	as	$d_file)
																				{
																								if($d_file->is_processed	==	0)
																								{
																												$this->cron_model->update_prcoess_data(array("processed_start_at"	=>	date('Y-m-d H:i:s')),	$d_file->id);
																												$file_path	=	'';
																												$file_path	=	trim($folder_data->folder_path	.	$d_file->file_path);
																												if(file_exists($file_path))
																												{
																																$this->myLog("Currently Parsing Files "	.	$file_path);
																																$asset_data	=	file_get_contents($file_path);
																																if(isset($asset_data)	&&	!	empty($asset_data))
																																{
																																				$asset_xml_data	=	@simplexml_load_string($asset_data);
																																				$asset_d	=	xmlObjToArr($asset_xml_data);
																																				echo	"Current Version "	.	$asset_d['attributes']['version']	.	" \n ";
																																				if(	!	isset($asset_d['attributes']['version'])	||	empty($asset_d['attributes']['version'])	||	$asset_d['attributes']['version']	==	'1.3')
																																				{
																																								//$this->db->trans_start	();
																																								$asset_id	=	$this->assets_model->insert_assets(array("stations_id"			=>	$station_data->id,	"created"							=>	date("Y-m-d H:i:s")));
																																								echo	"\n in Process \n";
																																								$asset_children	=	$asset_d['children'];
																																								if(isset($asset_children))
																																								{
																																												//echo "<pre>";
																																												//print_r($asset_children);
																																												// Assets Start
																																												$this->myLog(" Assets Start ");
																																												$this->process_assets($asset_children,	$asset_id);
																																												$this->myLog(" Assets Ends ");
																																												// Assets End
																																												// Instantiation Start
																																												$this->myLog(" Instantiation Start ");
																																												$this->process_instantiation($asset_children,	$asset_id);
																																												// Instantiation End
																																												$this->myLog(" Instantiation End ");
																																												$this->cron_model->update_prcoess_data(array('is_processed'		=>	1,	"processed_at"		=>	date('Y-m-d H:i:s'),	'status_reason'	=>	'Complete'),	$d_file->id);
																																								}
																																								else
																																								{
																																												$this->myLog(" Attribut children not found "	.	$file_path);
																																												$this->cron_model->update_prcoess_data(array('status_reason'	=>	'attribut_children_not_found'),	$d_file->id);
																																								}

																																								//$this->db->trans_complete	();
																																								unset($asset_d);
																																								unset($asset_xml_data);
																																								unset($asset_data);
																																				}
																																				else
																																				{
																																								$this->myLog(" Attribut version Issues "	.	$file_path);
																																								$this->cron_model->update_prcoess_data(array('status_reason'	=>	'version_issues'),	$d_file->id);
																																				}
																																}
																																else
																																{
																																				$this->myLog(" Data is empty in file "	.	$file_path);
																																				$this->cron_model->update_prcoess_data(array('status_reason'	=>	'data_empty'),	$d_file->id);
																																}
																												}
																												else
																												{
																																$this->myLog(" Is File Check Issues "	.	$file_path);
																																$this->cron_model->update_prcoess_data(array('status_reason'	=>	'file_not_found'),	$d_file->id);
																												}
																								}
																								else
																								{
																												$this->myLog(" Already Processed "	.	$file_path);
																												$this->cron_model->update_prcoess_data(array('status_reason'	=>	'already_processed'),	$d_file->id);
																								}
																				}
																				unset($data_files);
																}
																else
																{
																				$this->myLog(" Data files not found "	.	$file_path);
																}
												}
												else
												{
																$this->myLog(" folders Data not found "	.	$file_path);
												}
								}
								else
								{
												$this->myLog(" Station data not Found against "	.	$station_cpb_id);
								}
				}

				/*
					* Process Instantiation Elements
					*/

				function	process_instantiation($asset_children,	$asset_id)
				{
								error_reporting(E_ALL);
								ini_set('display_errors',	1);
								// pbcoreAssetType Start here
								if(isset($asset_children['pbcoreinstantiation']))
								{
												foreach($asset_children['pbcoreinstantiation']	as	$pbcoreinstantiation)
												{
																if(isset($pbcoreinstantiation['children'])	&&	!	is_empty($pbcoreinstantiation['children']))
																{

																				$pbcoreinstantiation_child	=	$pbcoreinstantiation['children'];
																				//pbcoreInstantiation Start
																				$instantiations_d	=	array();
																				$instantiations_d['assets_id']	=	$asset_id;
																				//Instantiation formatLocation
																				if(isset($pbcoreinstantiation_child['formatlocation'])	&&	!	is_empty($pbcoreinstantiation_child['formatlocation']))
																				{
																								if(	!	is_empty($pbcoreinstantiation_child['formatlocation'][0]['text']))
																								{
																												$instantiations_d['location']	=	$pbcoreinstantiation_child['formatlocation'][0]['text'];
																								}
																				}

																				//Instantiation formatMediaType
																				if(isset($pbcoreinstantiation_child['formatmediatype'][0]['text'])	&&	!	is_empty($pbcoreinstantiation_child['formatmediatype'][0]['text']))
																				{
																								$inst_media_type	=	$this->instant->get_instantiation_media_types_by_media_type($pbcoreinstantiation_child['formatmediatype'][0]['text']);
																								if(	!	is_empty($inst_media_type))
																								{
																												$instantiations_d['instantiation_media_type_id']	=	$inst_media_type->id;
																								}
																								else
																								{
																												$instantiations_d['instantiation_media_type_id']	=	$this->instant->insert_instantiation_media_types(array("media_type"	=>	$pbcoreinstantiation_child['formatmediatype'][0]['text']));
																								}
																				}

																				//Instantiation formatFileSize Start
																				if(isset($pbcoreinstantiation_child['formatfilesize'][0]['text'])	&&	!	is_empty($pbcoreinstantiation_child['formatfilesize'][0]['text']))
																				{
																								$files_size_perm	=	explode(" ",	$pbcoreinstantiation_child['formatfilesize'][0]['text']);
																								if(isset($files_size_perm[0])	&&	!	is_empty($files_size_perm[0]))
																								{
																												$instantiations_d['file_size']	=	$files_size_perm[0];
																								}
																								if(isset($files_size_perm[1])	&&	!	is_empty($files_size_perm[1]))
																								{
																												$instantiations_d['file_size_unit_of_measure']	=	$files_size_perm[1];
																								}
																				}

																				//Instantiation formatTimeStart Start
																				if(isset($pbcoreinstantiation_child['formattimestart'][0]['text'])	&&	!	is_empty($pbcoreinstantiation_child['formattimestart'][0]['text']))
																				{
																								$instantiations_d['time_start']	=	trim($pbcoreinstantiation_child['formattimestart'][0]['text']);
																				}

																				//Instantiation formatDuration Start
																				if(isset($pbcoreinstantiation_child['formatduration'][0]['text'])	&&	!	is_empty($pbcoreinstantiation_child['formatduration'][0]['text']))
																				{
																								$instantiations_d['projected_duration']	=	trim($pbcoreinstantiation_child['formatduration'][0]['text']);
																				}

																				//Instantiation formatDataRate Start
																				if(isset($pbcoreinstantiation_child['formatdatarate'][0]['text'])	&&	!	is_empty($pbcoreinstantiation_child['formatdatarate'][0]['text']))
																				{
																								$format_data_rate_perm	=	explode(" ",	$pbcoreinstantiation_child['formatdatarate'][0]['text']);
																								if(isset($format_data_rate_perm[0])	&&	!	is_empty($format_data_rate_perm[0]))
																								{
																												$instantiations_d['data_rate']	=	$format_data_rate_perm[0];
																								}
																								if(isset($format_data_rate_perm[1])	&&	!	is_empty($format_data_rate_perm[1]))
																								{
																												$data_rate_unit_d	=	$this->instant->get_data_rate_units_by_unit($format_data_rate_perm[1]);
																												if(isset($data_rate_unit_d)	&&	isset($data_rate_unit_d->id))
																												{
																																$instantiations_d['data_rate_units_id']	=	$data_rate_unit_d->id;
																												}
																												else
																												{
																																$instantiations_d['data_rate_units_id']	=	$this->instant->insert_data_rate_units(array("unit_of_measure"	=>	$format_data_rate_perm[1]));
																												}
																								}
																				}

																				//Instantiation formatcolors Start
																				if(isset($pbcoreinstantiation_child['formatcolors'][0]['text'])	&&	!	is_empty($pbcoreinstantiation_child['formatcolors'][0]['text']))
																				{
																								$inst_color_d	=	$this->instant->get_instantiation_colors_by_color($pbcoreinstantiation_child['formatcolors'][0]['text']);
																								if(isset($inst_color_d)	&&	!	is_empty($inst_color_d))
																								{
																												$instantiations_d['instantiation_colors_id']	=	$inst_color_d->id;
																								}
																								else
																								{
																												$instantiations_d['instantiation_colors_id']	=	$this->instant->insert_instantiation_colors(array('color'	=>	$pbcoreinstantiation_child['formatcolors'][0]['text']));
																								}
																				}

																				//Instantiation formattracks Start
																				if(isset($pbcoreinstantiation_child['formattracks'][0]['text'])	&&	!	is_empty($pbcoreinstantiation_child['formattracks'][0]['text']))
																				{
																								$instantiations_d['tracks']	=	$pbcoreinstantiation_child['formattracks'][0]['text'];
																				}

																				//Instantiation formatchannelconfiguration Start
																				if(isset($pbcoreinstantiation_child['formatchannelconfiguration'][0]['text'])	&&	!	is_empty($pbcoreinstantiation_child['formatchannelconfiguration'][0]['text']))
																				{
																								$instantiations_d['channel_configuration']	=	$pbcoreinstantiation_child['formatchannelconfiguration'][0]['text'];
																				}

																				//Instantiation language Start
																				if(isset($pbcoreinstantiation_child['language'][0]['text'])	&&	!	is_empty($pbcoreinstantiation_child['language'][0]['text']))
																				{
																								$instantiations_d['language']	=	$pbcoreinstantiation_child['language'][0]['text'];
																				}

																				//Instantiation alternativemodes Start
																				if(isset($pbcoreinstantiation_child['alternativemodes'][0]['text'])	&&	!	is_empty($pbcoreinstantiation_child['alternativemodes'][0]['text']))
																				{
																								$instantiations_d['alternative_modes']	=	$pbcoreinstantiation_child['alternativemodes'][0]['text'];
																				}
																				$instantiations_d['created']	=	date("Y-m-d H:i:s");
																				$instantiations_id	=	$this->instant->insert_instantiations($instantiations_d);

																				//pbcoreInstantiation End
																				//pbcoreExtension Start
																				$data_created_check	=	true;
																				if(isset($asset_children['pbcoreextension'])	&&	!	is_empty($asset_children['pbcoreextension']))
																				{
																								foreach($asset_children['pbcoreextension']	as	$pbcore_extension)
																								{
																												if(isset($pbcore_extension['children']['extensionauthorityused'][0])	&&	!	is_empty($pbcore_extension['children']['extensionauthorityused'][0]['text']))
																												{
																																$extension_authority_used	=	strtolower($pbcore_extension['children']['extensionauthorityused'][0]['text']);
																																if($extension_authority_used	===	strtolower('AACIP Record Nomination Status'))
																																{
																																				$nomination_d	=	array();
																																				$nomination_d['instantiations_id']	=	$instantiations_id;
																																				if(isset($pbcore_extension['children']['extension'][0]['text'])	&&	!	is_empty($pbcore_extension['children']['extension'][0]['text']))
																																				{
																																								if(in_array(trim($pbcore_extension['children']['extension'][0]['text']),	array('Nominated/1st Priority',	'Nominated/2nd Priority',	'Waiting List')))
																																								{
																																												$nomunation_status	=	$this->assets_model->get_nomination_status_by_status($pbcore_extension['children']['extension'][0]['text']);
																																												if(isset($nomunation_status)	&&	!	is_empty($nomunation_status))
																																												{
																																																$nomination_d['nomination_status_id']	=	$nomunation_status->id;
																																												}
																																												else
																																												{
																																																$nomination_d['nomination_status_id']	=	$this->assets_model->insert_nomination_status(array("status"																	=>	$pbcore_extension['children']['extension'][0]['text']));
																																												}
																																												$nomination_d['created']	=	date("Y-m-d H:i:s");
																																												$this->assets_model->insert_nominations($nomination_d);
																																								}
																																				}
																																}
																																else	if($extension_authority_used	===	'unknown'	||	$extension_authority_used	===	strtolower('Date type: Unknown'))
																																{
																																				if(isset($pbcore_extension['children']['extension'][0]['text'])	&&	!	is_empty($pbcore_extension['children']['extension'][0]['text']))
																																				{
																																								$instantiation_dates_d	=	array();
																																								$instantiation_dates_d['instantiations_id']	=	$instantiations_id;
																																								$instantiation_dates_d['instantiation_date']	=	str_replace(array('?',	'Unknown',	'unknown',	'`',	'['	.	']',	'N/A',	'N/A?',	'Jim Cooper',	'various',	'.00',	'.0',	'John Kelling',	'Roll in',	'interview'),	'',	trim($pbcore_extension['children']['extension'][0]['text']));
																																								if(isset($instantiation_dates_d['instantiation_date'])	&&	!	is_empty($instantiation_dates_d['instantiation_date']))
																																								{
																																												$date_check	=	$this->is_valid_date($instantiation_dates_d['instantiation_date']);
																																												if($date_check	===	FALSE)
																																												{
																																																$instantiation_annotation_d	=	array();
																																																$instantiation_annotation_d['instantiations_id']	=	$instantiations_id;
																																																$instantiation_annotation_d['annotation']	=	$instantiation_dates_d['instantiation_date'];
																																																$instantiation_annotation_d['annotation_type']	=	'date unidentified';
																																																$instantiation_annotation_ids[]	=	$this->instant->insert_instantiation_annotations($instantiation_annotation_d);
																																												}
																																												else
																																												{
																																																$date_type	=	$this->instant->get_date_types_by_type('unidentified');
																																																if(isset($date_type)	&&	isset($date_type->id))
																																																{
																																																				$instantiation_dates_d['date_types_id']	=	$date_type->id;
																																																}
																																																else
																																																{
																																																				$instantiation_dates_d['date_types_id']	=	$this->instant->insert_date_types(array('date_type'																																		=>	'unidentified'));
																																																}
																																																$instantiation_dates_d['instantiation_date']	=	$date_check;

																																																$instantiation_date_created_id	=	$this->instant->insert_instantiation_dates($instantiation_dates_d);
																																												}
																																								}
																																				}
																																}
																																else	if($extension_authority_used	===	'content')
																																{
																																				if(isset($pbcore_extension['children']['extension'][0]['text'])	&&	!	is_empty($pbcore_extension['children']['extension'][0]['text']))
																																				{
																																								$instantiation_dates_d	=	array();
																																								$instantiation_dates_d['instantiations_id']	=	$instantiations_id;
																																								$instantiation_dates_d['instantiation_date']	=	str_replace(array('?',	'Unknown',	'unknown',	'`',	'['	.	']',	'N/A',	'N/A?',	'Jim Cooper',	'various',	'.00',	'.0',	'John Kelling',	'Roll in',	'interview'),	'',	trim($pbcore_extension['children']['extension'][0]['text']));
																																								if(isset($instantiation_dates_d['instantiation_date'])	&&	!	is_empty($instantiation_dates_d['instantiation_date']))
																																								{
																																												$date_check	=	$this->is_valid_date($instantiation_dates_d['instantiation_date']);
																																												if($date_check	===	FALSE)
																																												{
																																																$instantiation_annotation_d	=	array();
																																																$instantiation_annotation_d['instantiations_id']	=	$instantiations_id;
																																																$instantiation_annotation_d['annotation']	=	$instantiation_dates_d['instantiation_date'];
																																																$instantiation_annotation_d['annotation_type']	=	'date created';
																																																$instantiation_annotation_ids[]	=	$this->instant->insert_instantiation_annotations($instantiation_annotation_d);
																																												}
																																												else
																																												{
																																																$date_type	=	$this->instant->get_date_types_by_type('created');
																																																if(isset($date_type)	&&	isset($date_type->id))
																																																{
																																																				$instantiation_dates_d['date_types_id']	=	$date_type->id;
																																																}
																																																else
																																																{
																																																				$instantiation_dates_d['date_types_id']	=	$this->instant->insert_date_types(array('date_type'																																		=>	'created'));
																																																}
																																																$data_created_check	=	false;
																																																$instantiation_dates_d['instantiation_date']	=	$date_check;
																																																$instantiation_date_created_id	=	$this->instant->insert_instantiation_dates($instantiation_dates_d);
																																												}
																																								}
																																				}
																																}
																																else	if($extension_authority_used	===	'created')
																																{
																																				if(isset($pbcore_extension['children']['extension'][0]['text'])	&&	!	is_empty($pbcore_extension['children']['extension'][0]['text']))
																																				{
																																								$instantiation_dates_d	=	array();
																																								$instantiation_dates_d['instantiations_id']	=	$instantiations_id;
																																								$instantiation_dates_d['instantiation_date']	=	str_replace(array('?',	'Unknown',	'unknown',	'`',	'['	.	']',	'N/A',	'N/A?',	'Jim Cooper',	'various',	'.00',	'.0',	'John Kelling',	'Roll in',	'interview'),	'',	trim($pbcore_extension['children']['extension'][0]['text']));
																																								if(isset($instantiation_dates_d['instantiation_date'])	&&	!	is_empty($instantiation_dates_d['instantiation_date']))
																																								{
																																												$date_check	=	$this->is_valid_date($instantiation_dates_d['instantiation_date']);
																																												if($date_check	===	FALSE)
																																												{
																																																$instantiation_annotation_d	=	array();
																																																$instantiation_annotation_d['instantiations_id']	=	$instantiations_id;
																																																$instantiation_annotation_d['annotation']	=	$instantiation_dates_d['instantiation_date'];
																																																$instantiation_annotation_d['annotation_type']	=	'date recorded';
																																																$instantiation_annotation_ids[]	=	$this->instant->insert_instantiation_annotations($instantiation_annotation_d);
																																												}
																																												else
																																												{
																																																$date_type	=	$this->instant->get_date_types_by_type('recorded');
																																																if(isset($date_type)	&&	isset($date_type->id))
																																																{
																																																				$instantiation_dates_d['date_types_id']	=	$date_type->id;
																																																}
																																																else
																																																{
																																																				$instantiation_dates_d['date_types_id']	=	$this->instant->insert_date_types(array('date_type'																																		=>	'recorded'));
																																																}
																																																$data_created_check	=	false;
																																																$instantiation_dates_d['instantiation_date']	=	$date_check;
																																																$instantiation_date_created_id	=	$this->instant->insert_instantiation_dates($instantiation_dates_d);
																																												}
																																								}
																																				}
																																}
																												}
																								}
																				}
																				if(isset($pbcoreinstantiation_child['pbcoreformatid']))
																				{

																								foreach($pbcoreinstantiation_child['pbcoreformatid']	as	$pbcoreformatid)
																								{
																												$instantiation_identifier_d	=	array();
																												$instantiation_identifier_d['instantiations_id']	=	$instantiations_id;
																												if(isset($pbcoreformatid['children'])	&&	!	is_empty($pbcoreformatid['children']))
																												{
																																if(isset($pbcoreformatid['children']['formatidentifier'][0]['text'])	&&	!	is_empty($pbcoreformatid['children']['formatidentifier'][0]['text']))
																																{
																																				$instantiation_identifier_d['instantiation_identifier']	=	$pbcoreformatid['children']['formatidentifier'][0]['text'];
																																}
																																if(isset($pbcoreformatid['children']['formatidentifiersource'][0]['text'])	&&	!	is_empty($pbcoreformatid['children']['formatidentifiersource'][0]['text']))
																																{
																																				$instantiation_identifier_d['instantiation_source']	=	$pbcoreformatid['children']['formatidentifiersource'][0]['text'];
																																}
																																//print_r($instantiation_identifier_d);
																																if((isset($instantiation_identifier_d['instantiation_identifier'])	&&	!	is_empty($instantiation_identifier_d['instantiation_identifier'])	)	||
																																								(isset($instantiation_identifier_d['instantiation_source'])	&&	!	is_empty($instantiation_identifier_d['instantiation_source'])))
																																{
																																				$instantiation_identifier_id	=	$this->instant->insert_instantiation_identifier($instantiation_identifier_d);
																																}
																												}
																								}
																				}
																				//Instantiation Date Created Start
																				if(isset($pbcoreinstantiation_child['datecreated'])	&&	!	is_empty($pbcoreinstantiation_child['datecreated'])	&&	$data_created_check)
																				{
																								$instantiation_dates_d	=	array();
																								$instantiation_dates_d['instantiations_id']	=	$instantiations_id;

																								if(isset($pbcoreinstantiation_child['datecreated'][0]['text'])	&&	!	is_empty($pbcoreinstantiation_child['datecreated'][0]['text']))
																								{
																												$instantiation_dates_d['instantiation_date']	=	str_replace(array('?',	'Unknown',	'unknown',	'`',	'['	.	']',	'N/A',	'N/A?',	'Jim Cooper',	'various',	'.00',	'.0',	'John Kelling',	'Roll in',	'interview'),	'',	trim($pbcoreinstantiation_child['datecreated'][0]['text']));
																												if(isset($instantiation_dates_d['instantiation_date'])	&&	!	is_empty($instantiation_dates_d['instantiation_date']))
																												{
																																$date_check	=	$this->is_valid_date($instantiation_dates_d['instantiation_date']);
																																if($date_check	===	FALSE)
																																{
																																				$instantiation_annotation_d	=	array();
																																				$instantiation_annotation_d['instantiations_id']	=	$instantiations_id;
																																				$instantiation_annotation_d['annotation']	=	$instantiation_dates_d['instantiation_date'];
																																				$instantiation_annotation_d['annotation_type']	=	'date created';
																																				$instantiation_annotation_ids[]	=	$this->instant->insert_instantiation_annotations($instantiation_annotation_d);
																																}
																																else
																																{
//																																				if(strpos($pbcoreinstantiation_child['datecreated'][0]['text'],	'?'))
//																																				{
//																																								$date_type	=	$this->instant->get_date_types_by_type('approximate');
//																																								if(isset($date_type)	&&	isset($date_type->id))
//																																								{
//																																												$instantiation_dates_d['date_types_id']	=	$date_type->id;
//																																								}
//																																								else
//																																								{
//																																												$instantiation_dates_d['date_types_id']	=	$this->instant->insert_date_types(array('date_type'	=>	'approximate'));
//																																								}
//																																				}
//																																				else	
																																				$date_type	=	$this->instant->get_date_types_by_type('created');
																																				if(isset($date_type)	&&	isset($date_type->id))
																																				{
																																								$instantiation_dates_d['date_types_id']	=	$date_type->id;
																																				}
																																				else
																																				{
																																								$instantiation_dates_d['date_types_id']	=	$this->instant->insert_date_types(array('date_type'																																		=>	'created'));
																																				}
																																				$instantiation_dates_d['instantiation_date']	=	$date_check;
																																				$instantiation_date_created_id	=	$this->instant->insert_instantiation_dates($instantiation_dates_d);
																																}
																												}
																								}
																				}
																				//Instantiation Date Created End
																				//Instantiation Date Issued Start
																				if(isset($pbcoreinstantiation_child['dateissued'])	&&	!	is_empty($pbcoreinstantiation_child['dateissued']))
																				{
																								$instantiation_dates_d	=	array();
																								$instantiation_dates_d['instantiations_id']	=	$instantiations_id;

																								if(isset($pbcoreinstantiation_child['dateissued'][0]['text'])	&&	!	is_empty($pbcoreinstantiation_child['dateissued'][0]['text']))
																								{
																												$instantiation_dates_d['instantiation_date']	=	str_replace(array('?',	'Unknown',	'unknown',	'`',	'['	.	']',	'N/A',	'N/A?',	'Jim Cooper',	'various',	'.00',	'.0',	'John Kelling',	'Roll in',	'interview'),	'',	$pbcoreinstantiation_child['dateissued'][0]['text']);
																												if(isset($instantiation_dates_d['instantiation_date'])	&&	!	is_empty($instantiation_dates_d['instantiation_date']))
																												{
																																$date_check	=	$this->is_valid_date($instantiation_dates_d['instantiation_date']);

																																if($date_check	===	FALSE)
																																{
																																				$instantiation_annotation_d	=	array();
																																				$instantiation_annotation_d['instantiations_id']	=	$instantiations_id;
																																				$instantiation_annotation_d['annotation']	=	$instantiation_dates_d['instantiation_date'];
																																				$instantiation_annotation_d['annotation_type']	=	'date issued';
																																				$instantiation_annotation_ids[]	=	$this->instant->insert_instantiation_annotations($instantiation_annotation_d);
																																}
																																else
																																{

																																				$date_type	=	$this->instant->get_date_types_by_type('issued');
																																				if(isset($date_type)	&&	isset($date_type->id))
																																				{
																																								$instantiation_dates_d['date_types_id']	=	$date_type->id;
																																				}
																																				else
																																				{
																																								$instantiation_dates_d['date_types_id']	=	$this->instant->insert_date_types(array('date_type'																																		=>	'issued'));
																																				}
																																				$instantiation_dates_d['instantiation_date']	=	$date_check;
																																				$instantiation_date_issued_id	=	$this->instant->insert_instantiation_dates($instantiation_dates_d);
																																}
																												}
																								}
																				}
																				//Instantiation Date Issued End
																				//Instantiation formatPhysical  Start
																				if(isset($pbcoreinstantiation_child['formatphysical'][0]['text'])	&&	!	is_empty($pbcoreinstantiation_child['formatphysical'][0]['text']))
																				{
																								$instantiation_format_physical_d	=	array();
																								$instantiation_format_physical_d['instantiations_id']	=	$instantiations_id;
																								$instantiation_format_physical_d['format_name']	=	$pbcoreinstantiation_child['formatphysical'][0]['text'];
																								$instantiation_format_physical_d['format_type']	=	'physical';
																								$instantiation_format_physical_id	=	$this->instant->insert_instantiation_formats($instantiation_format_physical_d);
																				}

																				//Instantiation formatdigital  Start
																				if(isset($pbcoreinstantiation_child['formatdigital'][0]['text'])	&&	!	is_empty($pbcoreinstantiation_child['formatdigital'][0]['text']))
																				{
																								$instantiation_format_digital_d	=	array();
																								$instantiation_format_digital_d['instantiations_id']	=	$instantiations_id;
																								$instantiation_format_digital_d['format_name']	=	$pbcoreinstantiation_child['formatdigital'][0]['text'];
																								$instantiation_format_digital_d['format_type']	=	'digital';
																								$instantiation_format_digital_id	=	$this->instant->insert_instantiation_formats($instantiation_format_digital_d);
																				}

																				//Instantiation formatgenerations  Start
																				if(isset($pbcoreinstantiation_child['formatgenerations'])	&&	!	is_empty($pbcoreinstantiation_child['formatgenerations']))
																				{
																								foreach($pbcoreinstantiation_child['formatgenerations']	as	$format_generations)
																								{
																												if(isset($format_generations['text'])	&&	!	is_empty($format_generations['text']))
																												{
																																$instantiation_format_generations_d	=	array();
																																$instantiation_format_generations_d['instantiations_id']	=	$instantiations_id;
																																$generations_d	=	$this->instant->get_generations_by_generation($format_generations['text']);
																																if(isset($generations_d)	&&	isset($generations_d->id))
																																{
																																				$instantiation_format_generations_d['generations_id']	=	$generations_d->id;
																																}
																																else
																																{
																																				$instantiation_format_generations_d['generations_id']	=	$this->instant->insert_generations(array("generation"																												=>	$format_generations['text']));
																																}
																																$instantiation_format_generations_ids[]	=	$this->instant->insert_instantiation_generations($instantiation_format_generations_d);
																												}
																								}
																				}
																				//Instantiation pbcoreAnnotation  Start
																				if(isset($pbcoreinstantiation_child['pbcoreannotation']))
																				{
																								foreach($pbcoreinstantiation_child['pbcoreannotation']	as	$pbcore_annotation)
																								{
																												if(isset($pbcore_annotation['children']['annotation'][0]['text'])	&&	!	is_empty($pbcore_annotation['children']['annotation'][0]['text']))
																												{
																																$instantiation_annotation_d	=	array();
																																$instantiation_annotation_d['instantiations_id']	=	$instantiations_id;
																																$instantiation_annotation_d['annotation']	=	$pbcore_annotation['children']['annotation'][0]['text'];
																																$instantiation_annotation_ids[]	=	$this->instant->insert_instantiation_annotations($instantiation_annotation_d);
																												}
																								}
																				}
																				//Instantiation pbcoreAnnotation  Start
																				if(isset($pbcoreinstantiation_child['pbcoreessencetrack']))
																				{
																								foreach($pbcoreinstantiation_child['pbcoreessencetrack']	as	$pbcore_essence_track)
																								{
																												if(isset($pbcore_essence_track['children'])	&&	!	is_empty($pbcore_essence_track['children']))
																												{
																																$pbcore_essence_child	=	$pbcore_essence_track['children'];
																																$essence_tracks_d	=	array();
																																$essence_tracks_d['instantiations_id']	=	$instantiations_id;
																																//essenceTrackType start
																																// Required Fields 1.essencetracktype If this not set then no record enter for essence_track
																																if(isset($pbcore_essence_child['essencetracktype'][0]['text'])	&&	!	is_empty($pbcore_essence_child['essencetracktype'][0]['text']))
																																{
																																				$essence_track_type_d	=	$this->essence->get_essence_track_by_type($pbcore_essence_child['essencetracktype'][0]['text']);
																																				if(isset($essence_track_type_d)	&&	isset($essence_track_type_d->id))
																																				{
																																								$essence_tracks_d['essence_track_types_id']	=	$essence_track_type_d->id;
																																				}
																																				else
																																				{
																																								$essence_tracks_d['essence_track_types_id']	=	$this->essence->insert_essence_track_types(array('essence_track_type'	=>	$pbcore_essence_child['essencetracktype'][0]['text']));
																																				}
																																				//essenceTrackStandard Start
																																				if(isset($pbcore_essence_child['essencetrackstandard'][0]['text'])	&&	!	is_empty($pbcore_essence_child['essencetrackstandard'][0]['text']))
																																				{
																																								$essence_tracks_d['standard']	=	$pbcore_essence_child['essencetrackstandard'][0]['text'];
																																				}
																																				//essenceRrackDatarate Start
																																				if(isset($pbcore_essence_child['essencetrackdatarate'][0]['text'])	&&	!	is_empty($pbcore_essence_child['essencetrackdatarate'][0]['text']))
																																				{
																																								$format_data_rate_perm	=	'';
																																								$format_data_rate_perm	=	explode(" ",	$pbcore_essence_child['essencetrackdatarate'][0]['text']);
																																								if(isset($format_data_rate_perm[0])	&&	!	is_empty($format_data_rate_perm[0]))
																																								{
																																												$essence_tracks_d['data_rate']	=	$format_data_rate_perm[0];
																																								}
																																								if(isset($format_data_rate_perm[1])	&&	!	is_empty($format_data_rate_perm[1]))
																																								{
																																												$data_rate_unit_d	=	$this->instant->get_data_rate_units_by_unit($format_data_rate_perm[1]);
																																												if(isset($data_rate_unit_d)	&&	isset($data_rate_unit_d->id))
																																												{
																																																$essence_tracks_d['data_rate_units_id']	=	$data_rate_unit_d->id;
																																												}
																																												else
																																												{
																																																$essence_tracks_d['data_rate_units_id']	=	$this->instant->insert_data_rate_units(array("unit_of_measure"	=>	$format_data_rate_perm[1]));
																																												}
																																								}
																																				}

																																				//essencetrackframerate Start
																																				if(isset($pbcore_essence_child['essencetrackframerate'][0]['text'])	&&	!	is_empty($pbcore_essence_child['essencetrackframerate'][0]['text']))
																																				{
																																								$frame_rate	=	explode(" ",	$pbcore_essence_child['essencetrackframerate'][0]['text']);
																																								$essence_tracks_d['frame_rate']	=	trim($frame_rate[0]);
																																				}

																																				//essencetrackframerate Start
																																				if(isset($pbcore_essence_child['essencetracksamplingrate'][0]['text'])	&&	!	is_empty($pbcore_essence_child['essencetracksamplingrate'][0]['text']))
																																				{
																																								$essence_tracks_d['sampling_rate']	=	$pbcore_essence_child['essencetracksamplingrate'][0]['text'];
																																				}

																																				//essenceTrackBitDepth Start
																																				if(isset($pbcore_essence_child['essencetrackbitdepth'][0]['text'])	&&	!	is_empty($pbcore_essence_child['essencetrackbitdepth'][0]['text']))
																																				{
																																								$essence_tracks_d['bit_depth']	=	$pbcore_essence_child['essencetrackbitdepth'][0]['text'];
																																				}

																																				//essenceTrackBitDepth Start
																																				if(isset($pbcore_essence_child['essencetrackframesize'][0]['text'])	&&	!	is_empty($pbcore_essence_child['essencetrackframesize'][0]['text']))
																																				{
																																								$frame_sizes	=	explode("x",	strtolower($pbcore_essence_child['essencetrackframesize'][0]['text']));
																																								if(isset($frame_sizes[0])	&&	isset($frame_sizes[1]))
																																								{
																																												$track_frame_size_d	=	$this->essence->get_essence_track_frame_sizes_by_width_height(trim($frame_sizes[0]),	trim($frame_sizes[1]));
																																												if($track_frame_size_d)
																																												{
																																																$essence_tracks_d['essence_track_frame_sizes_id']	=	$track_frame_size_d->id;
																																												}
																																												else
																																												{
																																																$essence_tracks_d['essence_track_frame_sizes_id']	=	$this->essence->insert_essence_track_frame_sizes(array("width"		=>	$frame_sizes[0],	"height"	=>	$frame_sizes[1]));
																																												}
																																								}
																																				}

																																				//essencetrackaspectratio Start
																																				if(isset($pbcore_essence_child['essencetrackaspectratio'][0]['text'])	&&	!	is_empty($pbcore_essence_child['essencetrackaspectratio'][0]['text']))
																																				{
																																								$essence_tracks_d['aspect_ratio']	=	$pbcore_essence_child['essencetrackaspectratio'][0]['text'];
																																				}

																																				//essencetracktimestart Start
																																				if(isset($pbcore_essence_child['essencetracktimestart'][0]['text'])	&&	!	is_empty($pbcore_essence_child['essencetracktimestart'][0]['text']))
																																				{
																																								$essence_tracks_d['time_start']	=	$pbcore_essence_child['essencetracktimestart'][0]['text'];
																																				}

																																				//essencetrackduration Start
																																				if(isset($pbcore_essence_child['essencetrackduration'][0]['text'])	&&	!	is_empty($pbcore_essence_child['essencetrackduration'][0]['text']))
																																				{
																																								$essence_tracks_d['duration']	=	$pbcore_essence_child['essencetrackduration'][0]['text'];
																																				}

																																				//essencetracklanguage Start
																																				if(isset($pbcore_essence_child['essencetracklanguage'][0]['text'])	&&	!	is_empty($pbcore_essence_child['essencetracklanguage'][0]['text']))
																																				{
																																								$essence_tracks_d['language']	=	$pbcore_essence_child['essencetracklanguage'][0]['text'];
																																				}

																																				$essence_tracks_id	=	$this->essence->insert_essence_tracks($essence_tracks_d);



																																				//essenceTrackIdentifier Start 
																																				if(isset($pbcore_essence_child['essencetrackidentifier'][0]['text'])	&&	!	is_empty($pbcore_essence_child['essencetrackidentifier'][0]['text'])
																																												&&	isset($pbcore_essence_child['essencetrackidentifiersource'][0]['text'])	&&	!	is_empty($pbcore_essence_child['essencetrackidentifiersource'][0]['text']))
																																				{
																																								$essence_track_identifiers_d	=	array();
																																								$essence_track_identifiers_d['essence_tracks_id']	=	$essence_tracks_id;
																																								$essence_track_identifiers_d['essence_track_identifiers']	=	$pbcore_essence_child['essencetrackidentifier'][0]['text'];
																																								$essence_track_identifiers_d['essence_track_identifier_source']	=	$pbcore_essence_child['essencetrackidentifiersource'][0]['text'];
																																								$this->essence->insert_essence_track_identifiers($essence_track_identifiers_d);
																																				}
																																				//essencetrackstandard Start 
																																				if(isset($pbcore_essence_child['essencetrackstandard'][0]['text'])	&&	!	is_empty($pbcore_essence_child['essencetrackstandard'][0]['text']))
																																				{
																																								$essence_track_standard_d	=	array();
																																								$essence_track_standard_d['essence_tracks_id']	=	$essence_tracks_id;
																																								$essence_track_standard_d['encoding']	=	$pbcore_essence_child['essencetrackstandard'][0]['text'];
																																								if(isset($pbcore_essence_child['essencetrackencoding'][0]['text']))
																																								{
																																												$essence_track_standard_d['encoding_source']	=	$pbcore_essence_child['essencetrackencoding'][0]['text'];
																																								}
																																								$this->essence->insert_essence_track_encodings($essence_track_identifiers_d);
																																				}

																																				//essenceTrackAnnotation Start
																																				if(isset($pbcore_essence_child['essencetrackannotation'])	&&	!	is_empty($pbcore_essence_child['essencetrackannotation']))
																																				{
																																								foreach($pbcore_essence_child['essencetrackannotation']	as	$trackannotation)
																																								{
																																												if(isset($trackannotation['text'])	&&	!	is_empty($trackannotation['text']))
																																												{
																																																$essencetrackannotation	=	array();
																																																$essencetrackannotation['essence_tracks_id']	=	$essence_tracks_id;
																																																$essencetrackannotation['annotation']	=	$trackannotation['text'];
																																																$this->essence->insert_essence_track_annotations($essencetrackannotation);
																																												}
																																								}
																																				}
																																}
																												}
																								}
																				}
																}
												}
								}
				}

				/*
					* Process Assets Elements
					*/

				function	process_assets($asset_children,	$asset_id)
				{
								// pbcoreAssetType Start here
								if(isset($asset_children['pbcoreassettype']))
								{
												foreach($asset_children['pbcoreassettype']	as	$pbcoreassettype)
												{

																if(isset($pbcoreassettype['text'])	&&	!	is_empty($pbcoreassettype['text']))
																{
																				$asset_type_d	=	array();
																				$asset_type_d['assets_id']	=	$asset_id;
																				if($asset_type	=	$this->assets_model->get_assets_type_by_type($pbcoreassettype['text']))
																				{
																								$asset_type_d['asset_types_id']	=	$asset_type->id;
																				}
																				else
																				{
																								$asset_type_d['asset_types_id']	=	$this->assets_model->insert_asset_types(array("asset_type"	=>	$pbcoreassettype['text']));
																				}
																				$this->assets_model->insert_assets_asset_types($asset_type_d);
																}
												}
								}

								// pbcoreAssetType End here
								// pbcoreidentifier Start here
								if(isset($asset_children['pbcoreidentifier']))
								{
												foreach($asset_children['pbcoreidentifier']	as	$pbcoreidentifier)
												{
																$identifier_d	=	array();
																//As Identfier is Required and based on identifiersource so apply following checks 
																if(isset($pbcoreidentifier['children']['identifier'][0]['text'])	&&	!	is_empty($pbcoreidentifier['children']['identifier'][0]['text']))
																{
																				$identifier_d['assets_id']	=	$asset_id;
																				$identifier_d['identifier']	=	trim($pbcoreidentifier['children']['identifier'][0]['text']);
																				$identifier_d['identifier_source']	=	'';
																				if(isset($pbcoreidentifier['children']['identifiersource'][0]['text'])	&&	!	is_empty($pbcoreidentifier['children']['identifiersource'][0]['text']))
																				{
																								$identifier_d['identifier_source']	=	trim($pbcoreidentifier['children']['identifiersource'][0]['text']);
																				}
																				if((isset($identifier_d['identifier'])	&&	!	is_empty($identifier_d['identifier'])))
																				{
																								$this->assets_model->insert_identifiers($identifier_d);
																				}
																				//print_r($identifier_d);	
																}
												}
								}
								// pbcoreidentifier End here
								// pbcoreTitle Start here
								if(isset($asset_children['pbcoretitle']))
								{
												foreach($asset_children['pbcoretitle']	as	$pbcoretitle)
												{
																$pbcore_title_d	=	array();
																if(isset($pbcoretitle['children']['title'][0]['text'])	&&	!	is_empty($pbcoretitle['children']['title'][0]['text']))
																{
																				$pbcore_title_d['assets_id']	=	$asset_id;
																				$pbcore_title_d['title']	=	$pbcoretitle['children']['title'][0]['text'];
																				// As this Field is not required so this can be empty
																				if(isset($pbcoretitle['children']['titletype'][0]['text'])	&&	!	is_empty($pbcoretitle['children']['titletype'][0]['text']))
																				{
																								$asset_title_types	=	$this->assets_model->get_asset_title_types_by_title_type($pbcoretitle['children']['titletype'][0]['text']);
																								if(isset($asset_title_types)	&&	isset($asset_title_types->id))
																								{
																												$asset_title_types_id	=	$asset_title_types->id;
																								}
																								else
																								{
																												$asset_title_types_id	=	$this->assets_model->insert_asset_title_types(array("title_type"																												=>	$pbcoretitle['children']['titletype'][0]['text']));
																								}
																								$pbcore_title_d['asset_title_types_id']	=	$asset_title_types_id;
																				}
																				$pbcore_title_d['created']	=	date('Y-m-d H:i:s');
																				//For 2.0 
																				// $pbcore_title_d['title_source'] 
																				// $pbcore_title_d['title_ref']
																				//print_r($pbcore_title_d);	
																				$this->assets_model->insert_asset_titles($pbcore_title_d);
																}
												}
								}
								// pbcoreTitle End here
								// pbcoreSubject Start here
								if(isset($asset_children['pbcoresubject']))
								{
												foreach($asset_children['pbcoresubject']	as	$pbcore_subject)
												{
																$pbcoreSubject_d	=	array();
																if(isset($pbcore_subject['children']['subject'][0]))
																{
																				$pbcoreSubject_d['assets_id']	=	$asset_id;
																				if(isset($pbcore_subject['children']['subject'][0]['text'])	&&	!	is_empty($pbcore_subject['children']['subject'][0]['text']))
																				{
																								$subjects	=	$this->assets_model->get_subjects_id_by_subject($pbcore_subject['children']['subject'][0]['text']);
																								if(isset($subjects)	&&	isset($subjects->id))
																								{
																												$subject_id	=	$subjects->id;
																								}
																								else
																								{
																												//For 2.0  also add following value in insert array of subject
																												//subject_ref
																												$subject_d	=	array();
																												$subject_d['subject']	=	$pbcore_subject['children']['subject'][0]['text'];
																												$subject_d['subject_source']	=	'';
																												if(isset($pbcore_subject['children']['subjectauthorityused'][0]['text'])	&&	!	is_empty($pbcore_subject['children']['subjectauthorityused'][0]['text']))
																												{
																																$subject_d['subject_source']	=	$pbcore_subject['children']['subjectauthorityused'][0]['text'];
																												}
																												$subject_id	=	$this->assets_model->insert_subjects($subject_d);
																								}
																								$pbcoreSubject_d['subjects_id']	=	$subject_id;
																								//Add Data into insert_assets_subjects
																								$assets_subject_id	=	$this->assets_model->insert_assets_subjects($pbcoreSubject_d);
																				}
																}
												}
								}
								// pbcoreSubject End here
								// pbcoreDescription Start here
								if(isset($asset_children['pbcoredescription']))
								{
												foreach($asset_children['pbcoredescription']	as	$pbcore_description)
												{
																$asset_descriptions_d	=	array();
																if(isset($pbcore_description['children']['description'][0]['text'])	&&	!	is_empty($pbcore_description['children']['description'][0]['text']))
																{
																				$asset_descriptions_d['assets_id']	=	$asset_id;
																				$asset_descriptions_d['description']	=	$pbcore_description['children']['description'][0]['text'];
																				if(isset($pbcoretitle['children']['descriptiontype'][0]['text'])	&&	!	is_empty($pbcoretitle['children']['descriptiontype'][0]['text']))
																				{
																								$asset_description_type	=	$this->assets_model->get_description_by_type($pbcoretitle['children']['descriptiontype'][0]['text']);
																								if(isset($asset_description_type)	&&	isset($asset_description_type->id))
																								{
																												$asset_description_types_id	=	$asset_description_type->id;
																								}
																								else
																								{
																												$asset_description_types_id	=	$this->assets_model->insert_asset_title_types(array("description_type"																												=>	$pbcoretitle['children']['descriptiontype'][0]['text']));
																								}
																								$asset_descriptions_d['description_types_id']	=	$asset_title_types_id;
																				}
																				// Insert Data into asset_description
																				//print_r($asset_descriptions_d);
																				$this->assets_model->insert_asset_descriptions($asset_descriptions_d);
																}
												}
								}
								// pbcoreDescription End here
								// Nouman Tayyab
								// pbcoreGenre Start
								if(isset($asset_children['pbcoregenre'])	&&	!	is_empty($asset_children['pbcoregenre']))
								{
												foreach($asset_children['pbcoregenre']	as	$pbcore_genre)
												{
																$asset_genre_d	=	array();
																$asset_genre	=	array();
																$asset_genre['assets_id']	=	$asset_id;
																if(isset($pbcore_genre['children']['genre'][0]['text'])	&&	!	is_empty($pbcore_genre['children']['genre'][0]['text']))
																{

																				$asset_genre_d['genre']	=	$pbcore_genre['children']['genre'][0]['text'];
																				$asset_genre_type	=	$this->assets_model->get_genre_type($asset_genre_d['genre']);
																				if(isset($asset_genre_type)	&&	isset($asset_genre_type->id))
																				{
																								$asset_genre['genres_id']	=	$asset_genre_type->id;
																				}
																				else
																				{
																								$asset_genre_d['genre_source']	=	'';
																								if(isset($pbcore_genre['children']['genreauthorityused'][0]['text'])	&&	!	is_empty($pbcore_genre['children']['genreauthorityused'][0]['text']))
																								{
																												$asset_genre_d['genre_source']	=	$pbcore_genre['children']['genreauthorityused'][0]['text'];
																								}
																								$asset_genre_id	=	$this->assets_model->insert_genre($asset_genre_d);
																								$asset_genre['genres_id']	=	$asset_genre_id;
																				}
																				$this->assets_model->insert_asset_genre($asset_genre);
																}
												}
								}
								// pbcoreGenre End
								// pbcoreCoverage Start
								if(isset($asset_children['pbcorecoverage'])	&&	!	is_empty($asset_children['pbcorecoverage']))
								{
												foreach($asset_children['pbcorecoverage']	as	$pbcore_coverage)
												{
																$coverage	=	array();
																$coverage['assets_id']	=	$asset_id;
																if(isset($pbcore_coverage['children']['coverage'][0]['text'])	&&	!	is_empty($pbcore_coverage['children']['coverage'][0]['text']))
																{
																				$coverage['coverage']	=	$pbcore_coverage['children']['coverage'][0]['text'];
																				if(isset($pbcore_coverage['children']['coveragetype'][0]['text'])	&&	!	is_empty($pbcore_coverage['children']['coveragetype'][0]['text']))
																				{
																								$coverage['coverage_type']	=	$pbcore_coverage['children']['coveragetype'][0]['text'];
																				}
																				$asset_coverage	=	$this->assets_model->insert_coverage($coverage);
																}
												}
								}
								// pbcoreCoverage End
								// pbcoreAudienceLevel Start
								if(isset($asset_children['pbcoreaudiencelevel']))
								{
												foreach($asset_children['pbcoreaudiencelevel']	as	$pbcore_aud_level)
												{
																$audience_level	=	array();
																$asset_audience_level	=	array();
																$asset_audience_level['assets_id']	=	$asset_id;
																if(isset($pbcore_aud_level['children']['audiencelevel'][0]['text'])	&&	!	is_empty($pbcore_aud_level['children']['audiencelevel'][0]['text']))
																{
																				$audience_level['audience_level']	=	trim($pbcore_aud_level['children']['audiencelevel'][0]['text']);
																				if(isset($audience_level['audience_level'])	&&	!	is_empty($audience_level['audience_level']))
																				{
																								$db_audience_level	=	$this->assets_model->get_audience_level($audience_level['audience_level']);
																								if(isset($db_audience_level)	&&	isset($db_audience_level->id))
																								{
																												$asset_audience_level['audience_levels_id']	=	$db_audience_level->id;
																								}
																								else
																								{
																												$asset_audience_level['audience_levels_id']	=	$this->assets_model->insert_audience_level($audience_level);
																								}
																								$asset_audience	=	$this->assets_model->insert_asset_audience($asset_audience_level);
																				}
																}
												}
								}
								// pbcoreAudienceLevel End
								// pbcoreAudienceRating Start
								if(isset($asset_children['pbcoreaudiencerating']))
								{

												foreach($asset_children['pbcoreaudiencerating']	as	$pbcore_aud_rating)
												{
																$audience_rating	=	array();
																$asset_audience_rating	=	array();
																$asset_audience_rating['assets_id']	=	$asset_id;
																if(isset($pbcore_aud_rating['children']['audiencerating'][0]['text'])	&&	!	is_empty($pbcore_aud_rating['children']['audiencerating'][0]['text']))
																{
																				$audience_rating['audience_rating']	=	trim($pbcore_aud_rating['children']['audiencerating'][0]['text']);
																				if(isset($audience_rating['audience_rating'])	&&	!	is_empty($audience_rating['audience_rating']))
																				{
																								$db_audience_rating	=	$this->assets_model->get_audience_rating($audience_rating['audience_rating']);
																								if(isset($db_audience_rating)	&&	isset($db_audience_rating->id))
																								{
																												$asset_audience_rating['audience_ratings_id']	=	$db_audience_rating->id;
																								}
																								else
																								{
																												$asset_audience_rating['audience_ratings_id']	=	$this->assets_model->insert_audience_rating($audience_rating);
																								}
																								$asset_audience_rate	=	$this->assets_model->insert_asset_audience_rating($asset_audience_rating);
																				}
																}
												}
								}
								// pbcoreAudienceRating End
								// pbcoreAnnotation Start
								if(isset($asset_children['pbcoreannotation']))
								{

												foreach($asset_children['pbcoreannotation']	as	$pbcore_annotation)
												{
																$annotation	=	array();
																$annotation['assets_id']	=	$asset_id;
																if(isset($pbcore_annotation['children']['annotation'][0]['text'])	&&	!	is_empty($pbcore_annotation['children']['annotation'][0]['text']))
																{
																				$annotation['annotation']	=	$pbcore_annotation['children']['annotation'][0]['text'];
																				$asset_annotation	=	$this->assets_model->insert_annotation($annotation);
																}
												}
								}
								// pbcoreAnnotation End
								// pbcoreRelation Start here
								if(isset($asset_children['pbcorerelation']))
								{

												foreach($asset_children['pbcorerelation']	as	$pbcore_relation)
												{
																$assets_relation	=	array();
																$assets_relation['assets_id']	=	$asset_id;
																$relation_types	=	array();
																if(isset($pbcore_relation['children']['relationtype'][0]['text'])	&&	!	is_empty($pbcore_relation['children']['relationtype'][0]['text']))
																{
																				$relation_types['relation_type']	=	$pbcore_relation['children']['relationtype'][0]['text'];
																				$db_relations	=	$this->assets_model->get_relation_types($relation_types['relation_type']);
																				if(isset($db_relations)	&&	isset($db_relations->id))
																				{
																								$assets_relation['relation_types_id']	=	$db_relations->id;
																				}
																				else
																				{
																								$assets_relation['relation_types_id']	=	$this->assets_model->insert_relation_types($relation_types);
																				}
																				if(isset($pbcore_relation['children']['relationidentifier'][0]['text'])	&&	!	is_empty($pbcore_relation['children']['relationidentifier'][0]['text']))
																				{
																								$assets_relation['relation_identifier']	=	$pbcore_relation['children']['relationidentifier'][0]['text'];
																								$this->assets_model->insert_asset_relation($assets_relation);
																				}
																}
												}
								}
								// pbcoreRelation End here
								// End By Nouman Tayyab
								// Start By Ali Raza
								// pbcoreCreator Start here
								if(isset($asset_children['pbcorecreator']))
								{
												foreach($asset_children['pbcorecreator']	as	$pbcore_creator)
												{
																$assets_creators_roles_d	=	array();
																$assets_creators_roles_d['assets_id']	=	$asset_id;
																$creator_d	=	array();
																$creator_role	=	array();
																if(isset($pbcore_creator['children']['creator'][0]['text'])	&&	!	is_empty($pbcore_creator['children']['creator'][0]['text']))
																{
																				$creator_d	=	$this->assets_model->get_creator_by_creator_name($pbcore_creator['children']['creator'][0]['text']);
																				if(isset($creator_d)	&&	isset($creator_d->id))
																				{
																								$assets_creators_roles_d['creators_id']	=	$creator_d->id;
																				}
																				else
																				{
																								// creator_affiliation , creator_source ,creator_ref
																								$assets_creators_roles_d['creators_id']	=	$this->assets_model->insert_creators(array('creator_name'	=>	$pbcore_creator['children']['creator'][0]['text']));
																				}
																}
																if(isset($pbcore_creator['children']['creatorrole'][0]['text'])	&&	!	is_empty($pbcore_creator['children']['creatorrole'][0]['text']))
																{
																				$creator_role	=	$this->assets_model->get_creator_role_by_role($pbcore_creator['children']['creatorrole'][0]['text']);
																				if(isset($creator_role)	&&	isset($creator_role->id))
																				{
																								$assets_creators_roles_d['creator_roles_id']	=	$creator_role->id;
																				}
																				else
																				{
																								// creator_role_ref , creator_role_source
																								$assets_creators_roles_d['creator_roles_id']	=	$this->assets_model->insert_creator_roles(array('creator_role'	=>	$pbcore_creator['children']['creatorrole'][0]['text']));
																				}
																}
																//print_r($assets_creators_roles_d);
																if((isset($assets_creators_roles_d['creators_id'])	&&	!	is_empty($assets_creators_roles_d['creators_id']))	||	(isset($assets_creators_roles_d['creator_roles_id'])	&&	!	is_empty($assets_creators_roles_d['creator_roles_id'])))
																{
																				$assets_creators_roles_id	=	$this->assets_model->insert_assets_creators_roles($assets_creators_roles_d);
																}
												}
								}
								// pbcoreCreator End here
								// pbcoreContributor Start here
								if(isset($asset_children['pbcorecontributor']))
								{
												foreach($asset_children['pbcorecontributor']	as	$pbcore_contributor)
												{
																$assets_contributors_d	=	array();
																$assets_contributors_d['assets_id']	=	$asset_id;
																$contributor_d	=	array();
																$contributor_role	=	array();
																if(isset($pbcore_contributor['children']['contributor'][0]['text'])	&&	!	is_empty($pbcore_contributor['children']['contributor'][0]['text']))
																{
																				$contributor_text	=	trim($pbcore_contributor['children']['contributor'][0]['text']);
																				if(isset($contributor_text)	&&	!	is_empty($contributor_text))
																				{
																								$contributor_d	=	$this->assets_model->get_contributor_by_contributor_name($contributor_text);
																								if(isset($contributor_d)	&&	isset($contributor_d->id))
																								{
																												$assets_contributors_d['contributors_id']	=	$contributor_d->id;
																								}
																								else
																								{
																												// contributor_affiliation ,	contributor_source, 	contributor_ref 
																												$last_insert_id	=	$this->assets_model->insert_contributors(array('contributor_name'	=>	$contributor_text));
																												if(isset($last_insert_id)	&&	$last_insert_id	>	0)
																												{
																																$assets_contributors_d['contributors_id']	=	$last_insert_id;
																												}
																								}
																				}
																}
																if(isset($pbcore_contributor['children']['contributorrole'][0]['text'])	&&	!	is_empty($pbcore_contributor['children']['contributorrole'][0]['text']))
																{
																				$contributorrole	=	trim($pbcore_contributor['children']['contributorrole'][0]['text']);
																				if(isset($contributorrole)	&&	!	is_empty($contributorrole))
																				{
																								$contributor_role	=	$this->assets_model->get_contributor_role_by_role($contributorrole);
																								if(isset($contributor_role)	&&	isset($contributor_role->id))
																								{
																												$assets_contributors_d['contributor_roles_id']	=	$contributor_role->id;
																								}
																								else
																								{
																												// contributor_role_source ,	contributor_role_ref 
																												$last_insert_id	=	$this->assets_model->insert_contributor_roles(array('contributor_role'	=>	$contributorrole));
																												if(isset($last_insert_id)	&&	$last_insert_id	>	0)
																												{
																																$assets_contributors_d['contributor_roles_id']	=	$last_insert_id;
																												}
																								}
																				}
																}
																if((isset($assets_contributors_d['contributors_id'])	&&	!	is_empty($assets_contributors_d['contributors_id']))	||
																								(isset($assets_contributors_d['contributor_roles_id'])	&&	!	is_empty($assets_contributors_d['contributor_roles_id'])))
																{
																				$assets_contributors_roles_id	=	$this->assets_model->insert_assets_contributors_roles($assets_contributors_d);
																}
												}
								}
								// pbcorecontributor End here
								// pbcorePublisher Start here
								if(isset($asset_children['pbcorepublisher']))
								{
												foreach($asset_children['pbcorepublisher']	as	$pbcore_publisher)
												{
																$assets_publisher_d	=	array();
																$assets_publisher_d['assets_id']	=	$asset_id;
																$publisher_d	=	array();
																$publisher_role	=	array();
																if(isset($pbcore_publisher['children']['publisher'][0]['text'])	&&	!	is_empty($pbcore_publisher['children']['publisher'][0]['text']))
																{
																				$publisher_d	=	$this->assets_model->get_publishers_by_publisher($pbcore_publisher['children']['publisher'][0]['text']);
																				if(isset($publisher_d)	&&	isset($publisher_d->id))
																				{
																								$assets_publisher_d['publishers_id']	=	$publisher_d->id;
																				}
																				else
																				{
																								// publisher_affiliation ,	publisher_ref 
																								$assets_publisher_d['publishers_id']	=	$this->assets_model->insert_publishers(array('publisher'	=>	$pbcore_publisher['children']['publisher'][0]['text']));
																				}
																				//Insert Data into asset_description
																}
																if(isset($pbcore_publisher['children']['publisherrole'][0]['text'])	&&	!	is_empty($pbcore_publisher['children']['publisherrole'][0]['text']))
																{
																				$publisher_role	=	$this->assets_model->get_publisher_role_by_role($pbcore_publisher['children']['publisherrole'][0]['text']);
																				if(isset($publisher_role)	&&	isset($publisher_role->id))
																				{
																								$assets_publisher_d['publisher_roles_id']	=	$publisher_role->id;
																				}
																				else
																				{
																								// publisher_role_ref ,	publisher_role_source 
																								$assets_publisher_d['publisher_roles_id']	=	$this->assets_model->insert_publisher_roles(array('publisher_role'	=>	$pbcore_publisher['children']['publisherrole'][0]['text']));
																				}
																}
																//print_r($assets_publisher_d);
																if((isset($assets_publisher_d['publishers_id'])	&&	!	is_empty($assets_publisher_d['publishers_id']))	||	(isset($assets_publisher_d['publisher_roles_id'])	&&	!	is_empty($assets_publisher_d['publisher_roles_id'])))
																{
																				$assets_publishers_roles_id	=	$this->assets_model->insert_assets_publishers_role($assets_publisher_d);
																}
												}
								}
								// pbcorePublisher End here
								// pbcoreRightsSummary Start
								if(isset($asset_children['pbcorerightssummary'])	&&	!	is_empty($asset_children['pbcorerightssummary']))
								{
												foreach($asset_children['pbcorerightssummary']	as	$pbcore_rights_summary)
												{
																$rights_summary_d	=	array();
																$rights_summary_d['assets_id']	=	$asset_id;
																if(isset($pbcore_rights_summary['children']['rightssummary'][0]['text'])	&&	!	is_empty($pbcore_rights_summary['children']['rightssummary'][0]['text']))
																{
																				$rights_summary_d['rights']	=	$pbcore_rights_summary['children']['rightssummary'][0]['text'];
																				//print_r($rights_summary_d);
																				$rights_summary_ids[]	=	$this->assets_model->insert_rights_summaries($rights_summary_d);
																}
												}
								}
								// pbcoreRightsSummary End
								//pbcoreExtension Start
								if(isset($asset_children['pbcoreextension'])	&&	!	is_empty($asset_children['pbcoreextension']))
								{
												foreach($asset_children['pbcoreextension']	as	$pbcore_extension)
												{
																if(isset($pbcore_extension['children']['extensionauthorityused'][0]['text'])	&&	!	is_empty($pbcore_extension['children']['extensionauthorityused'][0]['text']))
																{

																				if(strtolower($pbcore_extension['children']['extensionauthorityused'][0]['text'])	!=	strtolower('AACIP Record Nomination Status'))
																				{
																								$extension_d	=	array();
																								$extension_d['assets_id']	=	$asset_id;
																								$extension_d['extension_element']	=	$pbcore_extension['children']['extensionauthorityused'][0]['text'];
																								if(isset($pbcore_extension['children']['extension'][0]['text'])	&&	!	is_empty($pbcore_extension['children']['extension'][0]['text']))
																								{
																												$extension_d['extension_value']	=	$pbcore_extension['children']['extension'][0]['text'];
																								}

																								$this->assets_model->insert_extensions($extension_d);
																				}
																}
												}
								}
								//pbcoreExtension End
								// End By Ali Raza
				}

				function	import_media_files()
				{
								$index	=	$this->uri->segment(3);
								if($index	==	''	||	$index	<	1	||	$index	>	5)
												debug('Please Enter # b/w 1-5 included.');
								$file_name	=	array(
								'1'																		=>	'cpb-aacip-37-62f7m7dx.h264.mov.mediainfo.xml',
								'2'																		=>	'cpb-aacip-37-62f7m7dx.j2k.mxf.mediainfo.xml',
								'3'																		=>	'cpb-aacip-37-62f7m7dx.mpeg2.mxf.mediainfo.xml',
								'4'																		=>	'cpb-aacip-169-1937q01m.mp3.mediainfo.xml',
								'5'																		=>	'cpb-aacip-169-1937q01m.wav.mediainfo.xml',
								);
								echo	'<br/>File: '	.	$file_name[$index]	.	'<br/>';
								$data	=	file_get_contents($this->assets_path	.	'mediainfo/'	.	$file_name[$index]);
								$x	=	@simplexml_load_string($data);
								$data	=	xmlObjToArr($x);
								$tracks_data	=	$data['children']['file'][0]['children']['track'];
								$db_asset_id	=	NULL;
								$db_instantiation_id	=	NULL;
								$db_essence_track_id	=	NULL;
								if(isset($tracks_data)	&&	count($tracks_data)	>	0)
								{
												$instantiation	=	array();
												$instantiation['digitized']	=	1;
												echo	'<br/>Digitized= '	.	$instantiation['digitized'];
												$instantiation['location']	=	'N/A';
												echo	'<br/>Location= ';
												$essence_track	=	array();
												$dessence_track	=	array();
												$dessence_track_counter	=	0;

												foreach($tracks_data	as	$index	=>	$track)
												{
																if(isset($track['attributes']['type'])	&&	$track['attributes']['type']	===	'General')
																{
																				$general_track	=	$track['children'];
																				/* Media Type Start */
																				$media_type	=	'';
																				if(isset($general_track['videocount'])	&&	isset($general_track['videocount'][0]))
																				{

																								if(	!	empty($general_track['videocount'][0]['text'])	||	$general_track['videocount'][0]['text']	!=	NULL	||	$general_track['videocount'][0]['text']	>	0)
																								{
																												$media_type	=	'Moving Image';
																								}
																								else	if($general_track['videocount'][0]['text']	==	0)
																								{
																												if(isset($general_track['audiocount'])	&&	isset($general_track['audiocount'][0]))
																												{
																																if(	!	empty($general_track['audiocount'][0]['text'])	||	$general_track['audiocount'][0]['text']	!=	NULL)
																																{
																																				$media_type	=	'Sound';
																																}
																												}
																								}
																								if($media_type	!=	'')
																								{
																												echo	'<br/>Media Type = '	.	$media_type;
																												$inst_media_type	=	$this->instant->get_instantiation_media_types_by_media_type($media_type);
																												if(	!	is_empty($inst_media_type))
																																$instantiation['instantiation_media_type_id']	=	$inst_media_type->id;
																												else
																																$instantiation['instantiation_media_type_id']	=	$this->instant->insert_instantiation_media_types(array('media_type'	=>	$media_type));
																								}
																				}
																				else	if(isset($general_track['audiocount'])	&&	isset($general_track['audiocount'][0]))
																				{
																								if(	!	empty($general_track['audiocount'][0]['text'])	||	$general_track['audiocount'][0]['text']	!=	NULL)
																								{
																												$media_type	=	'Sound';
																												echo	'<br/>Media Type = '	.	$media_type;
																												$inst_media_type	=	$this->instant->get_instantiation_media_types_by_media_type($media_type);
																												if(	!	is_empty($inst_media_type))
																																$instantiation['instantiation_media_type_id']	=	$inst_media_type->id;
																												else
																																$instantiation['instantiation_media_type_id']	=	$this->instant->insert_instantiation_media_types(array('media_type'	=>	$media_type));
																								}
																				}
																				/* Media Type End */
																				/* Actual Duration Start */
																				if(isset($general_track['duration_string3'])	&&	isset($general_track['duration_string3'][0]))
																				{
																								if(	!	empty($general_track['duration_string3'][0]['text']))
																								{
																												$instantiation['actual_duration']	=	date('H:i:s',	strtotime($general_track['duration_string3'][0]['text']));
																												echo	'<br/>Actual Duration = '	.	$instantiation['actual_duration'];
																								}
																				}
																				/* Actual Duration End */
																				/* Standard Start */
																				if(isset($general_track['format_profile'])	&&	isset($general_track['format_profile'][0]))
																				{

																								if(	!	empty($general_track['format_profile'][0]['text'])	||	$general_track['format_profile'][0]['text']	!=	NULL)
																								{
																												$instantiation['standard']	=	$general_track['format_profile'][0]['text'];
																												echo	'<br/>Standard = '	.	$instantiation['standard'];
																								}
																								else	if(isset($general_track['format'])	&&	isset($general_track['format'][0]))
																								{
																												if(	!	empty($general_track['format'][0]['text'])	||	$general_track['format'][0]['text']	!=	NULL)
																												{
																																$instantiation['standard']	=	$general_track['format'][0]['text'];
																																echo	'<br/>Standard = '	.	$instantiation['standard'];
																												}
																								}
																				}
																				else	if(isset($general_track['format'])	&&	isset($general_track['format'][0]))
																				{
																								if(	!	empty($general_track['format'][0]['text'])	||	$general_track['format'][0]['text']	!=	NULL)
																								{
																												$instantiation['standard']	=	$general_track['format'][0]['text'];
																												echo	'<br/>Standard = '	.	$instantiation['standard'];
																								}
																				}
																				/* Standard End */
																				/* Tracks Start */
																				$instantiation['tracks']	=	'';
																				if(isset($general_track['videocount'])	&&	isset($general_track['videocount'][0]))
																				{
																								if(	!	empty($general_track['videocount'][0]['text']))
																								{
																												$add_comma	=	'';
																												if($instantiation['tracks']	!==	'')
																																$add_comma	=	', ';

																												$instantiation['tracks'].=$add_comma	.	$general_track['videocount'][0]['text']	.	' video';
																								}
																				}
																				if(isset($general_track['audiocount'])	&&	isset($general_track['audiocount'][0]))
																				{
																								if(	!	empty($general_track['audiocount'][0]['text']))
																								{
																												$add_comma	=	'';
																												if($instantiation['tracks']	!==	'')
																																$add_comma	=	', ';
																												$instantiation['tracks'].=$add_comma	.	$general_track['audiocount'][0]['text']	.	' audio';
																								}
																				}
																				if(isset($general_track['menucount'])	&&	isset($general_track['menucount'][0]))
																				{
																								if(	!	empty($general_track['menucount'][0]['text']))
																								{
																												$add_comma	=	'';
																												if($instantiation['tracks']	!==	'')
																																$add_comma	=	', ';
																												$instantiation['tracks'].=$add_comma	.	$general_track['menucount'][0]['text']	.	' menu';
																								}
																				}
																				if(isset($general_track['textcount'])	&&	isset($general_track['textcount'][0]))
																				{
																								if(	!	empty($general_track['textcount'][0]['text']))
																								{
																												$add_comma	=	'';
																												if($instantiation['tracks']	!==	'')
																																$add_comma	=	', ';
																												$instantiation['tracks'].=$add_comma	.	$general_track['textcount'][0]['text']	.	' text';
																								}
																				}
																				echo	'<br/>Tracks = '	.	$instantiation['tracks'];
																				/* Tracks End */
																				/* Data Rate Start */
																				if(isset($general_track['overallbitrate_string'])	&&	isset($general_track['overallbitrate_string'][0]))
																				{
																								if(	!	empty($general_track['overallbitrate_string'][0]['text']))
																								{
																												$datarate	=	explode(' ',	$general_track['overallbitrate_string'][0]['text']);
																												$instantiation['data_rate']	=	(isset($datarate[0]))	?	$datarate[0]	:	'';
																												echo	'<br/>Data Rate = '	.	$instantiation['data_rate'];
																												$data_rate_unit	=	(isset($datarate[1]))	?	$datarate[1]	:	'';
																												echo	'<br/>Data Rate Unit = '	.	$data_rate_unit;
																												if($data_rate_unit	!=	'')
																												{
																																$inst_media_type	=	$this->instant->get_data_rate_units_by_unit($data_rate_unit);
																																if(	!	is_empty($inst_media_type))
																																				$instantiation['data_rate_units_id']	=	$inst_media_type->id;
																																else
																																				$instantiation['data_rate_units_id']	=	$this->instant->insert_data_rate_units(array('unit_of_measure'	=>	$data_rate_unit));
																												}
																								}
																				}
																				/* Data Rate End */
																				/* File Size Start */
																				if(isset($general_track['filesize_string4'])	&&	isset($general_track['filesize_string4'][0]))
																				{
																								if(	!	empty($general_track['filesize_string4'][0]['text']))
																								{
																												$filesize	=	explode(' ',	$general_track['filesize_string4'][0]['text']);
																												$instantiation['file_size']	=	(isset($filesize[0]))	?	$filesize[0]	:	'';
																												$instantiation['file_size_unit_of_measure']	=	(isset($filesize[1]))	?	$filesize[1]	:	'';
																												echo	'<br/>Fize Size = '	.	$instantiation['file_size'];
																												echo	'<br/>File Size Unit = '	.	$instantiation['file_size_unit_of_measure'];
																								}
																				}
																				/* File Size End */
																				/* Identifier and Generation Start */
																				if(isset($general_track['filename'])	&&	isset($general_track['filename'][0]))
																				{

																								if(isset($general_track['fileextension'])	&&	isset($general_track['fileextension'][0]))
																								{
																												$identifier['instantiation_identifier']	=	$general_track['filename'][0]['text'];

																												$db_asset_id	=	$this->get_asset_id_for_media_import($identifier['instantiation_identifier']);

																												$identifier['instantiation_identifier']	=	$general_track['filename'][0]['text']	.	'.'	.	$general_track['fileextension'][0]['text'];
																												echo	'<br/>Instantitation Identifier = '	.	$identifier['instantiation_identifier'];
																												if($db_asset_id)
																												{
																																$instantiation['assets_id']	=	$db_asset_id;
																												}

																												$identifier['instantiation_source']	=	'mediainfo';
																												echo	'<br/>Instantitation Identifier source = '	.	$identifier['instantiation_source'];
																												$db_instantiation_id	=	$this->instant->insert_instantiations($instantiation);
																												$identifier['instantiations_id']	=	$db_instantiation_id;
																												$this->instant->insert_instantiation_identifier($identifier);
																												$filename	=	$identifier['instantiation_identifier'];
																												$generation	=	'';
																												if(strstr($filename,	'.j2k.mxf'))
																												{
																																$generation	=	'Preservation Master';
																												}
																												else	if(strstr($filename,	'.mpeg2.mxf'))
																												{
																																$generation	=	'Mezzanine';
																												}
																												else	if(strstr($filename,	'.h264.mov'))
																												{
																																$generation	=	'Proxy';
																												}
																												else	if(strstr($filename,	'.wav'))
																												{
																																$generation	=	'Preservation Master';
																												}
																												else	if(strstr($filename,	'.mp3'))
																												{
																																$generation	=	'Proxy';
																												}
																												if($generation	!=	'')
																												{
																																echo	'<br/>Generation = '	.	$generation;
																																$generations_d	=	$this->instant->get_generations_by_generation($generation);
																																if(isset($generations_d)	&&	isset($generations_d->id))
																																{
																																				$generations['generations_id']	=	$generations_d->id;
																																}
																																else
																																{
																																				$generations['generations_id']	=	$this->instant->insert_generations(array("generation"																						=>	$generation));
																																}
																																$generations['instantiations_id']	=	$db_instantiation_id;
																																$this->instant->insert_instantiation_generations($generations);
																												}
																								}
																				}
																				/* Identifier and Generation End */
																				/* Instantiation Date Start */
																				if(isset($general_track['encoded_date'])	&&	isset($general_track['encoded_date'][0]))
																				{

																								if(	!	empty($general_track['encoded_date'][0]['text'])	||	$general_track['encoded_date'][0]['text']	!=	NULL)
																								{
																												$date['instantiation_date']	=	date('Y-m-d',	strtotime($general_track['encoded_date'][0]['text']));
																												echo	'<br/>Instantitation Date = '	.	$date['instantiation_date'];
																												echo	'<br/>Instantitation Date Type = encoded';
																								}
																								else	if(isset($general_track['file_modified_date'])	&&	isset($general_track['file_modified_date'][0]))
																								{
																												$date['instantiation_date']	=	date('Y-m-d',	strtotime($general_track['file_modified_date'][0]['text']));
																												echo	'<br/>Instantitation Date = '	.	$date['instantiation_date'];
																												echo	'<br/>Instantitation Date Type = encoded';
																								}
																								if(isset($date['instantiation_date'])	&&	$date['instantiation_date']	!=	'')
																								{
																												$date_type	=	$this->instant->get_date_types_by_type('encoded');
																												if(isset($date_type)	&&	isset($date_type->id))
																												{
																																$date['date_types_id']	=	$date_type->id;
																												}
																												else
																												{
																																$date['date_types_id']	=	$this->instant->insert_date_types(array('date_type'																=>	'encoded'));
																												}
																												$date['instantiations_id']	=	$db_instantiation_id;
																												$this->instant->insert_instantiation_dates($date);
																								}
																				}
																				else	if(isset($general_track['file_modified_date'])	&&	isset($general_track['file_modified_date'][0]))
																				{


																								$date['instantiation_date']	=	date('Y-m-d',	strtotime($general_track['file_modified_date'][0]['text']));
																								echo	'<br/>Instantitation Date = '	.	$date['instantiation_date'];
																								echo	'<br/>Instantitation Date Type = encoded';
																								if(isset($date['instantiation_date'])	&&	$date['instantiation_date']	!=	'')
																								{
																												$date_type	=	$this->instant->get_date_types_by_type('encoded');
																												if(isset($date_type)	&&	isset($date_type->id))
																												{
																																$date['date_types_id']	=	$date_type->id;
																												}
																												else
																												{
																																$date['date_types_id']	=	$this->instant->insert_date_types(array('date_type'																=>	'encoded'));
																												}
																												$date['instantiations_id']	=	$db_instantiation_id;
																												$this->instant->insert_instantiation_dates($date);
																								}
																				}
																				/* Instantiation Date End */
																				/* Instantiation Format Start */
																				if(isset($general_track['internetmediatype'])	&&	isset($general_track['internetmediatype'][0]))
																				{

																								$format['format_name']	=	$general_track['internetmediatype'][0]['text'];
																								$format['format_type']	=	'digital';
																								echo	'<br/>Instantitation Format = '	.	$format['format_name'];
																								echo	'<br/>Instantitation Format Type = digital';
																								$format['instantiations_id']	=	$db_instantiation_id;
																								$this->instant->insert_instantiation_formats($format);
																				}
																				/* Instantiation Format End */
																				/* Instantiation Annotation Start */
																				if(isset($general_track['encoded_library_string'])	&&	isset($general_track['encoded_library_string'][0]))
																				{
																								if(	!	empty($general_track['encoded_library_string'][0]['text']))
																								{
																												$annotation['annotation']	=	$general_track['encoded_library_string'][0]['text'];
																												$annotation['annotation_type']	=	'encoded by';
																												echo	'<br/>Instantitation annotation = '	.	$annotation['annotation'];
																												echo	'<br/>Instantitation annotation Type = '	.	$annotation['annotation_type'];
																												$annotation['instantiations_id']	=	$db_instantiation_id;
																												$this->instant->insert_instantiation_annotations($annotation);
																								}
																				}
																				else	if(isset($general_track['encodedby'])	&&	isset($general_track['encodedby'][0]))
																				{
																								if(	!	empty($general_track['encodedby'][0]['text']))
																								{
																												$annotation['annotation']	=	$general_track['encodedby'][0]['text'];
																												$annotation['annotation_type']	=	'encoded by';
																												echo	'<br/>Instantitation annotation = '	.	$annotation['annotation'];
																												echo	'<br/>Instantitation annotation Type = '	.	$annotation['annotation_type'];
																												$annotation['instantiations_id']	=	$db_instantiation_id;
																												$this->instant->insert_instantiation_annotations($annotation);
																								}
																				}
																				/* Instantiation Annotation End */
																}
																else
																{
																				unset($essence_track);

																				if(isset($track['attributes']['type'])	&&	$track['attributes']['type']	===	'Audio')
																				{

																								$audio_track	=	$track['children'];

																								if(isset($audio_track['channel_s__string'])	&&	isset($audio_track['channel_s__string'][0]))
																								{
																												if(isset($audio_track['channel_s__string'][0]['text']))
																												{
																																$channel	=	substr_replace($audio_track['channel_s__string'][0]['text'],	"",	-1);
																																echo	'<br/>Channel Configuration = '	.	$channel;

																																$this->instant->update_instantiations($db_instantiation_id,	array('channel_configuration'	=>	$channel));
																												}
																								}
																								if(isset($audio_track['samplingrate_string'])	&&	isset($audio_track['samplingrate_string'][0]))
																								{
																												if(isset($audio_track['samplingrate_string'][0]['text']))
																												{
																																$dessence_track[$dessence_track_counter]['sampling_rate']	=	$essence_track['sampling_rate']	=	$audio_track['samplingrate_string'][0]['text'];
																												}
																								}
																				}
																				/* Essence Track type Start */
																				if(isset($track['children']['format'])	&&	isset($track['children']['format'][0]))
																				{
																								$track_type	=	'';
																								if(	!	empty($track['children']['format'][0]['text']))
																								{
																												if((isset($track['attributes']['type'])	&&	$track['attributes']['type']	===	'Audio')	||	(isset($track['attributes']['type'])	&&	$track['attributes']['type']	===	'Video'))
																												{
																																if($track['children']['format'][0]['text']	==	'EIA-608'	||	$track['children']['format'][0]['text']	==	'EIA-708')
																																{
																																				$track_type	=	'Caption';
																																}
																																else	if($track['children']['format'][0]['text']	==	'TimeCode')
																																{
																																				$track_type	=	'Timecode';
																																}
																																else
																																{
																																				$track_type	=	$track['children']['format'][0]['text'];
																																}
																												}

																												if($track_type	!=	'')
																												{
																																$dessence_track[$dessence_track_counter]['track_type']	=	$track_type;
																																$essence_track_type	=	$this->essence->get_essence_track_by_type($track_type);
																																if(isset($essence_track_type)	&&	isset($essence_track_type->id))
																																{
																																				$essence_track['essence_track_types_id']	=	$essence_track_type->id;
																																}
																																else
																																{
																																				$essence_track['essence_track_types_id']	=	$this->essence->insert_essence_track_types(array('essence_track_type'	=>	$track_type));
																																}
																												}
																								}
																				}
																				/* Essence Track type End */



																				/* Essence Track Standard Start */
																				if(isset($track['children']['standard'])	&&	isset($track['children']['standard'][0])	&&	isset($track['children']['standard'][0]['text'])	&&	!	empty($track['children']['standard'][0]['text']))
																				{

																								$dessence_track[$dessence_track_counter]['standard']	=	$essence_track['standard']	=	$track['children']['standard'][0]['text'];
																				}
																				/* Essence Track Standard End */
																				/* Essence Track Data Rate Start */
																				if(isset($track['children']['bitrate_string'])	&&	isset($track['children']['bitrate_string'][0])	&&	isset($track['children']['bitrate_string'][0]['text'])	&&	!	empty($track['children']['bitrate_string'][0]['text']))
																				{
																								$bitrate	=	explode(' ',	$track['children']['bitrate_string'][0]['text']);
																								$dessence_track[$dessence_track_counter]['data_rate']	=	$essence_track['data_rate']	=	(isset($bitrate[0]))	?	$bitrate[0]	:	'';
																								$dessence_track[$dessence_track_counter]['data_rate_unit']	=	$data_rate_unit	=	(isset($bitrate[1]))	?	$bitrate[1]	:	'';
																								if($data_rate_unit	!=	'')
																								{
																												$data_rate	=	$this->instant->get_data_rate_units_by_unit($data_rate_unit);
																												if(	!	is_empty($data_rate))
																																$essence_track['data_rate_units_id']	=	$data_rate->id;
																												else
																																$essence_track['data_rate_units_id']	=	$this->instant->insert_data_rate_units(array('unit_of_measure'	=>	$data_rate_unit));
																								}
																				}
																				/* Essence Track Date Rate End */
																				/* Essence Track Bitdepth Start */
																				if(isset($track['children']['bitdepth'])	&&	isset($track['children']['bitdepth'][0])	&&	isset($track['children']['bitdepth'][0]['text'])	&&	!	empty($track['children']['bitdepth'][0]['text']))
																				{

																								$dessence_track[$dessence_track_counter]['bit_depth']	=	$essence_track['bit_depth']	=	$track['children']['bitdepth'][0]['text']	.	' bits';
																				}
																				/* Essence Track Bitdepth End */
																				/* Essence Track Duration Start */
																				if(isset($track['children']['duration_string3'])	&&	isset($track['children']['duration_string3'][0])	&&	isset($track['children']['duration_string3'][0]['text'])	&&	!	empty($track['children']['duration_string3'][0]['text']))
																				{

																								$dessence_track[$dessence_track_counter]['duration']	=	$essence_track['duration']	=	date('H:i:s',	strtotime($general_track['duration_string3'][0]['text']));
																				}
																				/* Essence Track Duration End */
																				/* Essence Track Language Start */
																				if(isset($track['children']['language_string3'])	&&	isset($track['children']['language_string3'][0])	&&	isset($track['children']['language_string3'][0]['text'])	&&	!	empty($track['children']['language_string3'][0]['text']))
																				{

																								$dessence_track[$dessence_track_counter]['language']	=	$essence_track['language']	=	$track['children']['language_string3'][0]['text'];
																				}
																				/* Essence Track Language End */


																				/* Insert Essence Track Start */
																				$essence_track['instantiations_id']	=	$db_instantiation_id;

																				$db_essence_track_id	=	$this->essence->insert_essence_tracks($essence_track);

																				/* Insert Essence Track End */

																				/* Essence Track Encoding Start */
																				$essence_track_encodeing	=	array();


																				if(isset($track['children']['codec_string'])	&&	isset($track['children']['codec_string'][0])	&&	isset($track['children']['codec_string'][0]['text'])	&&	!	empty($track['children']['codec_string'][0]['text']))
																				{
																								$dessence_track[$dessence_track_counter]['encoding']	=	$essence_track_encodeing['encoding']	=	$track['children']['codec_string'][0]['text'];
																				}
																				else	if(isset($track['children']['format'])	&&	isset($track['children']['format'][0])	&&	isset($track['children']['format'][0]['text'])	&&	!	empty($track['children']['format'][0]['text']))
																				{
																								$dessence_track[$dessence_track_counter]['encoding']	=	$essence_track_encodeing['encoding']	=	$track['children']['format'][0]['text'];
																				}

																				if(isset($track['children']['codec_url'])	&&	isset($track['children']['codec_url'][0])	&&	isset($track['children']['codec_url'][0]['text'])	&&	!	empty($track['children']['codec_url'][0]['text']))
																				{
																								$dessence_track[$dessence_track_counter]['encoding_ref']	=	$essence_track_encodeing['encoding_ref']	=	$track['children']['codec_url'][0]['text'];
																				}
																				else	if(isset($track['children']['format_url'])	&&	isset($track['children']['format_url'][0])	&&	isset($track['children']['format_url'][0]['text'])	&&	!	empty($track['children']['format_url'][0]['text']))
																				{
																								$dessence_track[$dessence_track_counter]['encoding_ref']	=	$essence_track_encodeing['encoding_ref']	=	$track['children']['format_url'][0]['text'];
																				}
																				if(isset($essence_track_encodeing['encoding']))
																				{
																								$essence_track_encodeing['essence_tracks_id']	=	$db_essence_track_id;
																								$dessence_track[$dessence_track_counter]['encoding_source']	=	$essence_track_encodeing['encoding_source']	=	'mediainfo';
																								$this->essence->insert_essence_track_encodings($essence_track_encodeing);
																				}
																				unset($essence_track_encodeing);
																				/* Essence Track Encoding End */
																				/* Essence Track Identifier Start */
																				$essence_track_identifier	=	array();
																				if(isset($track['children']['id'])	&&	isset($track['children']['id'][0])	&&	isset($track['children']['id'][0]['text'])	&&	!	empty($track['children']['id'][0]['text']))
																				{
																								$dessence_track[$dessence_track_counter]['identifier']	=	$essence_track_identifier['essence_track_identifiers']	=	$track['children']['id'][0]['text'];
																								$dessence_track[$dessence_track_counter]['identifier_source']	=	$essence_track_identifier['essence_track_identifier_source']	=	'mediainfo';
																				}
																				else	if(isset($track['children']['streamkindid'])	&&	isset($track['children']['streamkindid'][0])	&&	isset($track['children']['streamkindid'][0]['text'])	&&	!	empty($track['children']['streamkindid'][0]['text']))
																				{
																								$dessence_track[$dessence_track_counter]['identifier']	=	$essence_track_identifier['essence_track_identifiers']	=	$track['children']['streamkindid'][0]['text'];
																								$dessence_track[$dessence_track_counter]['identifier_source']	=	$essence_track_identifier['essence_track_identifier_source']	=	'mediainfo';
																				}
																				if(isset($essence_track_identifier['essence_track_identifiers']))
																				{
																								$essence_track_identifier['essence_tracks_id']	=	$db_essence_track_id;
																								$this->essence->insert_essence_track_identifiers($essence_track_identifier);
																				}
																				unset($essence_track_identifier);
																				/* Essence Track Identifier End */
																				if(isset($track['attributes']['type'])	&&	$track['attributes']['type']	===	'Video')
																				{
																								unset($essence_track);
																								$video_track	=	$track['children'];
																								/* Essence Track Frame Rate Start */
																								if(isset($video_track['framerate'])	&&	isset($video_track['framerate'][0]))
																								{
																												if(isset($video_track['framerate'][0]['text']))
																												{
																																$dessence_track[$dessence_track_counter]['frame_rate']	=	$essence_track['frame_rate']	=	$video_track['framerate'][0]['text'];
																												}
																								}
																								/* Essence Track Frame Rate End */
																								/* Essence Track Aspect Ratio Start */
																								if(isset($video_track['displayaspectratio_string'])	&&	isset($video_track['displayaspectratio_string'][0]))
																								{
																												if(isset($video_track['displayaspectratio_string'][0]['text']))
																												{
																																$dessence_track[$dessence_track_counter]['aspect_ratio']	=	$essence_track['aspect_ratio']	=	$video_track['displayaspectratio_string'][0]['text'];
																												}
																								}
																								/* Essence Track Aspect Ratio End */

																								/* Essence Track Frame Size Start */
																								$frame	=	array();
																								if(isset($video_track['width'])	&&	isset($video_track['width'][0]))
																								{
																												if(isset($video_track['width'][0]['text']))
																												{
																																$dessence_track[$dessence_track_counter]['width']	=	$frame['width']	=	$video_track['width'][0]['text'];
																												}
																								}
																								if(isset($video_track['height'])	&&	isset($video_track['height'][0]))
																								{
																												if(isset($video_track['height'][0]['text']))
																												{
																																$dessence_track[$dessence_track_counter]['height']	=	$frame['height']	=	$video_track['height'][0]['text'];
																												}
																								}
																								if(isset($frame['width'])	||	isset($frame['height']))
																								{
																												$track_frame_size	=	$this->essence->get_essence_track_frame_sizes_by_width_height(trim($frame['width']),	trim($frame['height']));
																												if($track_frame_size)
																												{
																																$essence_track['essence_track_frame_sizes_id']	=	$track_frame_size->id;
																												}
																												else
																												{
																																$essence_track['essence_track_frame_sizes_id']	=	$this->essence->insert_essence_track_frame_sizes($frame);
																												}
																								}
																								unset($frame);
																								/* Essence Track Frame Size End */
																								/* Update Essence Track Start */
																								$this->essence->update_essence_track($db_essence_track_id,	$essence_track);
																								/* Update Essence Track End */

																								/* Essence Track Annotation Start */
																								$essence_annotation	=	array();

																								if(isset($video_track['colorspace'])	&&	isset($video_track['colorspace'][0]))
																								{
																												if(isset($video_track['colorspace'][0]['text']))
																												{
																																$dessence_track[$dessence_track_counter]['annotation'][]	=	$essence_annotation[]	=	array('annotation'						=>	$video_track['colorspace'][0]['text'],	'annotation_type'	=>	'colorspace');
																												}
																								}
																								if(isset($video_track['chromasubsampling'])	&&	isset($video_track['chromasubsampling'][0]))
																								{
																												if(isset($video_track['chromasubsampling'][0]['text']))
																												{
																																$dessence_track[$dessence_track_counter]['annotation'][]	=	$essence_annotation[]	=	array('annotation'						=>	$video_track['chromasubsampling'][0]['text'],	'annotation_type'	=>	'subsampling');
																												}
																								}
																								if(count($essence_annotation)	>	0)
																								{
																												foreach($essence_annotation	as	$annotation)
																												{
																																$annotation['essence_tracks_id']	=	$db_essence_track_id;
																																$this->essence->insert_essence_track_annotations($annotation);
																												}
																								}
																								unset($essence_annotation);
																								/* Essence Track Annotation End */
																				}
																				$dessence_track_counter	++;
																}
												}
								}
								unset($instantiation);
								unset($essence_track);
								echo	'<br/><br/>Essence Tracks';
								debug($dessence_track,	FALSE);
								echo	'<br/>These values will be saved in the respective tables';
				}

				function	get_asset_id_for_media_import($guid)
				{
								$asset_guid	=	explode('.',	$guid);
								if(count($asset_guid)	>	0)
								{
												$asset_guid	=	$asset_guid[0];
												$make_db_name	=	explode('cpb-aacip-',	$asset_guid);

												if(count($make_db_name)	>	1)
												{

																$guid_db	=	'cpb-aacip/'	.	$make_db_name[1];

																$asset_id	=	$this->assets_model->get_asset_id_by_guid($guid_db);

																if($asset_id	&&	!	empty($asset_id))
																{
																				return	$assets_id->assets_id;
																}
												}
												return	FALSE;
								}
								return	FALSE;
				}

				function	myLog($s)
				{
								global	$argc;
								if($argc)
												$s.="\n";
								else
												$s.="<br>\n";
								echo	date('Y-m-d H:i:s')	.	' >> '	.	$s;
								flush();
				}

				function	checkProcessStatus($pid)
				{
								$proc_status	=	false;
								try
								{
												$result	=	shell_exec("/bin/ps $pid");
												if(count(preg_split("/\n/",	$result))	>	2)
												{
																$proc_status	=	TRUE;
												}
								}
								catch	(Exception	$e)
								{
												
								}
								return	$proc_status;
				}

				function	procCounter()
				{
								foreach($this->arrPIDs	as	$pid	=>	$cityKey)
								{
												if(	!	$this->checkProcessStatus($pid))
												{
																$t_pid	=	str_replace("\r",	"",	str_replace("\n",	"",	trim($pid)));
																unset($this->arrPIDs[$pid]);
												}
												else
												{
																
												}
								}
								return	count($this->arrPIDs);
				}

				function	runProcess($cmd,	$pidFilePath,	$outputfile	=	"/dev/null")
				{
								$cmd	=	escapeshellcmd($cmd);
								@exec(sprintf("%s >> %s 2>&1 & echo $! > %s",	$cmd,	$outputfile,	$pidFilePath));
				}

				function	is_valid_date($value)
				{
								$date	=	date_parse($value);
								if($date['error_count']	==	0	&&	$date['warning_count']	==	0)
								{
												return	date("Y-m-d",	strtotime($value));
								}
								return	FALSE;
				}

}