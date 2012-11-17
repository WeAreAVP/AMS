<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * AMS Migrate Controller
 * 
 * This controller migrate the changes into mysql database.
 *
 * @package		AMS
 * @subpackage	Migrate Controller
 * @category	Controllers
 * @author		Nouman Tayyab <nouman@geekschicago.com>
 */
class Migrate extends CI_Controller
{

    /**
     * Constructor
     * 
     * Load the Migration Library.
     * 
     */
    function __construct()
    {
        parent::__construct();
        $this->load->library('migration');
    }

    /**
     * Migrate changes to mysql database.
     *  
     */
    function index()
    {
        if (!$this->migration->current())
        {
            show_error($this->migration->error_string());
        } else
        {
            echo 'Migrations changes are successfully applied.<br/>';
        }
    }

}

// END Migrate Controller

/* End of file migrate.php */
/* Location: ./application/controllers/migrate.php */