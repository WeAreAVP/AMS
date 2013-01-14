<?php

/**
	* Dashboard Controller
	* 
	* PHP version 5
	* 
	* @category   AMS
	* @package    CI
	* @subpackage Controller
	* @author     Nouman Tayyab <nouman@geekschicago.com>
	* @license    CPB http://nouman.com
	* @version    GIT: <$Id>
	* @link       http://amsqa.avpreserve.com
	*/

/**
	* AMS Dashboard Class
	*
	* @category   Class
	* @package    CI
	* @subpackage Controller
	* @author     Nouman Tayyab <nouman@geekschicago.com>
	* @license    CPB http://nouman.com
	* @link       http://amsqa.avpreserve.com
	*/
class	Dashboard	extends	MY_Controller
{

				/**
					* Constructor.
					* 
					* Load the layout for the dashboard.
					*  
					*/
				function	__construct()
				{
								parent::__construct();
								$this->layout	=	'main_layout.php';
								$this->load->model('instantiations_model',	'instantiation');
								if($this->is_station_user)
								{
												redirect('records/index');
								}
				}

				/**
					* Dashboard Functionality
					* 
					* @return view dashboard/index
					*/
				public	function	index()
				{
								/* Start Graph Get Digitized Formats  */
								$total_digitized	=	$this->instantiation->get_digitized_formats();
									$data['digitized_format_name']=NULL;
									$data['digitized_total']=NULL;
								foreach($total_digitized	as	$digitized)
								{
												$data['digitized_format_name'][]	=	$digitized->format_name;
												$data['digitized_total'][]	=	(int)	$digitized->total;
								}
								/* End Graph Get Digitized Formats  */
								/* Start Graph Get Scheduled Formats  */
								$total_scheduled	=	$this->instantiation->get_scheduled_formats();
								$data['scheduled_format_name']=NULL;
									$data['scheduled_total']=NULL;
								foreach($total_scheduled	as	$scheduled)
								{
												$data['scheduled_format_name'][]	=	$scheduled->format_name;
												$data['scheduled_total'][]	=	(int)	$scheduled->total;
								}
								/* End Graph Get Scheduled Formats  */
								/* Start Meterial Goal  */
								$data['material_goal']	=	$this->instantiation->get_material_goal();
								/* End Meterial Goal  */
								/* Start Hours at crawford  */
								foreach($this->config->item('messages_type')	as	$index	=>	$msg_type)
								{
												if($msg_type	===	'Materials Received Digitization Vendor')
												{
																$data['msg_type']	=	$index;
												}
								}

								$hours_at_craword	=	$this->station_model->get_hours_at_crawford($data['msg_type']);

								$data['at_crawford']	=	0;
								foreach($hours_at_craword	as	$hours)
								{
												$data['at_crawford']	=	$data['at_crawford']	+	$hours->total;
								}
								/* End Hours at crawford  */
								/* Start goal hours  */
								$digitized_hours	=	$this->instantiation->get_digitized_hours();
								$data['total_hours']=$this->abbr_number($data['material_goal']->total);
								$data['percentage_hours']=		round(($digitized_hours->total*100)/$data['material_goal']->total);
								
								/* End goal hours  */


								$this->load->view('dashboard/index',	$data);
				}

				function	abbr_number($size)
				{
								$size	=	preg_replace('/[^0-9]/',	'',	$size);
								$sizes	=	array("",	"K",	"M");
								if($size	==	0)
								{
												return('n/a');
								}
								else
								{
												return	(round($size	/	pow(1000,	($i	=	floor(log($size,	1000)))),	0)	.	$sizes[$i]);
								}
				}

}

// END Dashboard Controller

// End of file dashboard.php 
/* Location: ./application/controllers/dashboard.php */