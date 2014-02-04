package gr.ntua.ivml.mint.xml;

import gr.ntua.ivml.mint.util.TraversableI;
import gr.ntua.ivml.mint.xml.util.Namespaces;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

import org.xml.sax.Attributes;
import org.xml.sax.SAXException;
import org.xml.sax.SAXParseException;
import org.xml.sax.helpers.DefaultHandler;

public abstract class Handler extends DefaultHandler{
	 StringBuffer buffer = new StringBuffer();
	    Node cursor = new Node();
	    boolean trim;
	    Namespaces namespaces;
	    
	    public Handler() {}
	    
	    public Handler(boolean t) {
	        trim = t;
	        namespaces = new Namespaces();
	    }
	    
	    public Handler(boolean t, Namespaces n) {
	        trim = t;
	        namespaces = n;
	    }
	    
	    public class Node implements TraversableI {
	        protected HashMap<String,Node> children = new HashMap<String,Node>();
	        protected String uri;
	        protected String name;
	        protected String path;
	        protected int type;
	        protected Node parent;
	        
	        Node() {
	            this.parent = null;
	            this.path = "";
	            
	        }
	        
	        Node(String uri, String name, String qname, int type, Node par) {
	            this.uri = uri;
	            this.name = name;
	            this.parent = par;
	            this.parent.children.put(getFullName(uri, name),this);
	            this.type = type;
	            StringBuffer b = new StringBuffer();
	            b.append(par.path);
	            b.append('/');
	            if (type == 0) b.append('@');
	            String prefix = namespaces.getNamespacePrefix(uri, qname, type);
	            if (prefix.length() > 0) {
	                b.append(prefix);
	                b.append(':');
	            }
	            b.append(name);
	            this.path = b.toString();
	        }
	        
	        Node descend(String uri, String name, String qname, int type) {
	            String fullname = getFullName(uri,name);
	            Node n = (Node) children.get(fullname);
	            if (n == null) {
	                n = new Node(uri, name, qname, type, this);
	            }
	            return n;
	        }
	        
	        Node ascend() {
	            return this.parent;
	        }
	        
	        public String getPath() {
	        	return this.path;
	        }
	        
	        public String getFullName(String uri, String name) {
	            return uri + "|" + name;
	        }
	        public String getName(){
	        	String res="";
	        	if(this.type == 0){
	        		res = "@"+this.name;
	        	}else{
	        		res = this.name;
	        	}
	            return res;
	        }
	        HashMap<String, Node> getChildrenMap(){
	        	return this.children;
	        }
	        public List<Node> getChildren() {
	        	ArrayList<Node> l = new ArrayList<Node>();
	        	l.addAll( children.values());
	        	
	        	return l;
	        }
	    }
	    
	    public void startElement(String uri, String name, String qname, Attributes attrs) throws SAXException {
	        
	        cursor = cursor.descend(uri, name, qname, 1);
	        
	        for (int i = 0; i < attrs.getLength(); i++) {
	            attribute(attrs.getURI(i), attrs.getLocalName(i), attrs.getQName(i), attrs.getValue(i));
	        }
	    }
	    
	    public abstract void attribute(String uri, String name, String qname, String value) throws SAXException;
	    
	    public void characters(char chars[], int start, int length) throws SAXException {
	        this.buffer.append(chars, start, length);
	    }
	    
	    String getText() {
	        String value = buffer.toString();
	        buffer.setLength(0);
	        return (trim) ? value.trim() : value;
	    }
	    
	    public void warning(SAXParseException exception) throws SAXException {
	        printError("Warning", exception);
	    }
	    
	    public void error(SAXParseException exception) throws SAXException {
	        printError("Error", exception);
	    }
	    
	    public void fatalError(SAXParseException exception) throws SAXException {
	        printError("Fatal Error", exception);
	    }
	    
	    private void printError(String type, Exception exception) {
	        System.err.print("[");
	        System.err.print(type);
	        System.err.print("] ");
	        if (exception == null) {
	            System.out.println("!!!");
	        }
	        
	        if (exception instanceof SAXParseException) {
	            SAXParseException e = (SAXParseException) exception;
	            String systemId = e.getSystemId();
	            if (systemId != null) {
	                int index = systemId.lastIndexOf('/');
	                if (index != -1) {
	                    systemId = systemId.substring(index + 1);
	                }
	                System.err.print(systemId);
	            }
	            System.err.print(':');
	            System.err.print(e.getLineNumber());
	            System.err.print(':');
	            System.err.print(e.getColumnNumber());
	            System.err.print(": ");
	        }
	        
	        System.err.print(exception.getMessage());
	        System.err.println();
	        System.err.flush();
	    }
}
