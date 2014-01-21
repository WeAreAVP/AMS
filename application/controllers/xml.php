<?php

class Xml extends CI_Controller
{

	function __construct()
	{
		$this->layout = 'default.php';
	}

	function pbcore()
	{
		$xml = new SimpleXMLElement('<pbcoreDescriptionDocument/>');
		$xml->addAttribute('xmlns', "http://www.pbcore.org/PBCore/PBCoreNamespace.html");
		$xml->addAttribute('xmlns:xsi', "http://www.w3.org/2001/XMLSchema-instance");
		$xml->addAttribute('xsi:schemaLocation', "http://www.pbcore.org/PBCore/PBCoreNamespace.html http://pbcore.org/xsd/pbcore-2.0.xsd");
		for ($i = 1; $i <= 8; ++ $i)
		{
			$track = $xml->addChild('track');
			$track->addAttribute('source', 'Test');
			$track->addChild('path', "song$i.mp3");
			$track->addChild('title', "Track $i - Track Title");
		}

		Header('Content-type: text/xml');
		echo $xml->asXML();
		exit;
	}

}
