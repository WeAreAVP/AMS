<?php

//define('DOM_PDF_HOME', dirname(dirname(__FILE__)));
//require_once(DOM_PDF_HOME . '/third_party/dompdf/dompdf_config.inc.php');
class Dompdf_lib
{

	var $_dompdf = NULL;

	function __construct()
	{
		require_once("dompdf/dompdf_config.inc.php");
		if (is_null($this->_dompdf))
		{
			$this->_dompdf = new DOMPDF();
		}
	}

	function convert_html_to_pdf($data, $filename = 'sample.pdf', $stream = TRUE)
	{

		$html = '<center><h2>Digitization Statistics</h2></center>';
		if (count($data['dsd_report']) > 0)
		{
			$html .='<div style="background:green;">Scheduled for Digitization</div>';
		}
		$this->_dompdf->load_html($html);
		$this->_dompdf->render();
		return $this->_dompdf->stream($filename, array("Attachment" => 0));
//        return $this->_dompdf->stream($filename);
//        if ($stream) {
//            $this->_dompdf->stream($filename);
//        } else {
		return $this->_dompdf->output();
//        }
	}

}

?>