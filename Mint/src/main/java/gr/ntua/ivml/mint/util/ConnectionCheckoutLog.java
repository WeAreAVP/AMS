package gr.ntua.ivml.mint.util;

import java.sql.Connection;

import org.apache.log4j.Logger;

import com.mchange.v2.c3p0.ConnectionTester;

public class ConnectionCheckoutLog implements ConnectionTester {
	public static final Logger log = Logger.getLogger( ConnectionCheckoutLog.class);
	
	@Override
	public int activeCheckConnection(Connection arg0) {
		// TODO Auto-generated method stub
		log.info( "Checkout occured");
		if( log.isDebugEnabled()) {
			Exception e= new Exception();
			e.fillInStackTrace();
			log.debug( "Trace\n" + StringUtils.filteredStackTrace(e, "gr.ntua.ivml.mint"));
		}
		return CONNECTION_IS_OKAY;
	}

	@Override
	public int statusOnException(Connection arg0, Throwable arg1) {
		// TODO Auto-generated method stub
		return CONNECTION_IS_INVALID;
	}

}
