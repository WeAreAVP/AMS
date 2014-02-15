package gr.ntua.ivml.mint.test;

import gr.ntua.ivml.mint.concurrent.Queues;
import gr.ntua.ivml.mint.concurrent.UploadIndexer;
import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.db.GlobalPrefixStore;
import gr.ntua.ivml.mint.persistent.DataUpload;
import gr.ntua.ivml.mint.persistent.User;
import gr.ntua.ivml.mint.persistent.XmlObject;
import gr.ntua.ivml.mint.persistent.XpathHolder;
import gr.ntua.ivml.mint.util.Config;

import java.io.File;

import org.hibernate.Transaction;

import junit.framework.TestCase;

public class TestUploadIndexer extends TestCase {
	public void notestUploadIndexer() {
		// use an appropriate zip file
		GlobalPrefixStore gps;
		
		String zipFilename = Config.get( "testZip");
		assertNotNull("testZip needs configuration", zipFilename);
		assertTrue( zipFilename + " not readable", new File( zipFilename ).canRead());
		// Use a test user
		User u = DB.getUserDAO().findById(1000l, false);
		
		// create a data upload
		DataUpload du = DataUpload.create( u, "example.zip", "" );
		du.setSchemaName( "SomeLidoClone");
		
		DB.getDataUploadDAO().makePersistent(du);
		
		// use the upload indexer to put it in the database
		UploadIndexer ui = new UploadIndexer(du, UploadIndexer.SERVERFILE);
		ui.setServerFile(zipFilename);
		Queues.queue(ui, "net" );
		Queues.join( ui );
		Queues.join( ui );
	}
	
	public void testNoCounts() {
		DB.newSession();
		Transaction tx = DB.getSession().beginTransaction();
		User u = DB.getUserDAO().findById(1000l, false);
		
		// create a data upload
		DataUpload du = DataUpload.create( u, "noTextCount.zip", "" );
		DB.getDataUploadDAO().makePersistent(du);
		DB.commit();
		// use the upload indexer to put it in the database
		UploadIndexer ui = new UploadIndexer(du, UploadIndexer.SERVERFILE);
		ui.setServerFile("WEB-INF/data/beethoven_i.zip");
		Queues.queue(ui, "net" );
		Queues.join( ui );
		Queues.join( ui );
	
		assertTrue( true );
		DB.getSession().refresh(du);
		XmlObject xml = du.getXmlObject();
		XpathHolder xp1 = xml.getRoot();
		XpathHolder xp2 = xp1.getByRelativePath("/Dokument/Bildnis/DocID");
		assertEquals( "Node expected to be there 10 times", 10, xp2.getCount() );
		xp2 = xp2.getByRelativePath("text()" );
		assertEquals( "Node expected to be there 10 times", 10, xp2.getCount() );
		DB.commit();
		DB.getDataUploadDAO().makeTransient(du);
		DB.closeSession();
	}
}
