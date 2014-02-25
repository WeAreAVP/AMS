Installation and Configuration
===
[Back to Introduction](../README.md)

1) First clone code from git using following command.

	$ git clone git@github.com:avpreserve/AMS.git

2) Goto Code Directory

	$ cd AMS

3) Run create_directory script. It will create required folders and set permissions.

	$ sh create_directory.sh

4) Replace sphinx original configuration with AMS sphinx.conf file. e.g;

	$ cp sphinx.conf /etc/sphinx/sphinx.conf

5) Restart Sphinx Service. e.g;
	
	$ /etc/init.d/searcd restart

6) Use mysql dump file 

	[mySQL dump file] (schema.sql)


