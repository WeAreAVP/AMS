package gr.ntua.ivml.mint.util;

import gr.ntua.ivml.mint.db.DB;

import javax.servlet.http.HttpSessionEvent;
import javax.servlet.http.HttpSessionListener;

import org.apache.log4j.Logger;

public class SessionClose implements HttpSessionListener {
	Logger log = Logger.getLogger( SessionClose.class );
	public void sessionCreated(HttpSessionEvent arg0) {
	}

	public void sessionDestroyed(HttpSessionEvent event) {
		int count = DB.getLockManager().releaseLocks( event.getSession().getId());
		log.info( "Session contained " + count + " stale locks");
	}

}
