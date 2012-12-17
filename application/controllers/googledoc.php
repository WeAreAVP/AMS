<?php

/**
 * Google Doc controller.
 *
 * @package    AMS
 * @subpackage 	Google Documents Controller
 * @category	Controllers
 * @author		Ali Raza <ali@geekschicago.com>
 */
class Googledoc extends MY_Controller
{

    /**
     * Constructor.
     * 
     * Load the layout. Sphinx and tracking model
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
		
		$this->load->library('google_spreadsheet',array("user"=>'ali@geekschicago.com',"pass"=>'purelogics12','ss'=>'test_archive','ws'=>'Template'));
		echo "<pre>";
		//print_r($this->google_spreadsheet->getRows());
		$spreedSheets=$this->google_spreadsheet->getAllSpreedSheetsDetails();
		if($spreedSheets)
		{
			foreach($spreedSheets as $key=>$spreedSheet)
			{
				$worksheets[]=$this->google_spreadsheet->getAllWorksSheetsDetails($spreedSheet['spreedSheetId']);
			}
		}
		foreach($worksheets as $worksheet)
		{
			$data=$this->google_spreadsheet->displayWorksheetData($worksheet[0]['spreedSheetId'],$worksheet[0]['workSheetId']);
			break;
		}
		foreach($data as $row)
		{
			$i=0;
			$event_data=array();
			
			if((isset($row[8]) && !empty($row[8])) || (isset($row[9]) && !empty($row[9])))
			{
				$event_type='inspection';
				$event_type_data=$this->instantiation->get_id_by_event_type($event_type);
				if($event_type_data)
				{
					$event_data[$i]['event_types_id']=$event_type_data->id;
				}
				else
				{
					$event_data[$i]['event_types_id']=$this->instantiation->insert_event_types(array("event_type"=>$event_type));
				}
				if(isset($row[8]) && !empty($row[8]))
				{
					$event_data[$i]['event_date']=$row[8];
				}
				if(isset($row[9]) && !empty($row[9]))
				{
					$event_data[$i]['event_note']=$row[9];
				}
				$i++;
			}
			if((isset($row[12]) && !empty($row[12])) || (isset($row[13]) && !empty($row[13])))
			{
				$event_type='baked';
				$event_type_data=$this->instantiation->get_id_by_event_type($event_type);
				if($event_type_data)
				{
					$event_data[$i]['event_types_id']=$event_type_data->id;
				}
				else
				{
					$event_data[$i]['event_types_id']=$this->instantiation->insert_event_types(array("event_type"=>$event_type));
				}
				if(isset($row[12]) && !empty($row[12]))
				{
					$event_data[$i]['event_date']=$row[12];
				}
				if(isset($row[13]) && !empty($row[13]))
				{
					$event_data[$i]['event_note']=$row[13];
				}
				$i++;
			}
			if((isset($row[14]) && !empty($row[14])) || (isset($row[16]) && !empty($row[16])))
			{
				$event_type='cleaned';
				$event_type_data=$this->instantiation->get_id_by_event_type($event_type);
				if($event_type_data)
				{
					$event_data[$i]['event_types_id']=$event_type_data->id;
				}
				else
				{
					$event_data[$i]['event_types_id']=$this->instantiation->insert_event_types(array("event_type"=>$event_type));
				}
				if(isset($row[14]) && !empty($row[14]))
				{
					$event_data[$i]['event_date']=$row[14];
				}
				if(isset($row[16]) && !empty($row[16]))
				{
					$event_data[$i]['event_note']=$row[16];
				}
				$i++;
			}
			if((isset($row[17]) && !empty($row[17])) || (isset($row[34]) && !empty($row[34])) || (isset($row[33]) && !empty($row[33])))
			{
				$event_type='migration';
				$event_type_data=$this->instantiation->get_id_by_event_type($event_type);
				if($event_type_data)
				{
					$event_data[$i]['event_types_id']=$event_type_data->id;
				}
				else
				{
					$event_data[$i]['event_types_id']=$this->instantiation->insert_event_types(array("event_type"=>$event_type));
				}
				if(isset($row[17]) && !empty($row[17]))
				{
					$event_data[$i]['event_date']=$row[17];
				}
				if(isset($row[34]) && !empty($row[34]))
				{
					$event_data[$i]['event_outcome']=(($row[34]=='N')?(0):(1));
				}
				if(isset($row[35]) && !empty($row[35]))
				{
					$event_data[$i]['event_note']=$row[35];
				}
				$i++;
			}
			print_r($event_data);
			exit();
			
		}
    }

}

// END Stations Controller

/* End of file stations.php */
/* Location: ./application/controllers/stations.php */