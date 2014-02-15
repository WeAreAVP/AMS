package gr.ntua.ivml.mint.db;

import java.util.List;

import gr.ntua.ivml.mint.persistent.DataUpload;
import gr.ntua.ivml.mint.persistent.Organization;
import gr.ntua.ivml.mint.persistent.Thesaurus;
import gr.ntua.ivml.mint.persistent.ThesaurusAssignment;
import gr.ntua.ivml.mint.persistent.XMLNode;
import gr.ntua.ivml.mint.persistent.XpathHolder;

import org.apache.log4j.Logger;
import org.hibernate.Criteria;

public class ThesaurusDAO extends DAO<Thesaurus, Long> {

	public static final Logger log = Logger.getLogger( ThesaurusDAO.class );
	
	public List<Thesaurus> findByOrganization( Organization o) {
		List<Thesaurus> l = getSession().createQuery( "from Thesaurus where organization = :org order by title ASC" )
			.setEntity("org", o)
			.list();
		return l;
	}
	
	
	//Should try to optimize this...
	public List<Thesaurus> findByRootOrganization( Organization o) {
		Organization root = o;
		while(root.getParentalOrganization() != null) {
			root.getParentalOrganization();
		}
		List<Thesaurus> result;
		return findByOrganizationAndDependants(root);
	}
	
	public List<Thesaurus> findByOrganizationAndDependants(Organization o) {
		List<Thesaurus> result;
		result = findByOrganization(o);
		List<Organization> deps = o.getDependantRecursive();
		for(Organization org: deps) {
			result.addAll(findByOrganization(org));
		}
		
		return result;
	}
	
	public List findDistinctXpathsByThesaurusId(Thesaurus t) {
		return getSession().createQuery("select distinct ta.xpath.xpath from ThesaurusAssignment ta where ta.thesaurus=:t")
		.setEntity("t",t)
		.list();
	}

	public boolean delete(Long id) {
		Thesaurus ta = findById(id, false);
		if(ta == null)
			return false;
		
		getSession().delete(ta);
		return true;
	}
}
