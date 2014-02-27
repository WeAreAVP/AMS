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





[Next: Li](integration.md)