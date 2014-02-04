package gr.ntua.ivml.mint.xml.util;

import java.util.HashMap;
import java.util.Map;

public class Namespaces {

	 
    Map<String,String> namespaces;
    
    public Namespaces() {
        this.namespaces = new HashMap<String,String>();
    }
    
    public Namespaces(Map<String,String> n) {
        this.namespaces = n;
    }
    
    public String getNamespacePrefix(String uri, String qname, int type) {
        
        String prefix = XPathUtils.getNamespacePrefix(qname);
        if (!(type == 0 && "".equals(prefix))) {
            String namespaceURI = (String) namespaces.get(prefix);
            if (namespaceURI != null) {
                if (!namespaceURI.equals(uri)) {
                    int i = 0;
                    while (true) {
                        String newPrefix = prefix + i++;
                        namespaceURI = (String) namespaces.get(newPrefix);
                        if (namespaceURI == null) {
                            namespaces.put(newPrefix, uri);
                            prefix = newPrefix;
                            break;
                        } else if (namespaceURI.equals(uri)) {
                            prefix = newPrefix;
                            break;
                        }
                    }
                }
            } else {
                namespaces.put(prefix, uri);
            }
        }
        return prefix;
    }
}
