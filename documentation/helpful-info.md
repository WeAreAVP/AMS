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
