package gr.ntua.ivml.mint.db;


import gr.ntua.ivml.mint.persistent.DataUpload;
import gr.ntua.ivml.mint.persistent.Transformation;
import gr.ntua.ivml.mint.persistent.XmlObject;

import java.util.List;

import org.apache.log4j.Logger;
import org.hibernate.Session;

public class TransformationDAO extends DAO<Transformation, Long> {
	public static final Logger log = Logger.getLogger(TransformationDAO.class);
	static {
		Session ss = DB.getSession();
		ss.beginTransaction();
		try {
		List<Transformation> l = ss.createQuery( "from Transformation where statusCode != :ok " + 
				"and statusCode != :err and statusCode != :dummy")
			.setInteger("ok", Transformation.OK)
			.setInteger("err", Transformation.ERROR)
			.setInteger("dummy", Transformation.DUMMY)
			.list();
		int count = 0;
		for( Transformation t: l ) {
			t.setStatusCode(t.ERROR);
			t.setStatusMessage("Failed due to server restart!");
			count+=1;
			DB.commit();
		}
		log.info( "Failed " + count + " Transformations due to restart.");
		} catch( Exception e ) {
			log.error( "Exception in Transformation failing", e );
		} finally {
			DB.closeSession();
		}
	}
	
	public List<Transformation> findByUpload( DataUpload du ) {
		return getSession().createQuery("from Transformation where dataUpload=:du")
		.setEntity("du", du)
		.list();
	}

	public Transformation findByXmlObject(XmlObject xo) {
		return (Transformation) getSession().createQuery( "from Transformation where parsedOutput = :xo ")
		.setEntity("xo", xo)
		.uniqueResult();
	}
	public Transformation findOneByUpload(DataUpload du) {
		return (Transformation) getSession().createQuery( "from Transformation where dataUpload = :du ")
		.setEntity("du", du)
		.uniqueResult();
	}
	
}
