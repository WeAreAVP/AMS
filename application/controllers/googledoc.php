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
	* @license    ams http://ams.appreserve.com
	* @link       http://ams.appreserve.com
	*/
class	Googledoc	extends	MY_Controller
{

				/**
					* Constructor.
					* 
					* Load the Models,Library
					* 
					* @return 
					*/
				function	__construct	()
				{
								parent::__construct	();
								$this->layout	=	'';
								$this->load->model	('instantiations_model',	'instantiation');
				}

				/**
					* Mapping Google Spreed Sheet
					* 
					* @return view
					*/
				function	parse_american_archive	()
				{
								$this->load->library	('google_spreadsheet',	array	('user'									=>	'ali@geekschicago.com',	'pass'									=>	'purelogics12',	'ss'											=>	'test_archive',	'ws'											=>	'Template'));
								$spreed_sheets	=	$this->google_spreadsheet->getAllSpreedSheetsDetails	('american_archive spreadsheet template v1 - samples');
								if	($spreed_sheets)
								{
												foreach	($spreed_sheets	as	$spreed_sheet)
												{
																$work_sheets[]	=	$this->google_spreadsheet->getAllWorksSheetsDetails	($spreed_sheet['spreedSheetId']);
												}
								}
								foreach	($work_sheets	as	$work_sheet)
								{
												$data	=	$this->google_spreadsheet->displayWorksheetData	($work_sheet[0]['spreedSheetId'],	$work_sheet[0]['workSheetId']);
												$this->_store_event_data	($data);
												break;
								}
				}

				/**
				 * Map Data of Work sheet in to database
				 * 
					* @param array $data contain event data of spreed sheet
					* 
					* @return helper
					*/
				private	function	_store_event_data	($data)
				{
								if	(isset	($data)	&&	! empty	($data))
								{
												foreach	($data	as	$event_row)
												{
																if	(isset	($event_row[2])	&&	! empty	($event_row[2])	&&	isset	($event_row[5])	&&	! empty	($event_row[5]))
																{
																				$instantiation	=	$this->instantiation->get_instantiation_by_guid_physical_format	($event_row[2],	$event_row[5]);
																				if	($instantiation)
																				{
																								echo	'<pre>';
																								$instantiation_data	=	array	();
																								if	(isset	($event_row[32])	&&	! empty	($event_row[32]))
																								{
																												$instantiation_data['channel_configuration']	=	$event_row[32];
																								}
																								if	(isset	($event_row[33])	&&	! empty	($event_row[33]))
																								{
																												$instantiation_data['alternative_modes']	=	$event_row[33];
																								}
																								if	(isset	($event_row[42])	&&	! empty	($event_row[42]))
																								{
																												if	(isset	($instantiation->generation)	&&	! empty	($instantiation->generation))
																												{
																																if	($instantiation->generation	===	'Preservation Master'	OR	$instantiation->generation	===	'Mezzanine'	OR	$instantiation->generation	===	'Proxy')
																																{
																																				$instantiation_data['location']	=	$event_row[42];
																																}
																												}
																								}
																								echo	'<strong>Instantiation Table Changes According to american_archive spreadsheet template v1 Description <br/>Instantiation Id :'	.	$instantiation->id	.	'</strong><br>';
																								print_r	($instantiation_data);
																								$this->instantiation->update_instantiations	($instantiation->id,	$instantiation_data);
																								echo	'<br> <strong>Events Table changes</strong> <br/>';
																								$this->_store_event_type_inspection	($event_row,	$instantiation->id);
																								$this->_store_event_type_baked	($event_row,	$instantiation->id);
																								$this->_store_event_type_cleaned	($event_row,	$instantiation->id);
																								$this->_store_event_type_migration	($event_row,	$instantiation->id);
																								return ;
																						
																				}
																}
												}
								}
				}

				/**
				 * Store or Udpate inspection event type
				 * 
				 * @param Array   $event_row        row of spreed sheet
					* @param Integer $instantiation_id use to match event instantiation id
				 * 
					*@return helper
					*/
				private	function	_store_event_type_inspection	($event_row,	$instantiation_id)
				{
								if	((isset	($event_row[8])	&&	! empty	($event_row[8]))	OR	(isset	($event_row[9])	&&	! empty	($event_row[9])))
								{
												$event_data	=	array	();
												$event_type	=	'inspection';
												$event_data['instantiations_id']	=	$instantiation_id;
												$event_data['event_types_id']	=	$this->_get_event_type	($event_type);
												if	(isset	($event_row[8])	&&	! empty	($event_row[8]))
												{
																$event_data['event_date']	=	date	('Y-m-d',	strtotime	(str_replace	("'",	'',	trim	($event_row[8]))));
												}
												if	(isset	($event_row[9])	&&	! empty	($event_row[9]))
												{
																$event_data['event_note']	=	$event_row[9];
												}
												$this->_insert_or_update	($instantiation_id,	$event_data['event_types_id'],	$event_data);
								}
				}

				/**
				 * Store or Udpate baked event type
				 * 
				 * @param Array   $event_row        row of spreed sheet
					* @param Integer $instantiation_id use to match event instantiation id
					* 
					* @return helper
					*/
				private	function	_store_event_type_baked	($event_row,	$instantiation_id)
				{
								if	((isset	($event_row[12])	&&	! empty	($event_row[12]))	OR	(isset	($event_row[13])	&&	! empty	($event_row[13])))
								{
												$event_type	=	'baked';
												$event_data['instantiations_id']	=	$instantiation_id;
												$event_data['event_types_id']	=	$this->_get_event_type	($event_type);
												if	(isset	($event_row[12])	&&	! empty	($event_row[12]))
												{
																$event_data['event_date']	=	date	('Y-m-d',	strtotime	(str_replace	("'",	'',	trim	($event_row[12]))));
												}
												if	(isset	($event_row[13])	&&	! empty	($event_row[13]))
												{
																$event_data['event_note']	=	$event_row[13];
												}
												$this->_insert_or_update	($instantiation_id,	$event_data['event_types_id'],	$event_data);
								}
				}

				/**
				 * Store or Udpate cleaned event type
				 * 
				 * @param Array   $event_row        row of spreed sheet
					* @param Integer $instantiation_id use to match event instantiation id
					* 
					* @return helper
					*/
				private	function	_store_event_type_cleaned	($event_row,	$instantiation_id)
				{
								if	((isset	($event_row[14])	&&	! empty	($event_row[14]))	OR	(isset	($event_row[16])	&&	! empty	($event_row[16])))
								{
												$event_type	=	'cleaned';
												$event_data['instantiations_id']	=	$instantiation_id;
												$event_data['event_types_id']	=	$this->_get_event_type	($event_type);
												if	(isset	($event_row[14])	&&	! empty	($event_row[14]))
												{
																$event_data['event_date']	=	date	('Y-m-d',	strtotime	(str_replace	("'",	'',	trim	($event_row[14]))));
												}
												if	(isset	($event_row[16])	&&	! empty	($event_row[16]))
												{
																$event_data['event_note']	=	$event_row[16];
												}
												$this->_insert_or_update	($instantiation_id,	$event_data['event_types_id'],	$event_data);
								}
				}

				/**
				 * Store or Udpate migration event type
				 * 
				 * @param Array   $event_row        row of spreed sheet
					* @param Integer $instantiation_id use to match event instantiation id
					* 
					* @return helper
					*/
				private	function	_store_event_type_migration	($event_row,	$instantiation_id)
				{
								if	((isset	($event_row[17])	&&	! empty	($event_row[17]))	OR	(isset	($event_row[34])	&&	! empty	($event_row[34]))	OR	(isset	($event_row[35])	&&	! empty	($event_row[35])))
								{
												$event_type	=	'migration';
												$event_data['instantiations_id']	=	$instantiation_id;
												$event_data['event_types_id']	=	$this->_get_event_type	($event_type);
												if	(isset	($event_row[17])	&&	! empty	($event_row[17]))
												{
																$event_data['event_date']	=	date	('Y-m-d',	strtotime	(str_replace	("'",	'',	trim	($event_row[17]))));
												}
												if	(isset	($event_row[34])	&&	! empty	($event_row[34]))
												{
																$event_data['event_outcome']	=	(($event_row[34]	===	'N')	?	(0)	:	(1));
												}
												if	(isset	($event_row[35])	&&	! empty	($event_row[35]))
												{
																$event_data['event_note']	=	$event_row[35];
												}
												$this->_insert_or_update	($instantiation_id,	$event_data['event_types_id'],	$event_data);
								}
				}

				/**
					* Insert or update event 
					* 
					* @param Integer $instantiation_id use to match instantiation
					* @param Integer $event_types_id   use to event_types_id
					* @param Array   $event_data       use to insert event type
					* 
					* @return helper
					*/
				private	function	_insert_or_update	($instantiation_id,	$event_types_id,	$event_data)
				{
								$is_exists	=	$this->instantiation->is_event_exists	($instantiation_id,	$event_types_id);
								if	($is_exists)
								{
												echo	'<strong><br/>Event migration already Exists against Instantiation Id: '	.	$instantiation_id	.	'</strong><br/>';
												print_r	($event_data);
												$this->instantiation->update_event	($is_exists->id,	$event_data);
								}
								else
								{
												echo	'<strong><br/>New migration event against Instantiation Id: '	.	$instantiation_id	.	'</strong><br/>';
												print_r	($event_data);
												$this->instantiation->insert_event	($event_data);
								}
				}

				/**
					* Get event type Id on event_type base else store event  
					* 
					* @param string $event_type use to check/store event types
					* 
					* @return integer event_types_id
					*/
				private	function	_get_event_type	($event_type)
				{
								$event_type_data	=	$this->instantiation->get_id_by_event_type	($event_type);
								if	($event_type_data)
								{
												$event_types_id	=	$event_type_data->id;
								}
								else
								{
												$event_types_id	=	$this->instantiation->insert_event_types	(array	(
																'event_type'	=>	$event_type
																				));
								}
								return	$event_types_id;
				}

}

// END Google Doc Controller

// End of file googledoc.php
/* Location: ./application/controllers/googledoc.php */
