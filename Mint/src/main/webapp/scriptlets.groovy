import java.io.PrintWriter
import java.io.StringWriter

du = DB.getDataUploadDAO().getById( 1252l, false )

itemRoot = du.getItemXpath()
item = itemRoot.getNodes( 0,1).get(0)

sw = new StringWriter()
out = new PrintWriter( sw )

item.toXmlWrapped( out )


===== create the prefixes for existing db ===


import gr.ntua.ivml.athena.db.GlobalPrefixStore
import gr.ntua.ivml.athena.util.StringUtils

sr = DB.getXpathHolderDAO().scrollAll()

while( sr.next() ) {
  xp = sr.get(0)
  if( ! StringUtils.empty( (String) xp.getUri() )) {
   GlobalPrefixStore.createPrefix( (String) xp.getUri(), (String) xp.getUriPrefix() )
  }
  DB.getSession().clear()
}
sr.close()


========= check the XOM stuff

import gr.ntua.ivml.athena.persistent.*
import nu.xom.*

xo = DB.getXmlObjectDAO().findAll().find{ it.getRoot() != null }
xp = xo.getRoot().getByRelativePath( "/OAI-PMH/GetRecord/record" );
item = xp.getNodes( 10, 1).get(0)
tree = XMLNode.buildItemWrapTree( item )
elem = tree.toXOMElement()
 context = new XPathContext("oai", "http://www.openarchives.org/OAI/2.0/")

elem.query( "//oai:record", context ).size()
//elem.toXML()

==== transfer mappings from brain to nitro

import groovy.sql.Sql
import gr.ntua.ivml.athena.persistent.Mapping;

// connection to brain
sql = Sql.newInstance( "jdbc:postgresql://brain/athena", "athena", "athena",
 "org.postgresql.Driver" );
 
 
ids = [ 1297, 1312 ];
targetOrgId = 1044l;
//targetOrgId = 1001;

targetOrg = DB.getOrganizationDAO().getById( targetOrgId, false )
if( targetOrg == null ) throw new Exception( "Couldnt retreive target org" );
for( mappingId in ids ) {
   row = sql.firstRow( """select name, json from mapping  where mapping_id = $mappingId""" );
   if( row != null ) {
     Mapping m = new Mapping();
	m.setCreationDate( new Date() );
	m.setName( row.name );
	m.setJsonString( row.json )
	m.setOrganization( targetOrg )
	
	DB.getMappingDAO().makePersistent( m )
    println( "Mapping created " );
    println( m );
    println( "Json " + m.getJsonString().size() + " characters." );
    }	     
}

================= fix up mappings with event types ===========

====== redo all successfull transformations =================


import gr.ntua.ivml.athena.persistent.Transformation
import gr.ntua.ivml.athena.concurrent.Queues;

for( tr in DB.getTransformationDAO().findAll() ) {
  if( tr.statusCode == Transformation.OK ) {
       tr.setBeginTransform(new Date());
       tr.setStatusCode(Transformation.IDLE);
       tr.setStatusMessage( "" );
       tr.setJsonMapping(tr.getMapping().getJsonString());
	   tr.setEndTransform( null );
       DB.commit();
       Queues.queueTransformation(tr);
       println( "Queued Transformation " + tr.dbID )
       DB.getSession().evict( tr );
  }
}

=========== system commands on server: example df -h ================

import org.apache.commons.io.CopyUtils
pr = Runtime.getRuntime().exec( "df -h " )
CopyUtils.copy( pr.getInputStream(), System.out )
pr.waitFor()

===== which orgs have idle transformations =======================
def  orgs = [: ];

for( tr in DB.getTransformationDAO().findAll() ) {
  if( tr.statusCode == tr.ERROR ) {
	name = tr.dataUpload.organization.englishName
	if( orgs[name] ) orgs[name] += 1
        else  orgs[ name ] = 1  
  }
}

orgs.each{ println it.key+" " + it.value }

""

======= last 100 lines of log file ==================

import org.apache.commons.io.CopyUtils
pr = Runtime.getRuntime().exec( "tail -1000 ../../../../logs/catalina.out " )
CopyUtils.copy( pr.getInputStream(), System.out )
pr.waitFor()


============ simple db size report ==================

import groovy.sql.*

c = DB.getStatelessSession().connection()
md = c.getMetaData();
uname = md.getUserName()

sql = new Sql( c )
uid = sql.firstRow( "select usesysid from pg_user where usename = '$uname'" ).usesysid
tables = sql.rows( "select relname, reltuples, relpages from pg_class where relowner = $uid order by relpages desc" )
println "table rows pages"
tables.each{if( it.relpages > 5 ) {it.each{ print it.value+" " };println()}}
""
======== db size with easy read numbers =================

import groovy.sql.*

def prettyNum( num ) {
  def suf = [ "","k", "M", "G", "T", "P", "E"  ];
  def ind = 0;
  while( num > 1000.0 ) {
    ind +=1
    num /= 1000.0
  }
  return sprintf( "%1.3g%S",[num, suf[ind]]);
}

c = DB.getStatelessSession().connection()
md = c.getMetaData();
uname = md.getUserName()

sql = new Sql( c )
uid = sql.firstRow( "select usesysid from pg_user where usename = '$uname'" ).usesysid
tables = sql.rows( "select relname, reltuples, relpages from pg_class where relowner = $uid order by relpages desc" )
tables.each{if( it.relpages > 5 ) {println "Table/Index ${it.relname} - rows ${prettyNum(it.reltuples)} - " + prettyNum( it.relpages*8000.0)}}
pages = 0
tables.each{ pages += it.relpages }

"Total space " + prettyNum(pages*8000.0)

========== redo single transformation =================

import gr.ntua.ivml.athena.persistent.Transformation
import gr.ntua.ivml.athena.concurrent.Queues;

tr = DB.getTransformationDAO().getById( 1156l, false  ) 
tr.setBeginTransform(new Date());
tr.setStatusCode(Transformation.IDLE);
tr.setStatusMessage( "" );
tr.setJsonMapping(tr.getMapping().getJsonString());
tr.setEndTransform( null );
DB.commit();
Queues.queueTransformation(tr);
println( "Queued Transformation " + tr.dbID )

=========== Queue information =======================

import gr.ntua.ivml.athena.persistent.Transformation
import gr.ntua.ivml.athena.concurrent.Queues;

dbe = Queues.queues["db"]
dbq = dbe.getQueue()
nete = Queues.queues["net"]
netq = nete.getQueue()

printf( "%10s%10s%10s\n",["Name","pending","running"] )
printf( "%10s%10d%10d\n",["db",dbq.size(), dbe.getActiveCount() ] )
printf( "%10s%10d%10d\n",["net",netq.size(), nete.getActiveCount() ] )

============= log file again ======================
import org.apache.commons.io.CopyUtils
pr = Runtime.getRuntime().exec( "tail -1000 /usr/local/apache-tomcat-6.0.24/logs/catalina.out" )
CopyUtils.copy( pr.getInputStream(), System.out )
pr.waitFor()


===== failed transformations ===================


import gr.ntua.ivml.athena.persistent.Transformation

for( tr in DB.getTransformationDAO().findAll() ) {
  if( tr.statusCode != Transformation.OK ) {
       printf( "Transformation %6d Code %2d Message %s\n", [tr.dbID, tr.statusCode, tr.statusMessage] )
  }
       DB.getSession().evict( tr );
}


