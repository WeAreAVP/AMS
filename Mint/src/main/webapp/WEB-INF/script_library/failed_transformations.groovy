
import gr.ntua.ivml.athena.persistent.Transformation
// show ids of failed transformations

for( tr in DB.getTransformationDAO().findAll() ) {
  if( tr.statusCode != Transformation.OK ) {
       printf( "Transformation %6d Code %2d Message %s\n", [tr.dbID, tr.statusCode, tr.statusMessage] )
  }
       DB.getSession().evict( tr );
}

