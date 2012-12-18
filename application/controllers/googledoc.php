<?php
/**
 * Google Doc controller.
 *
 * @package    AMS
 * @subpackage     Google Documents Controller
 * @category    Controllers
 * @author        Ali Raza <ali@geekschicago.com>
 */
class Googledoc extends MY_Controller
{
    /**
     * Constructor.
     * 
     * Load the Models,Library
     *  
     */
    function __construct()
    {
        parent::__construct();
        $this->layout = 'main_layout.php';
        $this->load->model('instantiations_model', 'instantiation');
    }
    function parse_american_archive()
    {
        /* Load the Zend Gdata classes. */
        $this->load->library('google_spreadsheet', array(
            "user" => 'ali@geekschicago.com',
            "pass" => 'purelogics12',
            'ss' => 'test_archive',
            'ws' => 'Template'
        ));
        $spreedSheets = $this->google_spreadsheet->getAllSpreedSheetsDetails('american_archive spreadsheet template v1 - samples');
        if ($spreedSheets)
        {
            foreach ($spreedSheets as $key => $spreedSheet)
            {
                $worksheets[] = $this->google_spreadsheet->getAllWorksSheetsDetails($spreedSheet['spreedSheetId']);
            }
        }
        foreach ($worksheets as $worksheet)
        {
            $data = $this->google_spreadsheet->displayWorksheetData($worksheet[0]['spreedSheetId'], $worksheet[0]['workSheetId']);
            $this->_store_event_data($data);
            break;
        }
    }
    private function _store_event_data($data)
    {
        if (isset($data) && !empty($data))
        {
            foreach ($data as $row)
            {
                if (isset($row[2]) && !empty($row[2]) && isset($row[5]) && !empty($row[5]))
                {
                    $instantiation = $this->instantiation->get_instantiation_by_guid_physical_format($row[2], $row[5]);
                    if ($instantiation)
                    {
                        echo "<pre>";
                        $instantiation_data = array();
                        if (isset($row[32]) && !empty($row[32]))
                        {
                            $instantiation_data['channel_configuration'] = $row[32];
                        }
                        if (isset($row[33]) && !empty($row[33]))
                        {
                            $instantiation_data['alternative_modes'] = $row[33];
                        }
                        if (isset($row[42]) && !empty($row[42]))
                        {
                            if (isset($instantiation->generation) && !empty($instantiation->generation))
                            {
                                if ($instantiation->generation == 'Preservation Master' || $instantiation->generation == 'Mezzanine' || $instantiation->generation == 'Proxy')
                                {
                                    $instantiation_data['location'] = $row[42];
                                }
                            }
                        }
                        echo "<strong>Instantiation Table Changes According to american_archive spreadsheet template v1 Description <br/>Instantiation Id :" . $instantiation->id . "</strong><br>";
                        print_r($instantiation_data);
                        $this->instantiation->update_instantiations($instantiation->id, $instantiation_data);
                        echo "<br> <strong>Events Table changes</strong> <br/>";
                        $this->_store_event_type_inspection($row, $instantiation->id);
                        $this->_store_event_type_baked($row, $instantiation->id);
                        $this->_store_event_type_cleaned($row, $instantiation->id);
                        $this->_store_event_type_migration($row, $instantiation->id);
                        exit();
                    }
                }
            }
        }
    }
    private function _store_event_type_inspection($row, $instantiation_id)
    {
        if ((isset($row[8]) && !empty($row[8])) || (isset($row[9]) && !empty($row[9])))
        {
            $event_data                      = array();
            $event_type                      = 'inspection';
            $event_data['instantiations_id'] = $instantiation_id;
            $event_type_data                 = $this->instantiation->get_id_by_event_type($event_type);
            if ($event_type_data)
            {
                $event_data['event_types_id'] = $event_type_data->id;
            }
            else
            {
                $event_data['event_types_id'] = $this->instantiation->insert_event_types(array(
                    "event_type" => $event_type
                ));
            }
            if (isset($row[8]) && !empty($row[8]))
            {
                $event_data['event_date'] = date("Y-m-d", strtotime(str_replace("'", '', trim($row[8]))));
            }
            if (isset($row[9]) && !empty($row[9]))
            {
                $event_data['event_note'] = $row[9];
            }
            $is_exists = $this->instantiation->is_event_exists($instantiation_id, $event_data['event_types_id']);
            if ($is_exists)
            {
                echo "<strong><br/>Event inspection already Exists against Instantiation Id: " . $instantiation_id . "</strong><br/>";
                print_r($event_data);
                $this->instantiation->update_event($is_exists->id, $event_data);
            }
            else
            {
                echo "<strong><br/>New inspection event against Instantiation Id: " . $instantiation_id . "</strong><br/>";
                print_r($event_data);
                $this->instantiation->insert_event($event_data);
            }
        }
    }
    private function _store_event_type_baked($row, $instantiation_id)
    {
        if ((isset($row[12]) && !empty($row[12])) || (isset($row[13]) && !empty($row[13])))
        {
            $event_type                      = 'baked';
            $event_data['instantiations_id'] = $instantiation_id;
            $event_type_data                 = $this->instantiation->get_id_by_event_type($event_type);
            if ($event_type_data)
            {
                $event_data['event_types_id'] = $event_type_data->id;
            }
            else
            {
                $event_data['event_types_id'] = $this->instantiation->insert_event_types(array(
                    "event_type" => $event_type
                ));
            }
            if (isset($row[12]) && !empty($row[12]))
            {
                $event_data['event_date'] = date("Y-m-d", strtotime(str_replace("'", '', trim($row[12]))));
            }
            if (isset($row[13]) && !empty($row[13]))
            {
                $event_data['event_note'] = $row[13];
            }
            $is_exists = $this->instantiation->is_event_exists($instantiation_id, $event_data['event_types_id']);
            if ($is_exists)
            {
                echo "<strong><br/>Event baked already Exists against Instantiation Id: " . $instantiation_id . "</strong><br/>";
                print_r($event_data);
                $this->instantiation->update_event($is_exists->id, $event_data);
            }
            else
            {
                echo "<strong><br/>New baked event against Instantiation Id: " . $instantiation_id . "</strong><br/>";
                print_r($event_data);
                $this->instantiation->insert_event($event_data);
            }
        }
    }
    private function _store_event_type_cleaned($row, $instantiation_id)
    {
        if ((isset($row[14]) && !empty($row[14])) || (isset($row[16]) && !empty($row[16])))
        {
            $event_type                      = 'cleaned';
            $event_data['instantiations_id'] = $instantiation_id;
            $event_type_data                 = $this->instantiation->get_id_by_event_type($event_type);
            if ($event_type_data)
            {
                $event_data['event_types_id'] = $event_type_data->id;
            }
            else
            {
                $event_data['event_types_id'] = $this->instantiation->insert_event_types(array(
                    "event_type" => $event_type
                ));
            }
            if (isset($row[14]) && !empty($row[14]))
            {
                $event_data['event_date'] = date("Y-m-d", strtotime(str_replace("'", '', trim($row[14]))));
            }
            if (isset($row[16]) && !empty($row[16]))
            {
                $event_data['event_note'] = $row[16];
            }
            $is_exists = $this->instantiation->is_event_exists($instantiation_id, $event_data['event_types_id']);
            if ($is_exists)
            {
                echo "<strong><br/>Event cleaned already Exists against Instantiation Id: " . $instantiation_id . "</strong><br/>";
                print_r($event_data);
                $this->instantiation->update_event($is_exists->id, $event_data);
            }
            else
            {
                echo "<strong><br/>New cleaned event against Instantiation Id: " . $instantiation_id . "</strong><br/>";
                print_r($event_data);
                $this->instantiation->insert_event($event_data);
            }
        }
    }
    private function _store_event_type_migration($row, $instantiation_id)
    {
        if ((isset($row[17]) && !empty($row[17])) || (isset($row[34]) && !empty($row[34])) || (isset($row[35]) && !empty($row[35])))
        {
            $event_type                      = 'migration';
            $event_data['instantiations_id'] = $instantiation_id;
            $event_type_data                 = $this->instantiation->get_id_by_event_type($event_type);
            if ($event_type_data)
            {
                $event_data['event_types_id'] = $event_type_data->id;
            }
            else
            {
                $event_data['event_types_id'] = $this->instantiation->insert_event_types(array(
                    "event_type" => $event_type
                ));
            }
            if (isset($row[17]) && !empty($row[17]))
            {
                $event_data['event_date'] = date("Y-m-d", strtotime(str_replace("'", '', trim($row[17]))));
            }
            if (isset($row[34]) && !empty($row[34]))
            {
                $event_data['event_outcome'] = (($row[34] == 'N') ? (0) : (1));
            }
            if (isset($row[35]) && !empty($row[35]))
            {
                $event_data['event_note'] = $row[35];
            }
            $is_exists = $this->instantiation->is_event_exists($instantiation_id, $event_data['event_types_id']);
            if ($is_exists)
            {
                echo "<strong><br/>Event migration already Exists against Instantiation Id: " . $instantiation_id . "</strong><br/>";
                print_r($event_data);
                $this->instantiation->update_event($is_exists->id, $event_data);
            }
            else
            {
                echo "<strong><br/>New migration event against Instantiation Id: " . $instantiation_id . "</strong><br/>";
                print_r($event_data);
                $this->instantiation->insert_event($event_data);
            }
        }
    }
}
// END Google Doc Controller

/* End of file googledoc.php */