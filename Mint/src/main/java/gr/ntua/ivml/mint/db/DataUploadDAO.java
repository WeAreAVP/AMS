package gr.ntua.ivml.mint.db;

import gr.ntua.ivml.mint.concurrent.Queues;
import gr.ntua.ivml.mint.persistent.DataUpload;
import gr.ntua.ivml.mint.persistent.Organization;
import gr.ntua.ivml.mint.persistent.Transformation;
import gr.ntua.ivml.mint.persistent.User;

import java.util.List;

import org.apache.log4j.Logger;
import org.hibernate.Session;

public class DataUploadDAO extends DAO<DataUpload, Long> {
	public static final Logger log = Logger.getLogger(DataUploadDAO.class);

	static {
		Session ss = DB.getSession();
		ss.beginTransaction();
		try {
			List<DataUpload> toChange = ss.createQuery("from DataUpload where status != :ok "+
					"and status != :error and status != :dummy" )
			.setInteger("ok", DataUpload.OK)
			.setInteger("error", DataUpload.ERROR)
			.setInteger("dummy", DataUpload.DUMMY)
			.list();

			int count = 0;
			try {
				for( DataUpload du: toChange ) {
					du.setMessage("Failed due to server restart!");
					du.setStatus(du.ERROR);
					DB.commit();
					count++;
				}
			} catch( Exception e ) {
				log.error( "Error after " + count + " cancelled Uploads", e );
			}
			log.info( "Cancelled " + count + " Uploads from DB.");
		} finally {
			ss.close();
		}
	}
	

	
	public List<DataUpload> findByOrganizationUser( Organization o, User u ) {
		List<DataUpload> l = getSession().createQuery( "from DataUpload where organization = :org and  uploader = :user order by uploadDate DESC" )
			.setEntity("org", o)
			.setEntity("user", u)
			.list();
		return l;
	}
	
	public List<DataUpload> findByOrganization( Organization o) {
		List<DataUpload> l = getSession().createQuery( "from DataUpload where organization = :org order by uploadDate DESC" )
			.setEntity("org", o)
			.list();
		return l;
	}
	
	public List<User> getUploaders( Organization o ) {
		List<User> l = getSession().createQuery( "select distinct(ul) from DataUpload du join du.uploader ul where du.organization = :org" )
		.setEntity("org", o)
		.list();
	return l;		
	}

	public List<DataUpload> getByUser( User u ) {
		List<DataUpload> l = getSession().createQuery( "from DataUpload where uploader = :user order by uploadDate DESC" )
			.setEntity("user", u)
			.list();
		return l;
	}
	
	@Override
	public boolean makeTransient( DataUpload du ) {
		// interrupt a running Upload Indexer

		Queues.cancelUpload(du);
		DB.getSession().refresh(du);
		log.info( "Last message: " + du.getMessage() );
		// Delete contained transformations 
		if( du.getTransformations() != null ) {
			for( Transformation tr: du.getTransformations()) {
				DB.getSession().delete(tr);
			}
		}
		return super.makeTransient(du);
	}

	public List<DataUpload> getByUserOrg(User user, Organization org) {
		List<DataUpload> l = getSession().createQuery( "from DataUpload where uploader = :user and organization = :org order by uploadDate DESC" )
		.setEntity("user", user)
		.setEntity("org", org)
		.list();
	return l;
	}
}
