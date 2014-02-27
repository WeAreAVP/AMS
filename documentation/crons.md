Cron Jobs
===
[Back: NOID Setup](noid-configure.md)

**Note: Bash scripts are used in crons setup to prevent from running method more than one time.**

You can add the crons using crontab

	$ crontab -e

This cron is used to export csv file and send to user who exported it.

	* * * * *       /bin/sh /var/www/html/crons/export_csv.sh > /dev/null 2>&1

This cron send the emails are are in our system queue.

	* * * * *       /bin/sh /var/www/html/crons/process_email.sh > /dev/null 2>&1

This creates an open refine project and send the URL to user.

	* * * * *       /bin/sh /var/www/html/crons/make_refine_csv.sh > /dev/null 2>&1

This crons update the records in system that are updated using open refine.

	* * * * *       /bin/sh /var/www/html/crons/update_refine.sh > /dev/null 2>&1

This will cached the facet records for fast result.
 
	* * * * * /bin/sh /var/www/html/crons/memcache.sh  > /dev/null 2>&1

The following cron used memcache to store dashboard info so it display quickly

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