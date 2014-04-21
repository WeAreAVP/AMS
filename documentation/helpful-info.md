# Helpful Information

If you want to delete single or muliple assets or instantiations. So make sure to collect id's information from 

Asset Table `assets.id`
Instantiation Table `instantiations.id`

Then run query to delete those records from database. After that you need to remove records from sphinx. Run following steps to delete records from sphinx index.

First connect with mysql sphinx from command line

    $ mysql --protocol=tcp -P 9306
  
If you want to delete information from `assets_list`. Run the following query

    DELETE FROM assets_list WHERE id IN (comma separated id's from assets.id);
  
If you want to delete information from `instantiations_list`. Run the following query

    DELETE FROM instantiations_list WHERE id IN (comma separated id's from instantiations.id);

###GUID related quries.

table.name = identifiers.

SELECT * FROM identifiers WHERE identifiers.identifier_source = 'http://americanarchiveinventory.org';

The above query with return all the GUID's that are in AMS.


###Nominate/Un-nominate Records.

If you want to nominate records. Then you have to insert the records in 'nominations' table.

If you want to un-nominate records. You have to delete those records from 'nominations' table.


### If you do manual query in database for insert/update or delete then you have to update those modified records in sphinx index as well.

First you need to make sure you have a list of id's while doing modification in database. If successful modification you have to do following steps.

**For asset index update**

* Goto searchd.php controller located in application/controller/searchd.php.

* You will find the method with name `update_assets_index`

* Add a list of assets.id in $asset_ids array.

* save you changes.

* Run in command line `php /var/www/html/index.php searchd update_assets_index`

Above steps will update the assets index of Sphinx

**For instantiations index update**

* Goto searchd.php controller located in application/controller/searchd.php.

* You will find the method with name `update_instantiations_index`

* Add a list of instantiations.id in $instantiation_ids array.

* save you changes.

* Run in command line `php /var/www/html/index.php searchd update_instantiations_index`

Above steps will update the instantiation index of Sphinx


