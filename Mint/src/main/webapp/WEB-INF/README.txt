How to extend the http Upload size

Change the connector in Tomcats server xml

    <Connector URIEncoding="UTF-8" connectionTimeout="20000" port="8080" protoco
l="HTTP/1.1" redirectPort="8443" maxPostSize="50000000" />
 
Edit struts.xml




How general data manipulation could or should work?

a) We dont want to change the indexed xml-node table
 Its too big and too valueable to manipulate

There should be a way to generate new fields, but how can we access those?
-> create a table for new xpaths which are derived

We could store the xpath and a scriptlet that creates the value
for the field. There we need to provide good api support
for 
 1 - access other paths for the same item
 2 - access any items path in any upload
 3 - do manipulation on them
 4 - create xml like data ...

This can then access per item on export or show. Stats
for the field could be pre-calculated or maybe even calculated
on the fly (small datasets!)?

This supports mapping by virtue of
  mapped new path - old path accessor
  or concat old path accessor + other old path ...
  or defaulting default( access1, access2 ... , default)
  

The table could look somehow like this

int DataUploadId - reference the upload for which this is done.
String newXpath - how the new xpath looks like
int aliasId - It could be just an alias, give the original xpathId here from the xpath_summary table
String valueExpression - a groovy expression that can create the value for the field.

