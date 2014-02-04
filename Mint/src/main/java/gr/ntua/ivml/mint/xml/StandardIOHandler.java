package gr.ntua.ivml.mint.xml;

import org.xml.sax.SAXException;

public class StandardIOHandler extends Handler{

	
	public StandardIOHandler(boolean t){
		super(t);
	}
	
	@Override
    public void attribute(String uri, String name, String qname, String value) {       
            cursor = cursor.descend(uri, name, qname, 0);
            System.out.println(this.cursor.getPath() + ":"+value);
            cursor = cursor.ascend();    
    }

    public void endElement(String uri, String name, String qname) throws SAXException {
        String value = getText();
        if(value.compareTo("") != 0){
        	System.out.println(this.cursor.getPath() + ":"+value);
        }
        cursor = cursor.ascend();
    }
}
