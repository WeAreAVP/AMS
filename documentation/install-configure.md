Installation and Configuration
===
[Back: Prerequisite](prerequisite.md)

AMS System Application
----------
Application is build in PHP Framework **[CodeIgniter] (http://ellislab.com/codeigniter)**

1) First clone code from git using following command.

	$ git clone git@github.com:avpreserve/AMS.git

2) Goto Code Directory

	$ cd AMS

3) Run create_directory script. It will create required folders and set permissions.

	$ sh create_directory.sh

4) Replace sphinx original configuration with AMS sphinx.conf file. e.g;

	$ cp sphinx.conf /etc/sphinx/sphinx.conf

5) Restart Sphinx Service. e.g;
	
	$ /etc/init.d/searcd restart

6) Use mysql dump file 

	[mySQL dump file] (schema.sql)

7) Configuration variable that can be changed as per requirements (application/config/config.php)

	$config['base_url'] = "https://ams.avpreserve.com/";  Base URL of application

	$config['to_email'] = 'ssapienza@cpb.org';			  Admin Email Address

	$config['from_email'] = 'noreply@ams.avpreserve.com'; Default emil from when sending email

	$config['crawford_email'] = 'cstephenson@mail.crawford.com'; Crawford Email Address

	$config['path'] = '/var/www/html/';					  Document Root of application path

	$config['cookie_domain'] = ".ams.avpreserve.com";	  Cookie name

	$config['mint_url'] = "http://ams.avpreserve.com:8080/mint-ams"; Default MINT URL 

	$config['google_refine_url'] = "http://ams.avpreserve.com:3333"; Default Open Refine URL

	$config['instance_name'] = 'ams';					Instance type for MINT	

	$config['asset_index'] = 'assets_list';				Sphinx Asset Index Name

	$config['instantiatiion_index'] = 'instantiations_list'; Sphinx Instantiation Index Name

	$config['station_index'] = 'stations';				Sphinx Station index name

	$config['google_spreadsheet_email'] = 'nouman@avpreserve.com'; Email address to get crawford spreadsheet data

	$config['google_spreadsheet_password'] = 'bm91bWFuQGF2cHM=';   Password of the email address (should be in base64_encode)


