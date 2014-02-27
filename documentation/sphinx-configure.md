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

* rt_filed attr only used for searching and rt_attr_string is used for displaying and sorting.
* 'id' attribute is reserved and used it insert or update value in index. 

**Sphinx Indexes**

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

Visit http://sphinxsearch.com/ for more detail

[Next: Open Refine Configuration](openrefine-configure.md)