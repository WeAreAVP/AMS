package gr.ntua.ivml.mint.test;

import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.persistent.DataUpload;
import gr.ntua.ivml.mint.persistent.User;

import java.util.List;

import junit.framework.TestCase;

import org.apache.log4j.Logger;

public class UserTest extends TestCase {
	public static final Logger log = Logger.getLogger( UserTest.class ) ;
	
	
	public void setUp() {
		getExample();
	}
	
	public void testGetDataUploads() {
		User u = DB.getUserDAO().findById(1000l, false );
		List<DataUpload> ld = u.getUploads();
		assertTrue( ld.size() > 0 );
		
	}
	
	
	public DataUpload getExample() {
		DataUpload du = DB.getDataUploadDAO().simpleGet("originalFilename='example.zip'");
		assertNotNull( "DataUpload 'example.zip' not uploaded", du );
		return du;
	}
}
