NOID Setup
===
[Back: MINT Setup](mint-configure.md)

**1) Create directory for NOID on apache web directory**

in this case /var/www/html

	mkdir /var/www/html/nd

**2) Creates an executable copy of the noid script**

	cp -p /usr/local/bin/noid /var/www/html/nd

**3) Add following in apache config file and restart apache**

	ScriptAliasMatch ^/nd/noidu(.*) "/var/www/html/nd/noidu$1"

	/etc/init.d/httpd restart

**4) Go to "nd" directory**

	cd /var/www/html/nd

**5) Create NOID database**

	noid dbcreate .reedeedeedk

	mkdir kt5
 
	mv NOID kt5/
    
	ln noid noidu_kt5

You can now mint identifier.

http://domainname.com/nd/noidu_kt5?mint+1 

To see more detail on NOID. Visit

http://search.cpan.org/~jak/Noid-0.423/noid

[Next: Cronjobs](crons.md)

