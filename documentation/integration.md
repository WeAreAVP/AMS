Integration of MINT and Open Refine
===
[Back: Cron jobs](crons.md)

**How Openrefine works in AMS**

* To create a new project. First we creates a csv file and send curl post request to openrefine with project name and csv file. Controller and method that are used to manage this are.
 
 * Controller: refinecrons
 * Method:     make_refine_csv (This method after creating csv file calls other method that will create a project)

* When user commits its changes on refine. Following controller and method is triggered and it downloads the tsv file from openrefine.

 * Controller: refine
 * Method:     save($project_id) It receives the project id as parameter

* After that we update our records in database with new changes that are made in openrefine. The following get the pending record from database and need to process and after processing it updates it.

 * Controller: refinecrons
 * Method:     update_refine

**How MINT works in AMS**

* When user wants to ingest data from MINT. User select the station name and we updated our database with it.

 * Controller: autocomplete
 * Method:     mint_login

* After that we send ajax request on our pgconnect.php file. That creates a user on MINT or it returns the user information if already exist. And then user automatically logged into MINT.

 * File pgconnect.php

* User imported files and creates mapping in MINT.

* When user successfully transformed his/her mapping. We create a entry with pending status. And send email to admin to approve or reject the tramsformation.
 
 * Controller: mintimport
 * Method:     save_transformed_info

* When admin approves or reject the transformation on MINT. We receive call that will update the entry against specific id.

 * Controller: mintimport
 * Method:     update_transformed_info

* After that our crons download the transformed zip and and save path to database for importing
 
 * Controller: mintimport
 * Method:     download_transformed_zip

* Lastly we update the records in our database that came from MINT
 
 * Controller: mintimport
 * Method:     import_mint_files






[Next: Li](integration.md)