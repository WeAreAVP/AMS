Open Refine Configuration
===
[Back: Sphinx Configuration](sphinx-configure.md)	

**1) Configure base path in (documenation/refine/refine);**

	$ vim documenation/refine/refine

Now set BASEPATH .Open Refine source path will be your base path. For example.

	BASEPATH=/var/www/html/OpenRefine

**2) Set up open refine as Service**

So simply load this script into /etc/init.d

	$ cp documenation/refine/refine /etc/init.d/refine


**3) Set the permissions on it to make it executable**
	
	$ chmod 755 /etc/init.d/refine

**4) Register the script to start up with the server**

	$ chkconfig refine on

**5) Now you can start up refine as a service.**

	$ /etc/init.d/refine start

Note

 You can check the status

	$ /etc/init.d/refine status

 You can restart the service

	$ /etc/init.d/refine restart

 You can stop the service

	$ /etc/init.d/refine stop

See more about Open refine. http://openrefine.org/




[Next: MINT Setup](mint-configure.md)