import gr.ntua.ivml.athena.persistent.*
import org.apache.commons.io.IOUtils;

// Dump blobs from Uploads with successful Transformation
// dumps the transformaltion blob as well

outputDir = new File( "/tmp/testDump" );

dus = DB.getDataUploadDAO().scrollAll();

while( dus.next() ) {
  du = dus.get()[0];
  Transformation myTr = null;
  for( tr in du.getTransformations() ) {
	 if( tr.getZippedOutput() != null ) {
	   myTr = tr;
	 }
  }
  if( myTr != null ) {
  blobDump( du.getDbID()+".zip", du.getData() );
  blobDump( du.getDbID()+"_tr.zip", myTr.getZippedOutput() );
  }
  DB.getSession().clear();
}

def blobDump( name, blobWrap ) {
   out = new File( outputDir, name );
  outStream = new FileOutputStream( out );
  IOUtils.copy( blobWrap.getData().getBinaryStream(), outStream );
  outStream.close()
  println( "Dumping " + out.getAbsolutePath());
}
