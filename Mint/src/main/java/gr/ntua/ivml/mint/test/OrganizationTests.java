package gr.ntua.ivml.mint.test;

import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.persistent.Organization;
import gr.ntua.ivml.mint.persistent.User;

import java.util.List;

import junit.framework.TestCase;

import org.apache.log4j.Logger;
import org.hibernate.Transaction;

public class OrganizationTests extends TestCase {
	public static final Logger log = Logger.getLogger(OrganizationTests.class);
	
	public void notestSave() {
		Organization o1, o2, o3;
		o1 = new Organization();
		o1.setName( "Arne" );
		o2 = new Organization();
		o2.setName("Marlene");
		o3 = new Organization();
		o3.setName("Iolie");
		o2.setParentalOrganization(o1);
		o3.setParentalOrganization(o1);
		Transaction t = DB.getSession().beginTransaction();
		DB.getOrganizationDAO().makePersistent(o1);
		DB.getOrganizationDAO().makePersistent(o2);
		DB.getOrganizationDAO().makePersistent(o3);
		t.commit();
		DB.getSession().flush();
		DB.getSession().clear();
		DB.newSession();
		Organization o = DB.getOrganizationDAO().findByName("Arne");
		assertNotNull(o);
		List<Organization> l = o.getDependantOrganizations();
		
		assertEquals( 2, l.size());
		o1 = l.get(0);
		assertTrue( o1.getName().equals("Marlene")|| o1.getName().equals("Iolie"));
		DB.getOrganizationDAO().makeTransient(o);
	}
	
	public void testFindPrimary() {
		List<Organization> l = DB.getOrganizationDAO().findPrimary();
		for( Organization o: l ) {
			log.info( "Name: " + o.getName() );
		}
	}
	
	public void testGetUsers() {
		Organization o = DB.getOrganizationDAO().findById(1l, false);
		
		List<User> l = o.getUsers();
		assertTrue( l.size() == 2 );
		o = DB.getOrganizationDAO().findById(4l, false);
		l = o.getUsers();
		assertTrue( l.size() == 1 );
	}
}
