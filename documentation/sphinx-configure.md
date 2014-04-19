Sphinx Configuration
===
[Back: Installation and Configuration](install-configure.md)

**1) Replace sphinx original configuration with AMS sphinx.conf file. For example;**

	$ cp sphinx.conf /etc/sphinx/sphinx.conf

**2) You need to create folders to store indexes data.**

For current sphinx.conf we have path to set to `/var/lib/sphinx`.

	$ cd /var/lib/sphinx
	$ mkdir station
	$ mkdir instantiation
	$ mkdir asset
	
All the above folders should have ownership of sphinx. You can set ownership using 

	$ chown -R sphinx:sphinx .

**3) Restart Sphinx Service. e.g;**
	
	$ /etc/init.d/searchd restart

**Sphinx Configuration Detail**

**Note:**

* rt_field attr only used for searching and rt_attr_string is used for displaying and sorting.
* 'id' attribute is reserved and used it insert or update value in index. 

###Sphinx Indexes

**Index name:** stations

**Index type:** rt (realtime)

**Index path:** `/var/lib/sphinx/station/station`


********************************

**Index name:** instantiations_list

**Index type:** rt (realtime)

**Index path:** `/var/lib/sphinx/instantiation/instantiation`

********************************

**Index name:** assets_list

**Index type:** rt (realtime)

**Index path:** `/var/lib/sphinx/asset/asset`

********************************

If you already have data in your database and you want to insert in Sphinx Index.Then run following commands.

You should be in project directory.

Insert Stations data

	$ php index.php searchd insert_station_sphnix

Insert Assets data

	$ php index.php searchd insert_assets_sphnix

Insert Instantiations data
	
	$ php index.php searchd insert_instantiations_sphnix

**Re-index Sphinx**

First you need to remove old index files. Default path of files is `/var/lib/sphinx`.

If you want to re-index stations index.
	
	$ rm -rf /var/lib/sphinx/station/*
	
	$ php /var/www/html/index.php searchd insert_station_sphnix
	
If you want to re-index assets	
	
	$ rm -rf /var/lib/sphinx/asset/*
	
	$ php /var/www/html/index.php searchd insert_assets_sphnix
	
If you want to re-index instantiations	
	
	$ rm -rf /var/lib/sphinx/instantiation/*
	
	$ php /var/www/html/index.php searchd insert_instantiations_sphnix


Visit http://sphinxsearch.com/ for more detail

[Next: Open Refine Configuration](openrefine-configure.md)
