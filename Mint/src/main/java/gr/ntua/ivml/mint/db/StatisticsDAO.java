package gr.ntua.ivml.mint.db;


import gr.ntua.ivml.mint.persistent.XmlObject;
import gr.ntua.ivml.mint.persistent.XpathHolder;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

import org.apache.log4j.Logger;


/* Statistics should be available per data upload*/

public class StatisticsDAO {
	
	public static final Logger log = Logger.getLogger( StatisticsDAO.class );
	/* This method should return a list of the available namespaces in
	 * the upload together with the appropriate prefixes. The key of the 
	 * hashmap represents the uri and the value is the prefix of the namespace
	 * e.g. key: http://openarchives.org/oai value: oai. If the xml is correct 
	 * all the pairs of prefixes & namespace uris will be unique so the resulted
	 * hashmap will be consistent.
	 */
	public HashMap<String, String> getNameSpaces( XmlObject xo ){
        HashMap<String, String> res = new HashMap<String, String>();
        List<Object[]> result = DB.getSession()
        	.createQuery( "select uriPrefix, uri " + 
        					"from XpathHolder " + 
        					"where xmlObject = :xo  " + 
        					"group by uriPrefix,uri" )
        	.setEntity("xo", xo)
        	.list();
        log.debug( result );
        for( Object[] row: result) {
        	if( row[1] == null ) continue;
        	if( row[0] == null ) continue;
        	log.debug( row[1].toString() + "->" + row[0].toString());
        	if( row[1].toString().trim().length() > 0)
        		res.put( (String) row[1], (String) row[0]);
        }
        return res;
	}
	
	/* This method will return a list of the available elements for a specific
	 * namespace, the parameter should be the namespace uri.
	 */
	public List<String> getElements(XmlObject xo, String uri){
        List<String> result = DB.getSession()
        	.createQuery( "select name from XpathHolder where xmlObject = :xo and uri = :uri")
    	.setEntity("xo", xo)
    	.setString( "uri", uri)
    	.list();

        return result;
	}
	
	
	/* This method will return a list of the available elements for a specific
	 * namespace, the parameter should be the namespace uri.
	 */
	public List<String> getElementsPrefix(XmlObject xo, String uriPrefix){
        List<String> result = DB.getSession()
        	.createQuery( "select name from XpathHolder where xmlObject = :xo and uriPrefix = :pre")
    	.setEntity("xo", xo)
    	.setString( "pre", uriPrefix)
    	.list();

        return result;
	}
	
	
	
	
	
	
	/*This method returns the median length of the values for a specific 
	 * element/attribute. Parameter is the element/attribute name and
	 * returned value is a float representing the median length.
	 */
	
	public float getMedianLenght(String elementName){
		float res = 5.4f;
		
		return res;
	}
	
	/*This method returns the value distribution for a specific 
	 * element/distribution. The key of the hashmap is arbitrary, used mainly for retrieval 
	 * and the value associated with it is the occurences. For example
	 * if we have for a specific element 35 unique values and 100 occurences of
	 * the element then we might have one value appearing 10 times another one
	 * 5 times and the rest 20 values only once. The parameter of the method
	 * is the element name and the result is a hashmap with above mentioned 
	 * key/values. This method is used for generating the sparklines.
	 */
	
	public HashMap<Integer, Integer> getValueDistribution(String elementName){
		HashMap<Integer, Integer> res = new HashMap<Integer, Integer>();
		
		return res;
	}
}

