package gr.ntua.ivml.mint.db;


import gr.ntua.ivml.mint.persistent.Organization;
import gr.ntua.ivml.mint.persistent.XmlObject;

import java.util.Collections;
import java.util.HashMap;
import java.util.List;

public class OrganizationDAO extends DAO<Organization, Long> {
	private HashMap<Long, Long> xmlToOrgId = new HashMap<Long,Long>();
	
	public List<Organization> findPrimary() {
		List<Organization> result = Collections.emptyList();
		try {
			result = getSession().createQuery(" from Organization where parentalOrganization is null" ).list();
		} catch( Exception e ) {
			log.error( "Problems: ", e );
		}
		return result;
	}
	
	public	Organization findByName( String name ) {
		Organization result = null;
		try {
			result = (Organization) getSession()
				.createQuery(" from Organization where shortName=:name" )
				.setString("name", name )
				.uniqueResult();
		} catch( Exception e ) {
			log.error( "Problems: ", e );
		}
		return result;
	}

	/**
	 * Which organization owns the XmlObject. Tricky, it can be in many places ...
	 * @param xo
	 * @return
	 */
	public Organization findByXmlObject( XmlObject xo ) {
		Organization org = null;
		if( xmlToOrgId.containsKey(xo.getDbID())) {
			org = getById( xmlToOrgId.get( xo.getDbID()), false );
		} else {
			org = (Organization) getSession()
				.createQuery( "select du.organization from DataUpload du where du.xmlObject=:xo" )
				.setEntity("xo", xo)
				.uniqueResult();
			if( org == null ) {
				org = (Organization) getSession()
				.createQuery( "select tr.dataUpload.organization from Transformation tr join tr.dataUpload where tr.parsedOutput=:xo" )
				.setEntity("xo", xo)
				.uniqueResult();				
			}
			if( org != null ) xmlToOrgId.put( xo.getDbID(), org.getDbID());
		}
		return org;
	}
	
	
	public List<Organization> findByCountry( String country ) {
		List<Organization> result = null;
		result = getSession()
			.createQuery("from Organization where country=:country " 
						+" order by englishName" )
			.setString("country", country ) 
			.list();
		return result;
	}
	
	public List<Organization> findAll() {
		List<Organization> result = null;
		result = getSession()
			.createQuery("from Organization " 
						+" order by englishName" )
			.list();
		return result;
	}
}
