<?php
/**
 * AMS Archive Management System
 * 
 * 
 * PHP version 5
 * 
 * @category   AMS
 * @package    CI
 * @subpackage Migration
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @copyright  Copyright (c) WGBH (http://www.wgbh.org/). All Rights Reserved.
 * @license    http://www.gnu.org/licenses/gpl.txt GPLv3
 * @version    GIT: <$Id>
 * @link       https://github.com/avpreserve/AMS
 */

/**
 * Migration_Add_Db_Changes Class
 *
 * @category   Class
 * @package    CI
 * @subpackage Migration
 * @author     Nouman Tayyab <nouman@avpreserve.com>
 * @copyright  Copyright (c) WGBH (http://www.wgbh.org/). All Rights Reserved.
 * @license    http://www.gnu.org/licenses/gpl.txt GPLv3
 * @link       https://ams.americanarchive.org
 */
class Migration_Add_Db_Changes extends CI_Migration
{

    public function up()
    {
        $this->dbforge->add_field('id');
        $this->dbforge->add_field(array(
            'folder_path' => array(
                'type' => 'VARCHAR',
                'constraint' => '500',
                'null' => FALSE,
            ),
            'created_at' => array(
                'type' => 'DATETIME',
                'null' => FALSE,
            ),
            'data_type' => array(
                'type' => 'VARCHAR',
                'constraint' => '200',
                'null' => TRUE,
            ),
        ));
        $this->dbforge->create_table('data_folders');
        $fields = array(
            'event_note TEXT DEFAULT NULL',
        );

        $this->dbforge->add_column('events', $fields);

        $this->dbforge->add_field('id');
        $this->dbforge->add_field(array(
            'assets_id' => array(
                'type' => 'INT',
                'constraint' => '11',
                'null' => FALSE,
            ),
            'extension_element' => array(
                'type' => 'VARCHAR',
                'constraint' => '45',
                'null' => TRUE,
            ),
            'extension_value' => array(
                'type' => 'VARCHAR',
                'constraint' => '45',
                'null' => FALSE,
            ),
            'extension_authority' => array(
                'type' => 'VARCHAR',
                'constraint' => '45',
                'null' => TRUE,
            ),
        ));

        $this->dbforge->create_table('extensions');
    }

    public function down()
    {
        $this->dbforge->drop_table('data_folders');
        $this->dbforge->drop_column('events', 'event_note');
        $this->dbforge->drop_table('extensions');
    }

}

// END Migration_Add_Db_Changes

/* End of file 001_add_db_changes.php */
/* Location: ./application/migration/001_add_db_changes.php */