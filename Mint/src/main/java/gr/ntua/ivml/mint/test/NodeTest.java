package gr.ntua.ivml.mint.test;

import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.persistent.DataUpload;
import gr.ntua.ivml.mint.persistent.XMLNode;
import gr.ntua.ivml.mint.persistent.XpathHolder;

import java.io.PrintWriter;
import java.io.StringWriter;
import java.util.List;

import junit.framework.TestCase;

import org.apache.log4j.Logger;

public class NodeTest extends TestCase {
	Logger log = Logger.getLogger( NodeTest.class );
	
	public void testRetrieval() {
		XMLNode p = DB.getXMLNodeDAO().findById(341005l, false);
		XMLNode p2 = DB.getXMLNodeDAO().getDOMTree(p);
		
		// are p and p2 equivalent?
		assertEquals( p.getChildren().size(), p2.getChildren().size());
		for( int i=0; i<p2.getChildren().size(); i++ ) {
			assertEquals( p.getChildren().get( i ).getChecksum(), p2.getChildren().get(i).getChecksum());
		}
	}
	
	
	public void testGetChildrenByXpath() {
		DataUpload du = getTestUpload();
		XpathHolder xp = du.getRootXpath();
		List<XpathHolder> lxp = xp.getChildrenRecursive();
		XpathHolder l5=null;
		for(XpathHolder xp1: lxp ) {
			if( xp1.getDepth() > 5 ) {
				l5 = xp1;
				break;
			}
		}
		assertNotNull( l5 );
		List<? extends XMLNode> lxn = l5.getNodes(0, 100);
		assertTrue( "No results! Could happen I guess", lxn.size() > 0 );
		XMLNode xn = lxn.get(0);
		assertNotNull( "No example l5 node", xn);
		XMLNode parent = xn.getParentNode().getParentNode();
		lxn = parent.getChildrenByXpath(l5);
		// now get an example node
		assertTrue( "Should have result here.", lxn.size()>0);
		// and somehow the node should be in here too
		boolean found = false;
		for( XMLNode test: lxn ) {
			if( test.getNodeId() == xn.getNodeId()) found=true;
 		}
		assertTrue( "Child node not found back", found );
	}
	
	public void testNodeKey() {
		assertEquals( "b", XMLNode.num2key(1) );
		assertEquals( "xkd", XMLNode.num2key( 23+10*26+3));
		StringBuffer key = new StringBuffer();
		long mod = 26*26;
		mod *= mod;
		mod *= mod;
		
		for( int i=0; i<100; i++ ) {
			key.setLength(0);
			long someNum =(long) (Math.random()*mod);
			someNum = someNum%mod;
			key.append( XMLNode.num2key(someNum));
			String safe = key.toString();
			long newNum = XMLNode.popNum( key );
			assertTrue( key.length() == 0 );
			assertEquals( safe, someNum, newNum  );
		}
	}
	
	public void testWrappedXml() {
		DataUpload du = getTestUpload();
		XpathHolder xp = du.getItemXpath();
		log.debug( "Items: " + xp.getCount());
		assertTrue( xp.getCount() > 5 );
		XMLNode item = xp.getNodes(5, 1).get(0);
		StringWriter sw = new StringWriter();
		PrintWriter out = new PrintWriter( sw );
		item.toXml( out );
		log.debug( "XML\n" + sw.toString());
		sw = new StringWriter();
		out = new PrintWriter( sw );
		item.toXmlWrapped(out);
		log.debug( "Wrapped XML\n" + sw.toString());
	}
	
	private DataUpload getTestUpload() {
		List<DataUpload> l = DB.getDataUploadDAO().findAll();
		assertTrue( "No test upload found", l.size() > 0 );
		return l.get(0);
	}
	

}
