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

Station index: Its a realtime index.

Default Path: `/var/lib/sphinx/station/station`
| Sphinx Column  | Sphinx Column Type  | Database Column | Description |
| :------------- |:-------------------| :---------------|:-------------|
| s_station_name | rt_field    | stations.station_name			|			   |
| station_name   | rt_attr_string           |   stations.station_name			|			   |
| s_type         | rt_field           |    stations.type			|			   |
| type           | rt_attr_string           |    stations.type		|			   |
| s_address_primary | rt_field        |    stations.address			|			   |