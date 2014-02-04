package gr.ntua.ivml.mint.xml;

import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.persistent.XmlObject;
import gr.ntua.ivml.mint.persistent.XpathHolder;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.HashSet;
import java.util.LinkedHashMap;
import java.util.List;
import java.util.Map;

import org.apache.log4j.Logger;

public class Statistics {
	public static final Logger log = Logger.getLogger(Statistics.class);
	
	public XmlObject xmlObject;
	public Statistics( XmlObject xml ) {
		this.xmlObject = xml;
	}
	
	/* This method should return a list of the available namespaces in
	 * the upload together with the appropriate prefixes. The key of the
	 * hashmap represents the prefix and the value is the uri of the namespace
	 * e.g. key: oai value: http://openarchives.org/oai. If the xml is correct
	 * all the pairs of prefixes & namespace uris will be unique so the resulted
	 * hashmap will be consistent.
	 * ARNE: As mentioned before, you loose all namespaces without prefixes
	 */

	public LinkedHashMap<String, String> getNameSpaces() {
		List<Object[]> l = DB.getXpathHolderDAO().listNamespaces(xmlObject);
        LinkedHashMap<String, String> res = new LinkedHashMap<String, String>();
        for( Object[] oa: l ) {
        	if( oa[0] != null && oa[0].toString().trim().length() != 0 )
        		res.put( oa[0].toString(), oa[1].toString());
        }
        return res;
	}

	/* This method will return a list of the available elements for a specific
	 * namespace, the parameter should be the namespace prefix although this
	 * can change to the uri namespace if needed, the namespace is just shorter.
	 * ARNE: namespace prefixes are not mandatory and thus namespaces without 
	 * prefixes cannot be listed here 
	 */
	public List<String> getElements(String namespacePrefix){
		return DB.getXpathHolderDAO().getElementsByNamespace(xmlObject, namespacePrefix);
	}

	
	/*This method returns a list with the names of the attributes of a single
	 * element.
	 * ARNE: It will merge attributes of elements that have the same name and
	 * do not belong to the same type.
	 */
	public List<String> getAttributes(String elementName){
		List<XpathHolder> l = DB.getXpathHolderDAO().getByName(xmlObject, elementName);
		HashSet<String> res = new HashSet<String>();
        for( XpathHolder xp: l ) {
        	for( XpathHolder attr: xp.getAttributes()) {
        		res.add( attr.getName());
        	}
        }
        List<String> l2 = new ArrayList<String>();
        l2.addAll(res);
        return l2;
	}

	/*This method returns the actual statistics per element/attribute for the
	 * first case of statistics. The array should have two distinct value, the
	 * first is the frequency for the element (how many times it occurs in the
	 * upload) and the second value should be the uniqueness of the values, how
	 * many distict unique values have been found for the set of all possible
	 * value the element has in this particular upload. If the usage of an array
	 * causes problems just change it to anything more appropriate. The parameter
	 * of the method is the element/attribute name as it is returned by the
	 * getElements() and getAttributes() methods.
	 * ARNE: This will only really work for attributes and elements that have only
	 * text and not subelements.  
	 */
	public long[] getElementAttrFreqs(String elementName){
				
        long[] res = new long[2];
 
		List<XpathHolder> l = DB.getXpathHolderDAO().getByName(xmlObject, elementName);
		for( XpathHolder xp: l ) {
			res[0] += xp.getCount();
		}
		res[1] = DB.getXMLNodeDAO().countDistinct(xmlObject, elementName);
		return res;
	}

	/* This method returns the set of possible values an element/attribute
	 * has together with the frequency of it. It should return a hashmap where
	 * the key is the value of the element/attribute and the value attached to it,
	 * the frequency it has. The parameter of the method is the element/attribute
	 * name. We should make sure that only sensible data are returned, for example
	 * in cases like a description element which has a lot of free text this statistic
	 * has no meaning.
	 * ARNE: this can return hundreds of thousands of values and blow the system
	 * the default is a limit to 1000 entries
	 */
	public Map<String, Integer> getElementValues(String elementName, int limit){
		return DB.getXMLNodeDAO().getCountByValue(xmlObject, elementName, limit);
	}

	public Map<String, Integer> getElementValues(String elementName){
		log.debug( "Element values for " + elementName + " on " + xmlObject.getDbID() );
		return DB.getXMLNodeDAO().getCountByValue(xmlObject, elementName,  1000 );
	}

    /*This method returns the median length of the values for a specific
	 * element/attribute. Parameter is the element/attribute name and
	 * returned value is a float representing the median length.
	 */

	public float getAverageLength(String elementName){
		return DB.getXMLNodeDAO().getAvgLength(xmlObject, elementName);
	}
	
	public float getMedianLength(String elementName) {
		float f = 0;
		
		try {
			f = DB.getXMLNodeDAO().getAvgLength(xmlObject, elementName);
		} catch(Exception e) {
			
		}
		return f;
	}

	/*This method returns the value distribution for a specific
	 * element/distribution. The key of the hashmap is arbitrary, used mainly for retrieval
	 * and the value associated with it is the occurences. For example
	 * if we have for a specific element 35 unique values and 100 occurences of
	 * the element then we might have one value appearing 10 times another one
	 * 5 times and the rest 20 values only once. The parameter of the method
	 * is the element name and the result is a hashmap with above mentioned
	 * key/values. This method is used for generating the sparklines.
	 * ARNE: I dont really get this one, if the keys are irrelevant, what are the
	 * values?
	 */

	public HashMap<Integer, Integer> getValueDistribution(String elementName){
		HashMap<Integer, Integer> res = new HashMap<Integer, Integer>();
        res.put(1, 20);
        res.put(2,34);
        res.put(3,5);
        res.put(4,43);
        res.put(5, 8);
        res.put(6, 12);
        res.put(7, 19);
        res.put(8, 32);
        res.put(9, 47);
        res.put(10, 29);
		return res;
	}
}
