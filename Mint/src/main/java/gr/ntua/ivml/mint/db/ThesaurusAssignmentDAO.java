package gr.ntua.ivml.mint.db;

import java.util.List;

import gr.ntua.ivml.mint.persistent.DataUpload;
import gr.ntua.ivml.mint.persistent.Thesaurus;
import gr.ntua.ivml.mint.persistent.ThesaurusAssignment;
import gr.ntua.ivml.mint.persistent.XpathHolder;

public class ThesaurusAssignmentDAO  extends DAO<ThesaurusAssignment, Long> {

	public List<ThesaurusAssignment> getByThesaurus(Thesaurus t) {
		return getSession().createQuery("from ThesaurusAssignment where thesaurus=:thesaurus")
		.setEntity("thesaurus", t)
		.list();
	}
	
	public List<ThesaurusAssignment> getByThesaurusAndDataUpload(Thesaurus t, DataUpload du) {
		return getSession().createQuery("from ThesaurusAssignment where thesaurus=:thesaurus and dataUpload=:du")
		.setEntity("thesaurus", t)
		.setEntity("du", du)
		.list();
	}
	
	/**
	 * Checks if there's an assign for the specified xpath and thesaurus that have been applied on mapping specified
	 * by the data upload.
	 * @param xpath the xpath to check if is assigned to a thesaurus
	 * @param t the thesaurus
	 * @param du the mapping
	 * @return true if exists, false if not
	 */
	public boolean existsAssignment(XpathHolder xpath, Thesaurus t, DataUpload du) {
		List<ThesaurusAssignment> list = getSession().createQuery("from ThesaurusAssignment where thesaurus=:thesaurus and dataUpload=:du and xpath=:xpath")
		.setEntity("thesaurus", t)
		.setEntity("du", du)
		.setEntity("xpath", xpath)
		.list();
		if((list == null) || (list.size() == 0))
			return false;
		
		return true;
	}
	
	public boolean delete(Long id) {
		ThesaurusAssignment ta = findById(id, false);
		if(ta == null)
			return false;
		
		getSession().delete(ta);
		return true;
	}
}
