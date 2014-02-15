import gr.ntua.ivml.athena.persistent.Transformation
import gr.ntua.ivml.athena.concurrent.Queues;

// redo successfull transformations (problem with locks!!)

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
