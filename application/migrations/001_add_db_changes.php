<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * AMS Field Migrations
 * 
 *
 * @package		AMS
 * @subpackage	Migrations
 * @category	Migrations
 * @author		Nouman Tayyab <nouman@geekschicago.com>
 */
class Migration_Add_Db_Changes extends CI_Migration {

  public function up()
  {
    $fields = array(
      'my_name VARCHAR(50) DEFAULT NULL',
      
    );
    $this->dbforge->add_column('user_profile', $fields);
  }

  public function down()
  {
    $this->dbforge->drop_column('user_profile', 'my_name');
  }

} 

// END Migration_Add_Db_Changes

/* End of file 001_add_db_changes.php */
/* Location: ./application/migration/001_add_db_changes.php */