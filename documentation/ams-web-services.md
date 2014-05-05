AMS Web Services
===

**PBCore Web Service**

**URL:** https://ams.americanarchive.org/xml/pbcore

**Parameters**


| Parameter	    | Required | Validation	 | Description													|
| :-------------| :--------| :-----------| :------------------------------------------------------------|
| key		    | YES	   | valid key	 | Need to have valid key to access the AMS web services.		|
| digitized		| NO       | 0 or 1		 | to get the information of digitized and non-digitized data	|
| modified_date | NO       | YYYYMMDD	 | Asset created/modified date									|
| guid			| NO       | valid GUID	 | must be a valid GUID from AMS. e.g; 35-49t1g6gj				|
| page		    | NO       | integer	 | Use page number if records are more then 1000	            |

**Note**

guid,digitized or modified_date. One of the parameter is required to access the web sevices.

**PREMIS Web Service**

**URL:** https://ams.americanarchive.org/xml/premis

**Parameters**


| Parameter	    | Required | Validation	 | Description													|
| :-------------| :--------| :-----------| :------------------------------------------------------------|
| key		    | YES	   | valid key	 | Need to have valid key to access the AMS web services.		|
| digitized		| NO       | 0 or 1		 | to get the information of digitized and non-digitized data	|
| modified_date | NO       | YYYYMMDD	 | Asset created/modified date									|
| guid			| NO       | valid GUID	 | must be a valid GUID from AMS. e.g; 35-49t1g6gj				|
| page		    | NO       | integer	 | Use page number if records are more then 1000	            |

**Note**

guid,digitized or modified_date. One of the parameter is required to access the web sevices.

Sample Requests

https://ams.americanarchive.org/xml/premis/key/b5f3288f3c6b6274c3455ec16a2bb67a/guid/35-49t1g6gj
https://ams.americanarchive.org/xml/pbcore/key/b5f3288f3c6b6274c3455ec16a2bb67a/guid/35-49t1g6gj
https://ams.americanarchive.org/xml/premis/key/b5f3288f3c6b6274c3455ec16a2bb67a/digitized/1/modified_date/20130601
https://ams.americanarchive.org/xml/pbcore/key/b5f3288f3c6b6274c3455ec16a2bb67a/digitized/1/modified_date/20130601


**How to change the key**

Goto config folder and edit the config.php file.

	$ cd /var/www/html/application/config
	$ vim config.php

You will find parameter with name $config['web_service_key']. Replace the value with new value and save the file.

	$config['web_service_key'] = 'b5f3288f3c6b6274c3455ec16a2bb67a';





 









