package gr.ntua.ivml.mint.xml;

import gr.ntua.ivml.mint.persistent.DataUpload;
import gr.ntua.ivml.mint.persistent.XpathHolder;
import gr.ntua.ivml.mint.util.TraversableI;
import gr.ntua.ivml.mint.xml.Handler.Node;
import gr.ntua.ivml.mint.xml.util.ElementValueMap;

import java.io.File;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;

import javax.xml.parsers.ParserConfigurationException;
import javax.xml.parsers.SAXParser;
import javax.xml.parsers.SAXParserFactory;

import org.xml.sax.SAXException;

public class TreeGenerationParser {
	private SAXParserFactory factory;
	private UniqueXPathHandler handler;
	private SAXParser parser;
	private int counter;
	private ArrayList<ElementValueMap> res;
    private String treeId = "";
	
	
	public TreeGenerationParser(){
		counter = 0;
		factory = SAXParserFactory.newInstance();
		factory.setNamespaceAware(true);
        factory.setValidating(false);
       try {
			parser = factory.newSAXParser();
		} catch (ParserConfigurationException e) {
			e.printStackTrace();
		} catch (SAXException e) {
			e.printStackTrace();
		}
	}
	
	public String parse(File file, String treeId){
		this.treeId = treeId;
		this.counter = 0;
		StringBuffer res = new StringBuffer();
		res.append( "<div id=\"treemenu_" + treeId +"\">" );
		ArrayList<Node> roots = new ArrayList<Node>();
		handler = new UniqueXPathHandler(true, roots);
		try {
			parser.parse(file, handler);
		} catch (SAXException e) {
			e.printStackTrace();
		} catch (IOException e) {
			e.printStackTrace();
		}
		traverse( roots, res );
		
		res.append( "</div>" );
		return res.toString();
	}
	
	public ArrayList<Node> parseElements(File file) {
		ArrayList<Node> roots = new ArrayList<Node>();
		handler = new UniqueXPathHandler(true, roots);
		try {
			parser.parse(file, handler);
		} catch(Exception e) {
			e.printStackTrace();
		}
		return roots;
	}
	
	public String parseUpload( DataUpload du ) {
		return this.parseUpload(du, true);		
	}
	
	public String parseUpload( DataUpload du, boolean includeDiv ) {
		this.treeId = "1";
		this.counter = 0;
		StringBuffer res = new StringBuffer();
		if(includeDiv) res.append( "<div id=\"treemenu_" + treeId +"\">" );

		traverseXpathHolder( du.getRootXpath().getChildren(), res );
		if(includeDiv) res.append( "</div>" );
		return res.toString();
		
	}
	
	
	public int getElementCount(){
		return this.counter;
	}
	
	private void traverse(List<? extends TraversableI> children, StringBuffer output ){
		if( children.isEmpty()) return;
		// TODO: sort the children, attributes first
		output.append("<ul>" );
		for( TraversableI t: children ) {
			Node tmpNode = (Node) t; 
			this.counter++;
			String divName = tmpNode.getName();
			String className = "xmlelement";
			if(divName.startsWith("@")) {
				className = "xmlattribute";
			}
			output.append( 
			 "<li id=\"node_" + counter +
			 "\"> <div id=\"tree_" + this.treeId +
			 "_node_" + counter +"\" class=\"" + className + 
			 "\">" + divName + "</div>" );
			traverse( t.getChildren(), output );
			output.append( "</li>\n" );
		}
		output.append( "</ul>\n" );
	}


	private void traverseXpathHolder(List<? extends TraversableI> children, StringBuffer output ){
		if( children.isEmpty()) return;
		// TODO: sort the children, attributes first
		output.append("<ul>" );
		for( TraversableI t: children ) {
			XpathHolder xp = (XpathHolder) t;
			this.counter++;
			String divName = xp.getName();
			String className = "xmlelement";
			if(divName.startsWith("@")) {
				className = "xmlattribute";
			}
			output.append( 
			 "<li id=\"node_" + counter +
			 "\"> <div id=\"tree_" + this.treeId +
			 "_node_" + counter +"\" class=\"" + className + 
			 "\">" + divName + "</div>"  );
			traverseXpathHolder( t.getChildren(), output );
			output.append( "</li>\n" );
		}
		output.append( "</ul>\n" );
	}
}
