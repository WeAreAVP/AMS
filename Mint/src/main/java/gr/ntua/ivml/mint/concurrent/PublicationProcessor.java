package gr.ntua.ivml.mint.concurrent;

import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.persistent.Publication;

/**
 * Simple wrapper to submit a publication process to a queue.
 * 
 * @author Arne Stabenau 
 *
 */
public class PublicationProcessor implements Runnable {

	Publication p;
	
	public PublicationProcessor( Publication p ) {
		this.p = p;
	}
	
	@Override
	public void run() {
		// new version of p from db
		DB.newSession();
		DB.getSession().beginTransaction();
		try {
		p = DB.getPublicationDAO().findById(p.getDbID(), false);
		p.process();
		} finally {
			DB.closeSession();
			DB.closeStatelessSession();
		}
	}

}
