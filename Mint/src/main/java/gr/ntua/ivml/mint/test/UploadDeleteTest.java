package gr.ntua.ivml.mint.test;

import java.io.File;

import gr.ntua.ivml.mint.concurrent.Queues;
import gr.ntua.ivml.mint.concurrent.UploadIndexer;
import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.persistent.DataUpload;
import gr.ntua.ivml.mint.persistent.User;
import gr.ntua.ivml.mint.util.Config;
import junit.framework.TestCase;

public class UploadDeleteTest extends TestCase {

	// test delete a finished upload
	// sometimes it deletes dependents and sometimes it doesn't, sigh
	public void testFinished() {
		DB.getSession().beginTransaction();
		UploadIndexer ui = startUpload();
		Queues.join(ui);
		Queues.join(ui);
		DB.commit();
		DB.getSession().clear();
		DB.getSession().beginTransaction();

		DataUpload du = DB.getDataUploadDAO().findById(ui.getDataUpload().getDbID(), false);

		boolean result = DB.getDataUploadDAO().makeTransient(du);
		DB.commit();
		assertTrue( "makeTransient returned false", result );
		DB.getSession().clear();
		DataUpload du2 = DB.getDataUploadDAO().getById(du.getDbID(), false);
		assertNull( "DataUpload not deleted! " + du.getDbID(), du2 );
		DB.closeSession();
	}
	
	// remove the uploadindexer and clean db
	public void notestInterrupting() {
		DB.getSession().beginTransaction();
		UploadIndexer ui = startUpload();
		DB.getSession().beginTransaction();
		DataUpload du = DB.getDataUploadDAO().findById(ui.getDataUpload().getDbID(), false);
		boolean result = DB.getDataUploadDAO().makeTransient(du);
		DB.commit();
		assertTrue( "makeTransient returned false", result );
		DB.getSession().clear();
		DataUpload du2 = DB.getDataUploadDAO().getById(du.getDbID(), false);
		assertNull( "DataUpload not deleted! " + du.getDbID(), du2 );

		// now with some delay
		ui = startUpload();
		DB.getSession().beginTransaction();

		du = DB.getDataUploadDAO().findById(ui.getDataUpload().getDbID(), false);
		try {
			Thread.sleep( 3000 );
		} catch(Exception e ) {}
		result = DB.getDataUploadDAO().makeTransient(du);
		DB.commit();
		assertTrue( "makeTransient returned false", result );
		DB.getSession().clear();
		du2 = DB.getDataUploadDAO().getById(du.getDbID(), false);
		assertNull( "DataUpload not deleted! " + du.getDbID(), du2 );
		DB.closeSession();
	}
	
	
	private UploadIndexer startUpload() {
		// use an appropriate zip file
		String zipFilename = Config.get( "testZip");
		assertNotNull("testZip needs configuration", zipFilename);
		assertTrue( zipFilename + " not readable", new File( zipFilename ).canRead());
		// Use a test user
		User u = DB.getUserDAO().findById(1000l, false);
		
		// create a data upload
		DataUpload du = DataUpload.create( u, "example.zip", "" );
		DB.getDataUploadDAO().makePersistent(du);
		DB.commit();
		// use the upload indexer to put it in the database
		UploadIndexer ui = new UploadIndexer(du, UploadIndexer.SERVERFILE);
		ui.setServerFile(zipFilename);
		Queues.queue(ui, "net");
		return ui;
	}
}
