package gr.ntua.ivml.mint.xml;


import gr.ntua.ivml.mint.xml.util.XPathUtils;

import java.util.ArrayList;
import java.util.LinkedHashMap;

import org.xml.sax.SAXException;

public class UniqueXPathHandler extends Handler{
	
	//private LinkedHashMap<String, String> res;
	private ArrayList<Node> rootNodes;
	public UniqueXPathHandler(boolean t, ArrayList<Node> roots){
		super(t);
		this.rootNodes = roots;
	}
	
	@Override
    public void attribute(String uri, String name, String qname, String value) {       
            cursor = cursor.descend(uri, name, qname, 0);
            //res.put(this.cursor.getPath(), value);
            //res.add(new ElementValueMap(this.cursor.getPath(), value));
            cursor = cursor.ascend();    
    }

    public void endElement(String uri, String name, String qname) throws SAXException {
        String value = getText();
        //res.put(this.cursor.getPath(), value);
        if(XPathUtils.getDepth(this.cursor.getPath()) == 1){
        	this.rootNodes.add(this.cursor);
        	//System.out.println(this.cursor.getPath());
        }
        /*if(value.compareTo("") != 0){
        	res.add(new ElementValueMap(this.cursor.getPath(), value));
        }*/
        cursor = cursor.ascend();
    }
}
