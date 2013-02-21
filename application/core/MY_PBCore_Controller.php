<?php

if(	!	defined('BASEPATH'))
{
				exit('No direct script access allowed');
}

class	MY_PBCore_Controller	extends	CI_Controller
{

				function	__construct()
				{
								parent::__construct();
								$this->load->model('cron_model');
								$this->load->model('assets_model');
								$this->load->model('instantiations_model',	'instant');
								$this->load->model('essence_track_model',	'essence');
								$this->load->model('station_model');
				}

				/**
					* Display the output.
					* @global type $argc
					* @param type $s 
					*/
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

				/**
					* Check the process status
					* 
					* @param type $pid
					* @return boolean 
					*/
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

				/**
					* Check the process count.
					* 
					* @return type 
					*/
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

				/**
					* Run a new process
					* 
					* @param type $cmd
					* @param type $pidFilePath
					* @param type $outputfile 
					*/
				function	runProcess($cmd,	$pidFilePath,	$outputfile	=	"/dev/null")
				{
								$cmd	=	escapeshellcmd($cmd);
								@exec(sprintf("%s >> %s 2>&1 & echo $! > %s",	$cmd,	$outputfile,	$pidFilePath));
				}

				/**
					* Check the date format
					* 
					* @param type $value
					* @return boolean 
					*/
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

?>