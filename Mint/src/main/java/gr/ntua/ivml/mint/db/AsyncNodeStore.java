package gr.ntua.ivml.mint.db;

import gr.ntua.ivml.mint.persistent.ReportI;
import gr.ntua.ivml.mint.persistent.XMLNode;
import gr.ntua.ivml.mint.persistent.XmlObject;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.sql.Types;
import java.util.HashMap;
import java.util.Map;
import java.util.Map.Entry;
import java.util.concurrent.LinkedBlockingDeque;
import java.util.concurrent.TimeUnit;

import org.apache.log4j.Logger;
import org.hibernate.StatelessSession;

/**
 * This module asynchronously creates and loads the xml_node_nnnn tables.
 * nnnn is the database id of the xml object where the nodes belong.
 * 
 * This is done so the database feeding loop runs with best possible performance
 * independent of the node creating / parsing thread.
 * @author Arne Stabenau 
 *
 */
public class AsyncNodeStore implements Runnable {
	private Connection c;
	private PreparedStatement ps;
	private LinkedBlockingDeque<XMLNode> nodeBuffer = new LinkedBlockingDeque<XMLNode>( 1000 );
	private boolean finished = false;
	private boolean aborted = false;
	private long count = 0;
	private long lastCommit = 0;
	private long lastBatch = 0;
	private Thread t;
	private StatelessSession s;
	private XmlObject xml;
	public static final Logger log = Logger.getLogger( AsyncNodeStore.class );
	private Map<Long,long[]> xpathCount = new HashMap<Long, long[]>();
	
	/**
	 * Start a thread that stores nodes via given connection as they come.
	 * Commits stuff when finish is called, rolls back on abort. 
	 * @param c
	 */
	public AsyncNodeStore(XmlObject xml ) {
		this.xml = xml;
		t = new Thread( this );
		t.start();

	}
	
	/**
	* Inform the work loop that the job has to be aborted ...
	 */
	public void abort() {
		aborted = true;
		try {
			if( t!= null ) t.join();
		} catch( Exception e ) {
			log.error( "something interrupted the nodestore thread", e);
		}
	}
	
	public void finish() {
		finished = true;
		try {
			if( t!= null ) t.join();
		} catch( Exception e ) {
			log.error( "something interrupted the nodestore thread", e);
		}
	}

	/**
	 * Blocks if there is no space ...
	 * @param n
	 * @throws Exception
	 */
	public void store( XMLNode n ) throws Exception {
			if( t == null ) throw new Exception( "Store thread died ");
			nodeBuffer.putLast( n );
	}
	
	public void run() {
		// prepare the statement
		// loop, take nodes from the buffer and insert into db
		log.debug( "AsynStore started");
		// Thread.currentThread().setName("AsyncNodeStore["+xml.getDbID()+"]");
		this.s = DB.getStatelessSession();
		this.c = s.connection();
		try {
		c.setAutoCommit(false);
		} catch( SQLException se) {
			log.error( "Cannot set Connection to non auto commit", se); 
		}
		DB.logPid(c);
		try {
			createTable();
			c.commit();
			ps = c.prepareStatement("insert into xml_node_"+ xml.getDbID() +
					"( xml_node_id, parent_node_id, " + 
					"  xml_object_id, xpath_summary_id, node_type, content,  size, checksum ) values " + 
					"( ?,?, ?,?,?, ?,?,? )");
			
			while( !( aborted || (finished && nodeBuffer.isEmpty() ))) {
				XMLNode n = nodeBuffer.pollFirst( 2, TimeUnit.SECONDS );
				if( n != null ) {
					insertNode( n );
					if( count - lastBatch > 20 ) storeBatch();
					if(( count - lastCommit ) > 100000 ) {
						storeBatch();
						c.commit();
						lastCommit = count;
					}		
				}
			}
			if( aborted ) {
				log.debug( "AsynStore rollback");
				c.rollback();
				dropTable();
				c.commit();
			} else if( finished ){
				log.debug( "AsynStore commit");
				storeBatch();
				storeXpaths();
				c.commit();
			} else {
				log.info( "Hows that ???" );
			}
		} catch( Exception e ) {
			log.error( "Some desaster while node storing", e );
			// TODO: update DataUpload to error
			try { 
				c.rollback();
				dropTable(); 
				c.commit();
			} catch( Exception e2) {
				log.error( "Rollback failed", e2 );
			} 
		} finally {
			try {
				log.debug( "AsynNodeStore["+xml.getDbID()+"] freeing resources");
				c.commit();
				ps.close(); c.close(); s.close();
				log.debug( "AsynNodeStore["+xml.getDbID()+"] done!");
			} catch( Exception e ) {
				log.error( "Session not closed..", e );
			} 
		}
		log.debug( "AsynNodeStore["+xml.getDbID()+"] finished");
		t=null;
	}

	private void insertNode( XMLNode n ) throws Exception {
		int xpathId = getXpathId( n );
		
		ps.setLong(1, n.nodeId );
		if( n.parentNodeId == 0l ) 
			ps.setNull(2, Types.BIGINT);
		else
			ps.setLong(2, n.parentNodeId );
		ps.setInt(3, xml.getDbID().intValue());
		ps.setInt(4, xpathId );
		ps.setByte(5, n.nodeType );
		ps.setString( 6, n.content );
		ps.setLong( 7, n.size );
		ps.setString( 8, n.checksum );
		ps.addBatch();
		count++;
	}
	
	
	private void storeBatch() throws SQLException {
		ps.executeBatch();
		lastBatch = count;
	}
	
	
	private void storeXpaths( ) throws SQLException {
		PreparedStatement xps = c.prepareStatement("update xpath_summary set count=? where xpath_summary_id = ?" );
		for(Entry<Long,long[]> data : xpathCount.entrySet() ) {
			xps.setLong( 1, data.getValue()[0] );
			xps.setLong( 2, data.getKey().longValue());
			xps.execute();
			log.debug( "Setting xpath_summry_id="+data.getKey().longValue()+" count="+data.getValue()[0] );
		}
		xps.close();
	}
	
	private  void createTable() throws SQLException {
		doSQL( "create table xml_node_" + xml.getDbID() + "( CHECK ( xml_object_id =" +
				xml.getDbID() + " )) INHERITS (xml_node_master)");
	}
	
	
	private  void dropTable() throws SQLException {
		doSQL( "drop table if exists xml_node_" + xml.getDbID() );
		doSQL( "delete from xpath_summary where xml_object_id = " + xml.getDbID() );
	}
	
	private  void doSQL( String sql ) throws SQLException {
		Statement st;
		st = c.createStatement();
		st.executeUpdate( sql  );
		st.close();
	}

	private static void doSQL( Connection lc, String sql  ) throws SQLException {
		Statement st;
		st = lc.createStatement();
		st.executeUpdate( sql  );
		lc.commit();
		st.close();
	}

	private int getXpathId( XMLNode n ) throws SQLException {
		
		long[] data = xpathCount.get( n.getXpathHolder().getDbID() );
		if( data == null ) {
			data = new long[1];
			data[0] = 1l;
			xpathCount.put( n.getXpathHolder().getDbID(), data );
		} else {
			data[0]++;
		}
		return n.getXpathHolder().getDbID().intValue();
	}
	
	/**
	 * Allocating node ids in packs of 1000. The sequence will support this.
	 * Whoever has x000 can use ids x000 until x999.
	 * @return
	 */
	
	public static long[] getIds(Connection c) {
		long[] result = new long[2];
		ResultSet rs = null;
		PreparedStatement ps = null;
		try {
			ps = c.prepareStatement("select nextval('seq_xml_node_id')");
			rs = ps.executeQuery();
			if( rs.next() ) {
				result[0] = rs.getLong(1);
				result[1] = result[0]+999;
			} else {
				result[0] = -1l;
				result[1] = -1l;
			}
		} catch( Exception e ) {
			log.error( "No node ids", e );
			result[0] = -1l;
			result[1] = -1l;				
		} finally {
			try {
				if( rs != null ) rs.close();
				if( ps != null ) ps.close();
			} catch( Exception e2 ) {
				log.error( "Cannot close result set or statement", e2 );
			}
		}
		return result;
	}

	/**
	 * This module creates the appropriate indices on the xml_node_nnn table that it made.
	 * It reports about the progress on the reportI. 
	 * @param xml
	 * @param report
	 * @param c
	 * @throws Exception
	 */
	public static void index( XmlObject xml, ReportI report, Connection c ) throws Exception {
		String msg = "Doing nothing";
		
		try {
			String indexPre = "CREATE INDEX %n on xml_node_%i ";
			String constraintPre = "alter table xml_node_%i add CONSTRAINT ";
			String[] constraints = {
					"xml_node_%i_pkey PRIMARY KEY (xml_node_id)",
					"xml_node_%i_parent_node_id_fkey FOREIGN KEY (parent_node_id) " + 
					"  REFERENCES xml_node_%i(xml_node_id) MATCH SIMPLE " +
					" ON UPDATE NO ACTION ON DELETE NO ACTION "
			};
			
			String[] indicesNames = {
					"index_xpath_summary", "index_parent_node"
			};
			
			String[] indices = {
					"xpath_summary_id, xml_node_id", "parent_node_id, xml_node_id"
			};
			
			String[] constraintNames = { "primary key", "foreign key parent_node" };
			for( int i=0; i<constraints.length; i++ ) {
				Thread.sleep(0);
				String s = constraints[i];
				msg = "Creating constraint for " + constraintNames[i];
				report.report(msg);
				doSQL(c, (constraintPre + s ).replaceAll("%i", Long.toString( xml.getDbID())));
				log.info( msg + " on xml_node_" + xml.getDbID() + " is done.");
			}
			for( int i=0; i<indices.length; i++ ) {
				Thread.sleep(0);
				String s = indices[i];
				msg = "Creating index for " + indicesNames[i];
				report.report(msg);
				doSQL(c, (indexPre + "(" + s + ")" )
						.replaceAll("%i", Long.toString( xml.getDbID()))
						.replaceAll( "%n", indicesNames[i]+"_"+Long.toString( xml.getDbID())));
				log.info( msg + " on xml_node_" + xml.getDbID() + " is done.");
			}
			DB.commit();
		} catch( Exception e ) {
			log.error( "Indexing went wrong!", e );
			report.reportError();
			report.report("Error while " + msg);
		}
	}
	

 }
