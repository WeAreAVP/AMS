package gr.ntua.ivml.mint.test;

import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.db.MappingDAO;
import gr.ntua.ivml.mint.persistent.Mapping;
import gr.ntua.ivml.mint.persistent.Organization;

import java.util.Date;
import java.util.List;

import junit.framework.TestCase;

public class MappingTest extends TestCase {
	public void testDb() {
		MappingDAO md = DB.getMappingDAO();
		assertNotNull( md );
		// make a new Mapping
		Mapping m = new Mapping();
		Organization o1 = DB.getOrganizationDAO().getById(1l, false);
		m.setOrganization( o1 );
		m.setCreationDate(new Date());
		m.setName( "Some Name");
		md.makePersistent(m);
		DB.getSession().clear();
		List<Mapping> l = md.findByOrganization(o1);
		assertTrue( l.size() > 0  );
		for( Mapping ma: l ) {
			md.makeTransient(ma);
		}
		DB.getSession().clear();
		m = new Mapping();
		m.setCreationDate(new Date());
		m.setName( "Some Name");
		m.setOrganization(null);
		md.makePersistent(m);
		l = md.findByOrganization( null );
		assertTrue( l.size() > 0  );
		
	}
}
