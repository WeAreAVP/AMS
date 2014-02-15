package gr.ntua.ivml.mint.test;

import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.persistent.DataUpload;
import gr.ntua.ivml.mint.persistent.Mapping;
import gr.ntua.ivml.mint.persistent.Transformation;
import gr.ntua.ivml.mint.persistent.User;

import java.io.IOException;
import java.io.Reader;
import java.util.Date;
import java.util.List;

import junit.framework.TestCase;

public class TransformationTest extends TestCase {

	
	public void testHowToMakeOne() {
		// session and transaction stuff 
		// automatic in web container!
		DB.getSession().beginTransaction();
		// stuff starts here
		
		Transformation t = makeOne();
		
		Transformation t2 = DB.getTransformationDAO().makePersistent(t);
		Long oldDbID = t2.getDbID();
		
		// now check if its in the DB, commit done by web environment
		DB.commit();
		DB.getSession().clear();
		
		List<Transformation> l = DB.getTransformationDAO().findAll();
		// well, should check that its the one I put there :-)
		int size1 = l.size();
		assertTrue( "Transformation not stored!", l.size()>0);
		// should remove this one
		t2 = DB.getTransformationDAO().findById(oldDbID, false);
		DB.getTransformationDAO().makeTransient(t2);
		DB.commit();
		l = DB.getTransformationDAO().findAll();
		assertTrue( l.size()+1 == size1 );
		
	}

	/**
	 * General approach, get the Transformation
	 * do startOutput(), many appendOutput() and finishOutput()
	 * commit() it (happens automatically on the web)
	 * get the stuff back by
	 * getting the transformation.
	 * get the Reader with t.getOutput()
	 * read chars from it to your liking.
	 * 
	 * The DB holds gzipped UTF8 encoded stuff, but the API is all
	 * char in, char out, you shouldn't need to care how its stored
	 * 
	 */
	public void testStoreOutput() {
		// session and transaction stuff 
		// automatic in web container!
		DB.getSession().beginTransaction();

		Transformation t = makeOne();
		Transformation t2 = DB.getTransformationDAO().makePersistent(t);
		Long id = t2.getDbID();
		StringBuffer outputSafe = new StringBuffer();
		t2.startOutput();
		// do some output
		for( int i=0; i<10; i++ ) {
			String out = randomText( 1000 );
			outputSafe.append( out );
			t2.appendOutput(out);
		}
		t2.finishOutput();
		// just make sure the DB commits!
		DB.commit();
		
		// Now clear and test if it worked
		DB.getSession().clear();
		t2 = DB.getTransformationDAO().getById(id, false);
		// no more output reader, its a byte stream and many files in a zip
		/*
		Reader r = t2.getOutput();
		int c;
		int pos = 0;
		try {
			boolean equal = true;
			while((c = r.read()) >=0 ) {
				char cc = (char) c ;
				equal = ( equal && ( cc == outputSafe.charAt( pos )));
				pos += 1;
			}
			assertEquals( "Not right output size", 10000, pos );
			
			assertTrue( "Input Output in transformation not equal", equal );
			r.close();
			t2.clearTmpFile();
		} catch( IOException ie ) {
			assertTrue( "Damn IO problem", false );
		}
		*/
	}
	
	private DataUpload getTestUpload() {
		List<DataUpload> l = DB.getDataUploadDAO().findAll();
		assertTrue( "No test upload found", l.size() > 0 );
		return l.get(0);
	}
	
	private Transformation makeOne() {
		// need a user
		User u = DB.getUserDAO().getById(1l,false);
		// an upload 
		DataUpload du = getTestUpload();
		
		// the mapping used in the transform
		Mapping m = DB.getMappingDAO().getById(1l, false);
		
		Transformation t = new Transformation();
		t.setBeginTransform(new Date());
		t.setUser(u);
		t.setDataUpload(du);
		t.setMapping(m);
		
		return t;
	}
	
	private String randomText( int length ) {
		StringBuffer sb = new StringBuffer();
		for( int i=0; i<length; i++ ) {
			sb.append( randomChar() );
		}
		return sb.toString();
	}
	
	private char randomChar() {
		int num = (int) Math.floor( Math.random()*60 );
		if( num < 26 ) return (char)((int)'a'+num);
		num-=26;
		if( num < 26 ) return (char)((int)'A'+num);
		num -= 26;
		if( num < 1) return '\n';
		return ' ';
	}
}
