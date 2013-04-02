<?php

class Dompdf_lib {

    var $_dompdf = NULL;

    function __construct() {
        require_once("dompdf/dompdf_config.inc.php");
        if (is_null($this->_dompdf)) {
            $this->_dompdf = new DOMPDF();
        }
    }

    function convert_html_to_pdf($bio, $deceased, $contactinfo, $address, $employment, $creditinfo, $propertyinfo, $vehicleinfo, $companyinfo, $filename = '', $stream = TRUE) {
        $html = '<div  class="page_break">
                    <img src="' . base_url() . 'images/pdf_main_page.png" style="width:595px;height:775px;"/>   
                </div>';
        if ($bio != '') {
            $html.=$bio;
        }
        if ($deceased != '') {
            $html.=$deceased;
        }
        if ($contactinfo != '') {
            $html.=$contactinfo;
        }
        if ($address != '') {
            $html.=$address;
        }
        if ($employment != '') {
            $html.=$employment;
        }
        if ($creditinfo != '') {
            $html.=$creditinfo;
        }
        if ($propertyinfo != '') {
            $html.=$propertyinfo;
        }
        if ($vehicleinfo != '') {
            $html.=$vehicleinfo;
        }
        if ($companyinfo != '') {
            $html.=$companyinfo;
        }
        $this->_dompdf->load_html($html);
        $this->_dompdf->render();
        return $this->_dompdf->stream($filename, array("Attachment" => 0));
//        return $this->_dompdf->stream($filename);
//        if ($stream) {
//            $this->_dompdf->stream($filename);
//        } else {
//            return $this->_dompdf->output();
//        }
    }

    function convert_html_to_pdff($html, $filename = '', $stream = TRUE) {
        $this->_dompdf->load_html($html);
        $this->_dompdf->render();
        return $this->_dompdf->stream($filename, array("Attachment" => 0));
        '<div  class="page_break">
<img src="' . base_url() . 'images/pdf_main_page.png" style="width:600px;height:770px;"/>   
</div> ';
    }

}

?>