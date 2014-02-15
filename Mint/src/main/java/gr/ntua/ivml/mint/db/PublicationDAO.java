package gr.ntua.ivml.mint.db;


import gr.ntua.ivml.mint.persistent.Organization;
import gr.ntua.ivml.mint.persistent.Publication;

import java.util.List;

import org.apache.log4j.Logger;
import org.hibernate.Session;

public class PublicationDAO extends DAO<Publication, Long> {
	public static final Logger log = Logger.getLogger( PublicationDAO.class );
	
	static {
		Session ss = DB.getSession();
		try {
			ss.beginTransaction();
			List<Publication> l = ss.createQuery( "from Publication where statusCode != :ok and statusCode != :err")
			.setInteger("ok", Publication.OK)
			.setInteger("err", Publication.ERROR)
			.list();
			int count = 0;
			for(Publication p: l ) {
				p.setStatusCode(Publication.ERROR);
				p.setStatusMessage("Failed due to server restart!");
				p.getInputUploads().clear();
				count+=1;
				DB.commit();
			}
			log.info( "Failed " + count + " Publications due to restart.");
		} catch( Exception e ) {
			log.error( "Exception in Publication failing", e );
		} finally {
			DB.closeSession();
		}
	}

	public Publication findByOrganization( Organization org ) {
		return (Publication) getSession().createQuery( "from Publication where publishingOrganization=:org")
			.setEntity("org", org)
			.uniqueResult();
	}
	 
}
