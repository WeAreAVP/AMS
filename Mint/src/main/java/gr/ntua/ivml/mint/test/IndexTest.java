package gr.ntua.ivml.mint.test;

import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.db.DataUploadDAO;
import gr.ntua.ivml.mint.persistent.DataUpload;
import gr.ntua.ivml.mint.persistent.Organization;
import gr.ntua.ivml.mint.persistent.User;

import java.io.File;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;

import junit.framework.TestCase;

import org.apache.log4j.Logger;
import org.hibernate.StatelessSession;

public class IndexTest extends TestCase {
	Logger log = Logger.getLogger( IndexTest.class );
	
	public void notestload() {
		// super user
		try {
		User u = DB.getUserDAO().findById(1001l, false);
		DataUploadDAO dud = DB.getDataUploadDAO();
		File f = new File( "/Users/arne/Desktop/mets.zip" );
		DataUpload du = DataUpload.create( f, u, "mets.zip" );
		du.setStructuralFormat("xml");
		du.setAdminUpload(true);
		Organization o = DB.getOrganizationDAO().findById(1l, false );
		du.setOrganization(o);
		dud.makePersistent(du);
		
		} catch( Exception e ) {
			log.error( "Problems", e );
		}
	}
	
	public void notestIndex() {
		DataUploadDAO dud = DB.getDataUploadDAO();
//		DataUpload du = dud.simpleGet("noOfFiles<100 and noOfFiles>30");
//		DataUpload du = dud.simpleGet("noOfFiles>10000");
		DataUpload du = dud.simpleGet("noOfFiles>1000 and noOfFiles<50000");
	}
	
	public void notestLongQuery() throws SQLException {
		StatelessSession ss = DB.getStatelessSession();
		Connection c = ss.connection();
		PreparedStatement st = c.prepareStatement( "create table kill_test( num int)" );
		st.execute();
		st = c.prepareStatement( "insert into kill_test( num ) values( ? )" );
		int i;
		for( i=1; i<10000; i++ ) {
		  st.setInt( 1, i );
		  st.execute();
		}
		c.commit();

		st = c.prepareStatement( "select x1.num, x2.num, x3.num, x4.num from " +
				" kill_test x1, kill_test x2, kill_test x3, kill_test x4");
		st.setFetchSize(10);
		st.setFetchDirection(ResultSet.FETCH_FORWARD);
		ResultSet rs = st.executeQuery();
		i = 5000;
		int sum = 0;
		while( rs.next() && (i>0)) {
			i--;
			sum += rs.getInt(2);
		}
		st.cancel();
		st.close();
		rs.close();
		st = c.prepareStatement( "drop table kill_test" );
		st.execute();
		c.commit();
		ss.close();
	}
}

