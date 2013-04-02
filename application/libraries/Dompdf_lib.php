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
			$html .='<div style="page-break-after: always;"><br/><div><h4>Scheduled for Digitization</h4></div><br/>';
			$html .='<table style="width:100%; "border="1"><thead style="font-weight:bold;"><tr><td>Station Name</td><td>Nominated Assets</td><td>City</td><td>State</td></tr></thead><tbody>';
			foreach ($data['dsd_report'] as $value)
			{
				$html .='<tr>';
				$html .='<td>' . $value->station_name . '</td>';
				$html .='<td>' . $value->total . '</td>';
				$html .='<td>' . $value->city . '</td>';
				$html .='<td>' . $value->state . '</td>';
				$html .='</tr>';
			}
			$html .='</tbody></table></div>';
		}
		if (count($data['material_at_crawford_report']) > 0)
		{
			$html .='<div style="page-break-after: always;"><br/><div><h4>Materials at Crawford</h4></div><br/>';
			$html .='<table border="1"><thead style="font-weight:bold;"><tr><td>Station Name</td><td>Nominated Assets</td><td>City</td><td>State</td></tr></thead><tbody>';
			foreach ($data['material_at_crawford_report'] as $value)
			{
				$html .='<tr>';
				$html .='<td>' . $value->station_name . '</td>';
				$html .='<td>' . $value->total . '</td>';
				$html .='<td>' . $value->city . '</td>';
				$html .='<td>' . $value->state . '</td>';
				$html .='</tr>';
			}
			$html .='</tbody></table></div>';
		}
		if (count($data['shipment_report']) > 0)
		{
			$html .='<div style="page-break-after: always;"><br/><div><h4>Files Delivered for Verification</h4></div><br/>';
			$html .='<table border="1"><thead style="font-weight:bold;"><tr><td>Station Name</td><td>Nominated Assets</td><td>City</td><td>State</td></tr></thead><tbody>';
			foreach ($data['shipment_report'] as $value)
			{
				$html .='<tr>';
				$html .='<td>' . $value->station_name . '</td>';
				$html .='<td>' . $value->total . '</td>';
				$html .='<td>' . $value->city . '</td>';
				$html .='<td>' . $value->state . '</td>';
				$html .='</tr>';
			}
			$html .='</tbody></table></div>';
		}
		if (count($data['hd_return_report']) > 0)
		{
			$html .='<div style="page-break-after: always;"><br/><div><h4>Verified/Complete</h4></div><br/>';
			$html .='<table border="1"><thead style="font-weight:bold;"><tr><td>Station Name</td></tr></thead><tbody>';
			foreach ($data['hd_return_report'] as $value)
			{
				$html .='<tr>';
				$html .='<td>' . $value->station_name . '</td>';

				$html .='</tr>';
			}
			$html .='</tbody></table></div>';
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