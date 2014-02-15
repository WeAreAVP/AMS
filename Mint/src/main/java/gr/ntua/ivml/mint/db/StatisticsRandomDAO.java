/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package gr.ntua.ivml.mint.db;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.LinkedHashMap;
import java.util.List;

import java.util.Random;

public class StatisticsRandomDAO {
	public StatisticsRandomDAO () {
		System.out.println("StatisticsRandom DAO init");
	}
	
	/* This method should return a list of the available namespaces in
	 * the upload together with the appropriate prefixes. The key of the
	 * hashmap represents the prefix and the value is the uri of the namespace
	 * e.g. key: oai value: http://openarchives.org/oai. If the xml is correct
	 * all the pairs of prefixes & namespace uris will be unique so the resulted
	 * hashmap will be consistent.
	 */
	public LinkedHashMap<String, String> getNameSpaces(){
        LinkedHashMap<String, String> res = new LinkedHashMap<String, String>();
        res.put("oai", "http://www.openarchives.org/oai");
        res.put("oai_dc", "http://www.openarchives.org/oai");
        res.put("museumdat", "http://www.museumdat.org/museumdat");
        return res;
	}

	/* This method will return a list of the available elements for a specific
	 * namespace, the parameter should be the namespace prefix although this
	 * can change to the uri namespace if needed, the namespace is just shorter.
	 */
	public List<String> getElements(String namespacePrefix){
		ArrayList<String> res = new ArrayList<String>();
        if(namespacePrefix.compareTo("oai") == 0){
            for(int counter = 0; counter < 15; counter++){
                res.add("element_oai_"+counter);
            }
        }else if(namespacePrefix.compareTo("oai_dc") == 0){
            for(int counter = 0; counter < 15; counter++){
                res.add("element_oaiDc_"+counter);
            }
        }else if(namespacePrefix.compareTo("museumdat") == 0){
            for(int counter = 0; counter < 15; counter++){
                res.add("element_museumDat_"+counter);
            }
        }
        return res;
	}

	/*This method returns a list with the names of the attributes of a single
	 * element.
	 */
	public List<String> getAttributes(String elementName){
		ArrayList<String> res = new ArrayList<String>();
        res.add("   attr1");
        return res;
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
	 */
	public long[] getElementAttrFreqs(String elementName){
        long[] res = new long[2];
        Random randomGenerator = new Random();
        Random randomGenerator1 = new Random();
        
        int dice = randomGenerator1.nextInt(5);
        if(dice == 0){
            res[0] = res[1] = 0;
        }else if(dice <= 2){
            res[0] = res[1] = randomGenerator.nextInt(1000);
        }else{
            res[0] = randomGenerator.nextInt(1000);
            res[1] = randomGenerator.nextInt(1000);
        }
        //res[0] = 100;
        //res[1] = 50;
        return res;
	}

	/* This method returns the set of possible values an element/attribute
	 * has together with the frequency of it. It should return a hashmap where
	 * the key is the value of the element/attribute and the value attached to it,
	 * the frequency it has. The parameter of the method is the element/attribute
	 * name. We should make sure that only sensible data are returned, for example
	 * in cases like a description element which has a lot of free text this statistic
	 * has no meaning.
	 */
	public HashMap<String, Integer> getElementValues(String elementName){
        HashMap<String, Integer> res = new HashMap<String, Integer>();
        res.put("en", 5);
        res.put("us", 4);
        res.put("gr", 112);
        res.put("de", 35);
        res.put("fr", 117);
        res.put("en_us", 2);
        res.put("tr", 16);
        res.put("es", 5);
        res.put("usd", 4);
        res.put("gre", 112);
        res.put("dee", 35);
        res.put("fra", 117);
        res.put("en_usa", 2);
        res.put("tre", 16);
        res.put("trw", 16);
        res.put("esd", 5);
        res.put("usda", 4);
        res.put("gref", 112);
        res.put("deeg", 35);
        res.put("frad", 117);
        res.put("en_usa", 2);
        res.put("tre", 16);
        return res;
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
