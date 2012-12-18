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