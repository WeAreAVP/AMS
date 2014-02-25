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
	

**3) Restart Sphinx Service. e.g;**
	
	$ /etc/init.d/searchd restart