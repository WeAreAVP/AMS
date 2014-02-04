import gr.ntua.ivml.athena.persistent.Transformation
import gr.ntua.ivml.athena.concurrent.Queues;

// redo a single transformation

// specify id with trailing l
tr = DB.getTransformationDAO().getById( 1156l, false  ) 


tr.setBeginTransform(new Date());
tr.setStatusCode(Transformation.IDLE);
tr.setStatusMessage( "" );
tr.setJsonMapping(tr.getMapping().getJsonString());
tr.setEndTransform( null );
DB.commit();
Queues.queueTransformation(tr);
println( "Queued Transformation " + tr.dbID )

