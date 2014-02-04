package gr.ntua.ivml.mint.test;

import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.persistent.DataUpload;
import gr.ntua.ivml.mint.persistent.Organization;
import gr.ntua.ivml.mint.persistent.Publication;

import java.util.List;

import junit.framework.TestCase;

import org.apache.log4j.Logger;

public class PublicationTest extends TestCase {
	public static final Logger log = Logger.getLogger(PublicationTest.class);
	
	public void testDb() {
		// this is not needed in the webapp, automatic ..
		log.info( "Check yourself if this works, no asserts in here" );
		DB.getSession().beginTransaction();
		
		Publication p = new Publication();
		Organization org = getOrg();
		p.setPublishingOrganization(org);
		p.setTargetSchema("some_lido");
		for( DataUpload du: org.getDataUploads()) {
			p.addUpload(du);
		}
		DB.getPublicationDAO().makePersistent(p);
		// you don't need this in the webapp, happens automatically
		
		p = new Publication();
		p.setPublishingOrganization(org);
		p.setTargetSchema("some_other_schema");
		for( DataUpload du: org.getDataUploads()) {
			p.addUpload(du);
		}
		DB.getPublicationDAO().makePersistent(p);
		DB.commit();
		DB.closeSession();
	}

	public void testRet() {
		Publication res = null;
		DB.getSession().beginTransaction();
		
		for( Publication p: DB.getPublicationDAO().findAll()) {
			try {
				//p.upToDateCheck();
				res = p;
			} catch( Exception e ) {
				log.info( e );
			}
		}
		
		res.process();
		DB.closeSession();
	}
	
	
	private Organization getOrg() {
		Organization result = null;
		for( Organization org: DB.getOrganizationDAO().findAll() ) {
			List<DataUpload> l = org.getDataUploads();
			if( l.size() >= 2 ) {
				result = org;
				break;
			}
		}
		return result;
	}
}
