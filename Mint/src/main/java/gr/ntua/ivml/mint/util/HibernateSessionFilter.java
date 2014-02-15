package gr.ntua.ivml.mint.util;

import gr.ntua.ivml.mint.db.DB;

import java.io.IOException;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

import javax.servlet.Filter;
import javax.servlet.FilterChain;
import javax.servlet.FilterConfig;
import javax.servlet.ServletException;
import javax.servlet.ServletRequest;
import javax.servlet.ServletResponse;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;

import org.apache.log4j.Logger;
import org.hibernate.Session;
import org.hibernate.Transaction;

import com.mchange.v2.c3p0.C3P0Registry;
import com.mchange.v2.c3p0.PooledDataSource;

public class HibernateSessionFilter implements Filter {

	static Logger log = Logger.getLogger(HibernateSessionFilter.class);
	static List<String> excludeURLs = new ArrayList<String>();
	static {
		excludeURLs.add( "/images" );
		excludeURLs.add( "/css" );
		excludeURLs.add( "/js" );
		excludeURLs.add( "/custom" );
	}
	public void destroy() {
		// TODO Auto-generated method stub

	}

	static PooledDataSource pds;
	
	private FilterConfig filterConfig;
	 
	  public void doFilter(ServletRequest req,
	             ServletResponse resp,
	             FilterChain chain) throws ServletException, IOException {

		  long start = System.currentTimeMillis();
		  HttpServletRequest request = (HttpServletRequest) req;
		  HttpServletResponse response = (HttpServletResponse) resp;
		  HttpSession session = request.getSession(true);

		  if( excluded( request.getServletPath())) {
			  chain.doFilter(request, response );
			  return;
		  }
		  
		  Config.setContext(session.getServletContext());
  
		  
		  Session s;
		  s = DB.getSession();
		  String uri = request.getRequestURI();
		  log.info( "Hibernate session " + uri );
		  logConnections();
		  Transaction tx = s.beginTransaction();
		  // keeps a Hibernate session for the thread,
		  // I think hibernate can do it itself .. but I dont know the exact semantics
		  // this keeps a transaction open as well
		  try {
			  chain.doFilter (request, response);
			  // there could be a new session open ...
			  s = DB.getSession();
			  if( s.getTransaction().isActive()) {
				  s.flush();
				  s.getTransaction().commit();
			  } else {
				  log.debug( "No Transaction is active, shouldnt happen!");				  
			  }
		  }
		  catch(Exception e) {
			  // all exceptions and transactions are handled in 
			  // application.
			  // The default transaction should only contain uncomitted reads
			  log.error( "HibernateSessionFilter caught exception!", e );
			  // handle!
		  }
		  finally {
			  s.close();
			  DB.removeSession();
			  DB.closeStatelessSession();
			  logConnections();
			  log.info( "Served " + uri + " in " + ( System.currentTimeMillis()-start) + "msecs.");
		  }
	  }
	 
	  private boolean excluded( String url ) {
		  for( String urlStart: excludeURLs ) {
			  if( url.startsWith(urlStart)) {
				  log.debug( "No Hibernate for " + url );
				  return true;
			  }
		  }
		  return false;
	  }
	  
	  
	  public FilterConfig getFilterConfig()
	  {
	    return this.filterConfig;
	  }
	 
	  public void init(FilterConfig filterConfig)
	  {
	    this.filterConfig = filterConfig;
	  }
	  
	  private void logConnections() {
		  try {
			  getDataSource();
			  log.debug( "Connections in use " + pds.getNumBusyConnectionsDefaultUser());
			  log.debug( "Idle connections " + pds.getNumIdleConnectionsDefaultUser());
			  log.debug( "All connections " + pds.getNumConnectionsDefaultUser());

		  } catch( Exception e ) {
			  log.error( "Couldnt log connection status", e);
		  }
	  }
	  
	  private PooledDataSource getDataSource() {
		  if( pds == null ) {
			  try {
				  pds = (PooledDataSource) C3P0Registry.getPooledDataSources().iterator().next();
			  } catch( Exception e ) {
				  log.error( "Couldn't get the PooledDataSource",e);
			  }
		  }
		  return pds;
	  }
}
