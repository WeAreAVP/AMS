MINT Setup
===
[Back: Open Refine Configuration](openrefine-configure.md)

**1) Goto Mint directory**

	$ cd Mint

**2) Change only domain name in following files**

* ./src/main/java/gr/ntua/ivml/mint/actions/AjaxApproval.java at line number 80
* ./src/main/java/gr/ntua/ivml/mint/concurrent/XSLTransform.java at line number 126
* ./src/main/webapp/WEB-INF/jsp/menu.jsp at line number 33

**3) Build Project** 

	mvn package

It will create a build file in 'target' directory.

**4) Move build file in tomcat web directory**

For example. if tomcat web directory is /usr/share/tomcat6/webapps then

	$ cp target/mint-ams.war /usr/share/tomcat6/webapps/mint-ams.war

**5) Restart tomcat service**

	$ /etc/init.d/tomcat6 restart

**6) Database Setup**

Create a database
	
	sudo -u postgres createdb -E UTF8 mint

Login

	sudo -u postgres psql mint

create a user for the application

	create user mint password 'mint' login;

set the user's search path

	alter role mint set search_path to mint,public;

and grant database to the user

	grant all on database mint to mint;

Logout with

	\q

Locate the createSchema.sql in your mint installation (./Mint/src/main/webapp/WEB-INF/src)

Login again as mint

	psql -h localhost -U mint mint

and read in the schema. Its helpful to be in the directory with the createSchema.sql

	\i createSchema.sql

That should setup the schema! Run the update

	\i upgrade_sql_thesauri_1_3.sql

Alter tables

	ALTER TABLE transformation ADD COLUMN is_approved int NOT NULL default 0;

	ALTER TABLE mapping ADD COLUMN user_id int NOT NULL default 1000;

After that, edit the postgresql.conf file - which is normally kept in the data directory (initdb installs a default copy there) - by setting

	#constraint_exclusion = off
	
	constraint_exclusion = on

Now Your MINT is setup.

See the offical site for more details

http://mint.image.ece.ntua.gr/redmine/projects/mint/wiki/Mint



[Next: NOID Setup](noid-configure.md)
