<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * AMS Migrations
 * 
 *
 * @package		AMS
 * @subpackage	Migrations
 * @category	Migrations
 * @author		Nouman Tayyab <nouman@geekschicago.com>
 */
class Migration_Add_Db_Changes extends CI_Migration
{

    public function up()
    {

        $this->dbforge->add_field('id');
        $this->dbforge->add_field(array(
            'date' => array(
                'type' => 'DATE',
                'null' => FALSE,
            ),
            'location' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => FALSE,
            ),
            'description' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
        ));
        $this->dbforge->create_table('sample_table');
    }

    public function down()
    {

        $this->dbforge->drop_table('sample_table');
    }

}

// END Migration_Add_Db_Changes

/* End of file 002_add_db_changes.php */
/* Location: ./application/migration/002_add_db_changes.php */