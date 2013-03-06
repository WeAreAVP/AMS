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
				}

				function create(){
								
								$project_name='Refine_'.time();
								echo 'hee';exit;
								$file_path='/var/www/html/uploads/Workbook7.csv';
								$this->googlerefine->create_project($project_name,$file_path);
				}

// Location: ./controllers/googledoc.php
}

// END Google Doc Controller

// End of file googledoc.php
// Location: ./application/controllers/googledoc.php
