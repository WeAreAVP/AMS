<?php

/**
 * stations controller.
 *
 * @package    AMS
 * @subpackage stations
 * @author     Nouman Tayyab
 */
class Stations extends MY_Controller {

  /**
   * constructor. Load layout,Model,Library and helpers
   * 
   */
  function __construct() {
    parent::__construct();
    error_reporting(E_ALL);
    error_reporting(1);
    $this->layout = 'main_layout.php';
    $this->load->model('station_model');
    $this->load->model('sphinx_model', 'sphinx');
    $this->load->model('dx_auth/users', 'users');
    $this->load->model('tracking_model', 'tracking');
  }

  /**
   * List all the stations and also filters stations
   * 
   * It receives 3 post parameters are received with ajax call
   * 
   * @param string $search_keyword
   * @param boolean $certified
   * @param boolean $agreed
   *  
   */
  public function index() {
    $param = array('search_kewords' => '', 'certified' => '', 'agreed' => '');
    $val = $this->form_validation;
    $val->set_rules('search_keyword', 'Search Keyword', 'trim|xss_clean');
    $val->set_rules('certified', 'Certified', 'trim|xss_clean');
    $val->set_rules('agreed', 'Agreed', 'trim|xss_clean');
    $val->set_rules('start_date_range', 'Start Date', 'trim|xss_clean');
    $val->set_rules('end_date_range', 'End Date', 'trim|xss_clean');
    if ($this->input->post()) {
      $param['certified'] = $this->input->post('certified');
      $param['agreed'] = $this->input->post('agreed');
//            $param['start_date'] = $this->input->post('start_date');
//            $param['end_date'] = $this->input->post('end_date');

      $param['search_kewords'] = str_replace(",", " & ", trim($this->input->post('search_words')));
      $records = $this->sphinx->search_stations($param);
      $data['stations'] = $records['records'];
    } else {
      $records = $this->sphinx->search_stations($param);
      $data['stations'] = $records['records'];
    }
    if (isAjax()) {
      $data['is_ajax'] = true;
      echo $this->load->view('stations/list', $data, true);
      exit;
    } else {
      $data['is_ajax'] = false;

      $this->load->view('stations/list', $data);
    }
  }

  /**
   * Show Detail of specific station
   * 
   * @param $station_id as a uri segment
   */
  public function detail() {
    $station_id = $this->uri->segment(3);
    $data['station_detail'] = $this->station_model->get_station_by_id($station_id);
    $data['station_contacts'] = $this->users->get_station_users($station_id);
    $data['station_tracking'] = $this->tracking->get_all($station_id);

    $this->load->view('stations/detail', $data);
  }

  /**
   * set or update the start time of station.
   * 
   * @param $id get id of a station
   * @param $start_date get station start date
   * @return json 
   */
  public function update_stations() {
    if (isAjax()) {
      $station_ids = $this->input->post('id');
      $station_ids = explode(',', $station_ids);
      $start_date = $this->input->post('start_date');
      $end_date = $this->input->post('end_date');
      $is_certified = $this->input->post('is_certified');
      $is_agreed = $this->input->post('is_agreed');
      $start_date = $start_date ? $start_date : NULL;
      $end_date = $end_date ? $end_date : NULL;
      $station = array();
      foreach ($station_ids as $value) {
        $station[] = $this->station_model->update_station($value, array('start_date' => $start_date, 'end_date' => $end_date, 'is_certified' => $is_certified, 'is_agreed' => $is_agreed));

        $this->sphinx->update_indexes('stations', array('start_date', 'end_date', 'is_certified', 'is_agreed'), array($value => array(strtotime($start_date), strtotime($end_date), $is_certified, $is_agreed)));
      }

//            print exec("/usr/bin/indexer --all --rotate");


      echo json_encode(array('success' => true, 'station' => $station, 'total' => count($station_ids)));
      exit;
    }
    show_404();
  }

  /**
   *  Get List of stations by Id by Ajax Request.
   *  
   * @param $id as post parameter
   * @return json
   */
  public function get_stations() {
    if (isAjax()) {
      $this->station_model->delete_stations_backup();
      $stations_id = $this->input->post('id');
      $records = $this->station_model->get_stations_by_id($stations_id);
      foreach ($records as $value) {
        $backup_record = array('station_id' => $value->id, 'start_date' => $value->start_date, 'end_date' => $value->end_date, 'is_certified' => $value->is_certified, 'is_agreed' => $value->is_agreed);
        $this->station_model->insert_station_backup($backup_record);
      }
      echo json_encode(array('success' => true, 'records' => $records));
      exit;
    }
    show_404();
  }

  /**
   * Undo the last edited stations
   *  
   */
  public function undostations() {
    $backups = $this->station_model->get_all_backup_stations();
    if (count($backups) > 0) {
      foreach ($backups as $value) {
        $this->station_model->update_station($value->station_id, array('start_date' => $value->start_date, 'end_date' => $value->end_date));
        $this->sphinx->update_indexes('stations', array('start_date', 'end_date'), array($value->station_id => array(strtotime($value->start_date), strtotime($value->end_date))));
      }
    }
    redirect('stations/index', 'location');
  }

  function test() {

    $this->load->library('zend');
    $this->zend->load('Zend/Gdata/Spreadsheets');
    $this->zend->load('Zend/Gdata/ClientLogin');
    $this->zend->load('Zend/Gdata/Calendar');



    $email = 'purelogicsy@gmail.com';
    $passwd = 'purelogics123';
    $service = Zend_Gdata_Spreadsheets::AUTH_SERVICE_NAME;
    try {
      $client = Zend_Gdata_ClientLogin::getHttpClient($email, $passwd, $service);
      $oSpreadSheet = new Zend_Gdata_Spreadsheets($client);
    } catch (Zend_Gdata_App_CaptchaRequiredException $cre) {
      echo 'URL of CAPTCHA image: ' . $cre->getCaptchaUrl() . "\n";
      echo 'Token ID: ' . $cre->getCaptchaToken() . "\n";
    } catch (Zend_Gdata_App_AuthException $ae) {
      echo 'Problem authenticating: ' . $ae->getMessage() . "\n";
    }

    $spreadsheetTitle = array();
    $list = $oSpreadSheet->getSpreadsheetFeed();
    foreach ($list->entries as $key=>$entry) {
      $spreadsheetTitle[$key]['name'] = $entry->title->text;
      $spreadsheetTitle[$key]['URL'] = $entry->link[1]->href;
      $spreadsheetTitle[$key]['entityID'] = $entry->id;
    }

    $spreadsheetKey = basename($spreadsheetTitle[0]['entityID']);

    $query = new Zend_Gdata_Spreadsheets_DocumentQuery();
    $query->setSpreadsheetKey($spreadsheetKey);
    $feed = $spreadsheetService->getWorksheetFeed($query); // now that we have the desired spreadsheet, we need the worksheets

    /**
     * Loop through all of our worksheets and echo
     * its name as well as its id
     */
    echo("<table><tr><td><strong>Spreadsheet Name:</strong></td><td>" . $spreadsheetToFind . "</td></tr><tr><td><strong>Spreadsheet ID:</strong></td><td>" . $spreadsheetKey . "</td></tr>");

    foreach ($feed->entries as $entry) {
      echo("<tr><td><strong>" . $entry->title->text . ": </strong></td><td>" . basename($entry->id) . "</td></tr>");
    }

    echo("</table>");
    echo '<pre>';
//    print_r($spreadsheetTitle);
    echo(" </pre> ");
    EXIT;
    $entry = $oSpreadSheet->newCellEntry();

    $cell = $oSpreadSheet->newCell();
    $cell->setText('My cell value');
    $cell->setRow('1');
    $cell->setColumn('3');
    $entry->cell = $cell;

    echo(" <pre> ");
    var_dump($entry);
    echo(" </pre> ");
    EXIT;

    // newer versions of CodeIgniter have updated its loader API slightly,
    // we can no longer pass parameters to our library constructors
    // therefore, we should load the library like this:
    // $this->load->library('zend');
    // $this->zend->load('Zend/Service/Flickr');
  }

}

?>