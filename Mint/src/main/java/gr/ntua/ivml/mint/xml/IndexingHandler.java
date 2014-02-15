package gr.ntua.ivml.mint.xml;

import gr.ntua.ivml.mint.xml.util.ElementValueMap;

import java.util.ArrayList;
import java.util.HashMap;

import org.xml.sax.SAXException;

public class IndexingHandler extends Handler{
	
	private ArrayList<ElementValueMap> res;
	
	public IndexingHandler(boolean t, ArrayList<ElementValueMap> res){
		super(t);
		this.res = res;
	}
	
	@Override
    public void attribute(String uri, String name, String qname, String value) {       
            cursor = cursor.descend(uri, name, qname, 0);
            res.add(new ElementValueMap(this.cursor.getPath(), value));
            cursor = cursor.ascend();    
    }

    public void endElement(String uri, String name, String qname) throws SAXException {
        String value = getText();
        if(value.compareTo("") != 0){
        	res.add(new ElementValueMap(this.cursor.getPath(), value));
        }
        cursor = cursor.ascend();
    }
}
