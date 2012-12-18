<?php

/**
	* Dashboard Controller
 * @category   AMS
	* @package    Controller
	* @subpackage Dashboard
 * @author     Nouman Tayyab <nouman@geekschicago.com>
	* @license    CPB http://nouman.com
	* @link       http://amsqa.avpreserve.com
	*/

/**
	* AMS Dashboard Class
	*
	* @category   Controller
	* @package    Class
	* @subpackage Dashboard
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
				/*
				 * This is a "Block Comment."  The format is the same as
     * Docblock Comments except there is only one asterisk at the
     * top.  phpDocumentor doesn't parse these.
     */
}

// END Dashboard Controller

// End of file dashboard.php 
/* Location: ./application/controllers/dashboard.php */