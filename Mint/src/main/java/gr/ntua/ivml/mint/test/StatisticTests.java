package gr.ntua.ivml.mint.test;

import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.db.StatisticsDAO;
import gr.ntua.ivml.mint.persistent.DataUpload;
import gr.ntua.ivml.mint.persistent.XmlObject;
import gr.ntua.ivml.mint.persistent.XpathHolder;
import gr.ntua.ivml.mint.xml.Statistics;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.util.ArrayList;
import java.util.LinkedHashMap;
import java.util.List;
import java.util.Map;

import junit.framework.TestCase;

import org.apache.log4j.Logger;
import org.hibernate.StatelessSession;


public class StatisticTests extends TestCase {
	
	public static final Logger log = Logger.getLogger(StatisticTests.class);
	
	public void notestNameSpaces() {
		XmlObject xo = getExample();
		StatisticsDAO stats = new StatisticsDAO();
		
		Map<String, String> nameSpaces = stats.getNameSpaces(xo);
		assertTrue( nameSpaces.containsValue("mets"));
		assertTrue( nameSpaces.containsValue("xsi"));
		assertTrue( nameSpaces.size() == 5 );
	}
	
	public void notestElements() {
		XmlObject xo = getExample();
		StatisticsDAO stats = new StatisticsDAO();

		List<String> l = stats.getElements(xo, "http://www.w3.org/2001/XMLSchema-instance");
		assertTrue( l.size()==1 );
		l = stats.getElements( xo, "http://www.loc.gov/METS/" );
		assertTrue( l.size() == 17 );
		l = stats.getElementsPrefix( xo, "mets" );
		assertTrue( l.size() == 17 );	
	}
	
	public void notestXpathStats() {
		XmlObject xo = getExample();
		StatisticsDAO stats = new StatisticsDAO();

		XpathHolder xp = xo.getRoot().getByRelativePath("/OAI-PMH/GetRecord/record/metadata/mets");

		List<? extends XpathHolder> l = xp.getAttributes();
		assertTrue( l.size() == 3 );
		
		xp = xo.getRoot().getByRelativePath("/OAI-PMH/GetRecord/record/metadata/mets/dmdSec/@ID");
		log.debug( xp.getDistinctCount() + " " + xp.getCount());
		XpathHolder xp2 = xo.getRoot().getByRelativePathQuick("/OAI-PMH/GetRecord/record/metadata/mets/dmdSec/@ID");
		assertEquals( xp.getDbID(), xp2.getDbID());
		xp = xo.getRoot().getByRelativePath("/OAI-PMH/GetRecord/record/metadata/mets/dmdSec/mdWrap/xmlData/note/@type");
		List<Object[]> res  = xp.getCountByValue(100);
		assertEquals( res.size(), 4 );
	}
	

	public void testStatisticsObject() {
		XmlObject xo = getExample();
		Statistics s = xo.getStats();
		log.debug( "namespaces " + s.getNameSpaces());
		log.debug( "Elements for URIprefix" + s.getElements( "mets"));
		log.debug( "Attributes " + s.getAttributes("identifier"));
		log.debug( "ElemAttrFreq " + s.getElementAttrFreqs("@ID")[0] + " " + s.getElementAttrFreqs("@ID")[1]);
		log.debug( "Elem Values " + s.getElementValues("identifier"));
		log.debug( "Avg length " + s.getAverageLength("identifier"));
		
	}
	
	
	public void testSlowQueries() {
		XmlObject xo = DB.getXmlObjectDAO().getById( 1003l, false );
		List<XpathHolder> l  = DB.getXpathHolderDAO().getByUri(xo, "");
		for( XpathHolder xp: l ) {
    		long start = System.currentTimeMillis();
    		Long count = xp.getDistinctCount();
            log.debug( "Frequencies " + ( System.currentTimeMillis()-start ));    		
            start = System.currentTimeMillis();
            float len2 = xp.getAvgLength();
            log.debug( "AvgLen " + ( System.currentTimeMillis()-start ));
		}
		/*
		Statistics st = xo.getStats();
		LinkedHashMap<String, String> res = st.getNameSpaces();
	    for( String uri: res.keySet()) {
	    	List<String> elements = st.getElements(uri);
	    	for( String elem: elements ) {
	    		long start = System.currentTimeMillis();
                long[] res1 = st.getElementAttrFreqs(elem);
                log.debug( "Frequencies " + ( System.currentTimeMillis()-start ));
                start = System.currentTimeMillis();
                float len2 = st.getMedianLength(elem);
                log.debug( "Median " + ( System.currentTimeMillis()-start ));
	    	}
	    }
	    */
	}
	
	public void testAllStats() {
		XmlObject xo = DB.getXmlObjectDAO().getById( 1001l, false );
		Map<Long, Object[]> stats = DB.getXMLNodeDAO().getStatsForXpaths(xo);
		assertTrue( stats.size()>0);
		
		
	}
	public void testDirectJdbc() {
		StatelessSession ss = DB.getStatelessSession();
		Connection c = ss.connection();
		try {
			PreparedStatement  st1 = c.prepareStatement(
		    "select	xpathholde0_.name as col_0_0_ from xpath_summary xpathholde0_ " + 
		    "where xpathholde0_.xml_object_id=? " +  
	        " and xpathholde0_.uri=? " + 
	        " and xpathholde0_.name<>'text()' " +  
	        " and substring(xpathholde0_.name, 1, 1)<>'@' " + 
	     "group by " +
	        " xpathholde0_.name " );
			st1.setLong(1, 1003l);
			st1.setString( 2, "" );
			ResultSet rs = st1.executeQuery();
			List<String> elements = new ArrayList<String>();
			while( rs.next()) {
				elements.add( rs.getString(1));
			}
			
			for( int i=0; i<elements.size(); i++ ) {
				long start = System.currentTimeMillis();
				PreparedStatement st = c.prepareStatement("select " +
						"avg(length(xmlnode0_.content)) as col_0_0_ " +
						" from " + 
						" xml_node_master xmlnode0_, " +  
						" xpath_summary xpathholde1_, " +  
						" xpath_summary xpathholde2_  " + 
						" where   xmlnode0_.xpath_summary_id=xpathholde1_.xpath_summary_id  and xpathholde1_.parent_summary_id=xpathholde2_.xpath_summary_id " +  
						" and xmlnode0_.xml_object_id= 1003 " + 
						" and xpathholde2_.name= '"+ elements.get(i) + "'"  +
				" and xpathholde1_.name='text()'" );
				st.execute();
				log.debug( "Time passed " + (System.currentTimeMillis() - start ) );
			}
		} catch( Exception e ) {
			log.error( e );
		}

	}
	
	public XmlObject getExample() {
		DataUpload du = DB.getDataUploadDAO().simpleGet("originalFilename='example.zip'");
		assertNotNull( "DataUpload 'example.zip' not found, run test UploadIndexer to create it", du );
		return du.getXmlObject();
	}
	
}

