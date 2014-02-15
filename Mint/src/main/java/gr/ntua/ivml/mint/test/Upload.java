package gr.ntua.ivml.mint.test;

import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.persistent.DataUpload;
import gr.ntua.ivml.mint.persistent.User;

import java.io.File;
import java.io.FileInputStream;
import java.io.FileOutputStream;
import java.io.InputStream;
import java.util.Enumeration;
import java.util.List;
import java.util.Random;
import java.util.zip.ZipEntry;
import java.util.zip.ZipFile;

import junit.framework.TestCase;

import org.apache.log4j.Logger;
import org.hibernate.Transaction;

public class Upload extends TestCase {
	private Logger log = Logger.getLogger( Upload.class );
	
	public void testUnzip( ) {
		File targetDir = new File( System.getProperty( "java.io.tmpdir"));
		File randomTarget = new File( targetDir, "TempDir"+ new Random().nextInt(10000));
		while( randomTarget.exists())
			randomTarget = new File( targetDir, "TempDir"+ new Random().nextInt(10000));
		
		File jar = getTestJar();
		try {
			FileInputStream dataStream = new FileInputStream( jar );
			DataUpload.unzipToDir(randomTarget, dataStream);
			randomTarget.deleteOnExit();
		} catch( Exception e ) {
			log.error( "Unzip throws ", e );
			assertTrue( "Exception thrown", false );
		}
		assertTrue( true );
	}
	
	private File getTestJar( ) {
		String path = System.getProperty( "sun.boot.class.path" );
		for( String lib: path.split(";") ) {
			if( lib.endsWith("jce.jar" )) {
				path = lib;
			}
		}
		log.debug( path );
		return new File( path );
	}
	
	public void testStore() {
		File jar = getTestJar();
		User u = DB.getUserDAO().findAll().get(0);
		try {
			DataUpload du = DataUpload.create(jar, u, "test.zip" );
			DB.getDataUploadDAO().makePersistent(du);
		} catch( Exception e  ) {
			log.error( "didnt store", e );
			assertTrue( false );
		}
	}
	
	public void testRetrieve() {
		Transaction t = DB.getSession().beginTransaction();
		List<DataUpload> l = DB.getDataUploadDAO().findAll();
		try {
			for( DataUpload du: l ) {
				if( du.isZippedUpload() && du.getUploadSize() > 0 ) {
					List<ZipEntry> lz = du.listEntries(0,10);
					assertTrue( lz.size()==10);
					InputStream is = du.getEntry("javax/crypto/Mac.class");
					byte[] buffer = new byte[1024];
					is.read(buffer, 0, 1024);
					is.close();
					assertTrue( true );
					break;
				}
			}
		} catch( Exception e ) {
			log.debug("Exception in db blob reading",e);
			assertTrue(  false );
		} finally {
			t.commit();
		}
	}
	
	// testing the helper function, assume the blob uploading of the file will work.
	public void testZipDirectory() {
		log.info("Zip a directory into a db blob");
		// call DataUpload. zipDirectory( File dir, OutputStream os )
		// check that the os contains all the entries
		try {
			File dir = getADir();
			File tmpZip = File.createTempFile("TestDirZip", ".zip");
			DataUpload.zipDirectory(dir, new FileOutputStream( tmpZip ));
			ZipFile zf = new ZipFile( tmpZip );
			Enumeration<? extends ZipEntry> e =  zf.entries();
			int count = 0;
			while( e.hasMoreElements()) {
				e.nextElement();
				count++;
			}
			assertTrue( count>1 );
			log.info( "ZipDir has " + count + " elements" );
			tmpZip.delete();
		} catch( Exception e ) {
			log.error( "Something failed in zipping Dir.", e );
			assertTrue( false );
		}
	}
	
	public void testUpload() {
		User u = DB.getUserDAO().findById(1001l, false);
		
		
	}
	
	
	
	private File getADir() {
		String path = System.getProperty( "java.class.path" );
		File result = null;
		for( String lib: path.split(";") ) {
			if( !lib.endsWith(".jar" )) {
				result = new File( lib );
				if( result.isDirectory())
					break;
			}
		}
		log.debug( "Getting dir "+ result.toString() );
		return result;
	}
}
