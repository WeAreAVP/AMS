Cron Jobs
===
[Back: NOID Setup](noid-configure.md)

**Note: Bash scripts are used in crons setup to prevent from running process more than one time.**

You can add the crons using crontab

	$ crontab -e


It runs every minute and process limited csv export those are pending.

	* * * * *       /bin/sh /var/www/html/crons/export_csv.sh > /dev/null 2>&1

It runs every minute and send the email that are pending in system.

	* * * * *       /bin/sh /var/www/html/crons/process_email.sh > /dev/null 2>&1

This creates a new project on open refine and send the email to the user.

	* * * * *       /bin/sh /var/www/html/crons/make_refine_csv.sh > /dev/null 2>&1

This updates the records when changes are commit on google refine.

	* * * * *       /bin/sh /var/www/html/crons/update_refine.sh > /dev/null 2>&1

Facet sidebar filters are stored in memcache for quick response.
 
	* * * * * /bin/sh /var/www/html/crons/memcache.sh  > /dev/null 2>&1

It stores the dashboard information into memcache to load the page quickly.

	*/10 * * * * /bin/sh /var/www/html/crons/dashboard_memcache.sh  > /dev/null 2>&1

The following script run onece in day and update the database if new spreadsheet in added in google account.
	
	@daily /bin/sh /var/www/html/crons/fetch_gsheets.sh > /dev/null 2>&1

This cron import the data from google spreadsheet into events table

	*/15 * * * * /bin/sh /var/www/html/crons/import_gsheets.sh > /dev/null 2>&1

This cron check if any transformation is approved in mint the download it to server

	* * * * * /bin/sh /var/www/html/crons/download_mint_zip.sh  > /dev/null 2>&1

This cron is used to import records into system that are tranformed from MINT.

	* * * * * /bin/sh /var/www/html/crons/import_mint.sh  > /dev/null 2>&1

This cron is used to export the bag of xml files for PBCore and PREMIS.

	* * * * * /bin/sh /var/www/html/crons/export_pbcore.sh  > /dev/null 2>&1


Unzip all the crawford files and place it in mediainfo folder

	0 4 * * * /bin/sh /var/www/html/crons/unzip_mediainfo.sh  > /dev/null 2>&1

Process the mediainfo files and store the path to database.
	
	0 5 * * * /bin/sh /var/www/html/crons/mediainfo_process_dir.sh  > /dev/null 2>&1

Import mediainfo files data to database.

	0 23 */2 * * /bin/sh /var/www/html/crons/mediainfo_import_xml.sh  > /dev/null 2>&1