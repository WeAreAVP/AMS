<?php

class XML extends CI_Controller
{

	function __construct()
	{
		
	}

	function pbcore()
	{
		$xml = new SimpleXMLElement('<xml/>');

		for ($i = 1; $i <= 8; ++ $i)
		{
			$track = $xml->addChild('track');
			$track->addChild('path', "song$i.mp3");
			$track->addChild('title', "Track $i - Track Title");
		}

		Header('Content-type: text/xml');
		echo $xml->asXML();
	}

}
