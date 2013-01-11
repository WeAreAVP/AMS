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

								$total_digitized	=	$this->instantiation->get_digitized_formats();
								$total_scheduled	=	$this->instantiation->get_scheduled_formats();
								
								foreach($total_digitized as $digitized){
												$data['digitized_format_name'][]=$digitized->format_name;
												$data['digitized_total'][]=(int)$digitized->total;
								}
								
								foreach($total_scheduled as $scheduled){
												$data['scheduled_format_name'][]=$scheduled->format_name;
												$data['scheduled_total'][]=(int)$scheduled->total;
								}
								
								$this->load->view('dashboard/index',	$data);
				}

}

// END Dashboard Controller

// End of file dashboard.php 
/* Location: ./application/controllers/dashboard.php */