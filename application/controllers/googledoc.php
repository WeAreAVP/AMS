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
		$this->load->library('zend');
        $this->zend->load('Zend/Gdata/Spreadsheets');
        $this->zend->load('Zend/Gdata/ClientLogin');
        $this->zend->load('Zend/Gdata/Calendar');
    }
    function test()
    {
		$email = 'ali@geekschicago.com';
        $passwd = 'purelogics12';
        $service = Zend_Gdata_Spreadsheets::AUTH_SERVICE_NAME;
        try
        {
            $client = Zend_Gdata_ClientLogin::getHttpClient($email, $passwd, $service);
            $oSpreadSheet = new Zend_Gdata_Spreadsheets($client);
        } catch (Zend_Gdata_App_CaptchaRequiredException $cre)
        {
            echo 'URL of CAPTCHA image: ' . $cre->getCaptchaUrl() . "\n";
            echo 'Token ID: ' . $cre->getCaptchaToken() . "\n";
        } catch (Zend_Gdata_App_AuthException $ae)
        {
            echo 'Problem authenticating: ' . $ae->getMessage() . "\n";
        }

        $spreadsheetTitle = array();
        $list = $oSpreadSheet->getSpreadsheetFeed();
        foreach ($list->entries as $key => $entry)
        {
            $spreadsheetTitle[$key]['name'] = $entry->title->text;
            $spreadsheetTitle[$key]['URL'] = $entry->link[1]->href;
            $spreadsheetTitle[$key]['entityID'] = $entry->id;
        }

        echo $spreadsheetKey = basename($spreadsheetTitle[0]['entityID']);

        $query = new Zend_Gdata_Spreadsheets_ListQuery();
        $query->setSpreadsheetKey($spreadsheetKey);
        $feed = $oSpreadSheet->getWorksheetFeed($query); // now that we have the desired spreadsheet, we need the worksheets
        echo '<pre>';
       // print_r($feed);
        exit;

        /**
         * Loop through all of our worksheets and echo
         * its name as well as its id
         */
        echo("<table><tr><td><strong>Spreadsheet Name:</strong></td><td>" . $spreadsheetToFind . "</td></tr><tr><td><strong>Spreadsheet ID:</strong></td><td>" . $spreadsheetKey . "</td></tr>");

        foreach ($feed->entries as $entry)
        {
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

// END Stations Controller

/* End of file stations.php */
/* Location: ./application/controllers/stations.php */