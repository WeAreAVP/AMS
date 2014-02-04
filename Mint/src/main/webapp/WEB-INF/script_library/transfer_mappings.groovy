import groovy.sql.Sql
import gr.ntua.ivml.athena.persistent.Mapping;

// Transfer mappings from test server (brain) to here

// enter correct mapping ids and target org (with trailing l!!) 
ids = [ 1297, 1312 ];
targetOrgId = 1044l;

// connection to brain
sql = Sql.newInstance( "jdbc:postgresql://brain/athena", "athena", "athena",
 "org.postgresql.Driver" );
 
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
