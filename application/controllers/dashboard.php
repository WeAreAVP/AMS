<?php

/**
	* Dashboard Controller
	*/

/**
	* AMS Dashboard Class
	*
	* @category   Controller
	* @package    AMS
	* @subpackage Dashboard
	* @author     Nouman Tayyab <nouman@geekschicago.com>
	* @license    CPB http://nouman.com
	* @link       http://amsqa.avpreserve.comÏ
	*/
class	Dashboard	extends	MY_Controller
{

				/**
					* Constructor.
					* 
					* Load the layout for the dashboard.
					*  
					*/
				function	__construct	()
				{
								parent::__construct	();
								$this->layout	=	'main_layout.php';
				}

				/**
					* Dashboard Functionality
					* 
					* @return view dashboard/index
					*  
					*/
				public	function	index	()
				{
								$data	=	NULL;
								$this->load->view	('dashboard/index',	$data);
				}
				
}

// END Dashboard Controller

// End of file dashboard.php 
/* Location: ./application/controllers/dashboard.php */