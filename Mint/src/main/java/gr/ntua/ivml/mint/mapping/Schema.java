package gr.ntua.ivml.mint.mapping;

import gr.ntua.ivml.mint.persistent.DataUpload;
import gr.ntua.ivml.mint.persistent.XpathHolder;
import gr.ntua.ivml.mint.util.TraversableI;
import gr.ntua.ivml.mint.xml.TreeGenerationParser;
import gr.ntua.ivml.mint.xml.Handler.Node;

import java.io.File;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

public class Schema {
	private String id = "";
	private String filename = "";
	private HashMap<String, MappingElement> map = new HashMap<String, MappingElement>();

	private File file = null;
	private DataUpload dataUpload = null;
	
	private ArrayList<MappingElement> roots = new ArrayList<MappingElement>();
	
	public Schema() { this.setId(""); this.file = null; this.dataUpload = null;}
	public Schema(String id) { this.file = null; this.dataUpload = null; this.setId(id); }
	
	private void addElementsToMap(ArrayList<MappingElement> root) {
		String key = null;
		for(MappingElement e: root) {
			key = "tree_" + this.getId() + "_node_" + map.size();
			map.put(key, e);
			e.setId(key);
			
			addElementsToMap(e.getAttributes());
			addElementsToMap(e.getChildren());
		}
	}
	
	//added for item tree building
	private void addElementsToMap(ArrayList<MappingElement> root, int depth) {
		String key = null;
		for(MappingElement e: root) {
			key = "tree_" + this.getId() + "_node_" + (map.size()+depth);
			map.put(key, e);
			e.setId(key);
			
			addElementsToMap(e.getAttributes(),depth);
			addElementsToMap(e.getChildren(),depth);
		}
	}
	
	public void initFromFile(String filename) {
		this.filename = filename;
		System.out.println("schema(" + this.id + ") location: " + this.filename);

		try {
			this.file = new File(this.filename);
		} catch(Exception e) {
			System.out.println(e.toString());
			this.file = null;
		}
		
		TreeGenerationParser parser = new TreeGenerationParser();
		ArrayList<Node> nodes = parser.parseElements(this.file);
		for(Node n: nodes) {
			MappingElement element = new MappingElement(n);
			this.roots.add(element);
		}
		
		addElementsToMap(roots);
	}
		
	public void initFromUpload(DataUpload upload) {
//		this.dataUpload = DB.getDataUploadDAO().findById(Long.parseLong(this.uploadid), false);
		this.dataUpload = upload;
		
		XpathHolder rootXpath = this.dataUpload.getRootXpath();
		if(rootXpath != null) {
			List<? extends TraversableI> children = rootXpath.getChildren();
			for(TraversableI t: children) {
				XpathHolder xp = (XpathHolder) t;
				MappingElement element = new MappingElement(xp);
				this.roots.add(element);
			}
		}
		
		addElementsToMap(roots);
	}
	
	public void initSubtreeFormUpload(DataUpload upload) {
//		this.dataUpload = DB.getDataUploadDAO().findById(Long.parseLong(this.uploadid), false);
		this.dataUpload = upload;
		this.roots = new ArrayList<MappingElement>();
		XpathHolder rootXpath =null; 
		if(this.dataUpload.getItemXpath()!=null)	
			rootXpath=this.dataUpload.getItemXpath().getParent();
		if(rootXpath==null){
			rootXpath=this.dataUpload.getItemXpath();
		}
		XpathHolder itemXpath = this.dataUpload.getItemXpath();
		XpathHolder rXpath=this.dataUpload.getRootXpath();
		List xpaths=rXpath.listOfXPaths(false);
		int i=0;
		//remove duplicate nodes with text()
		List finalpaths=new ArrayList<String>();
		for(Object t: xpaths) {
			if(t.toString().length()==0 || t.toString().indexOf("/text()")>0){
		//		xpaths.remove(t);
				continue;
			}
			else{
				finalpaths.add(t);}
		}
		//find pos: used to build correct tooltip
		int pos=finalpaths.indexOf(itemXpath.getXpathWithPrefix(false));
		if(rootXpath != null && itemXpath!=null) {
			List<? extends TraversableI> children = rootXpath.getChildren();
			
			for(TraversableI t: children) {
			
				XpathHolder xp = (XpathHolder) t;
				
				if(xp!=itemXpath && xp.getParent()!=itemXpath){
					continue;
				}
				MappingElement element = new MappingElement(xp);
				this.roots.add(element);
			}
		}
		addElementsToMap(roots, pos);
	}
	
	public void setId(String id) { this.id = id; }
	public String getId() { return id;	}
	public void setFilename(String filename) {
	}
	public String getFilename() {
		return filename;
	}
	
	public MappingElement getMappingElement(String name) {
		MappingElement e = this.map.get(name);
		return e;
	}
	
	public String printTree() {
		return printTree(false);
	}
	
	public String printTree(boolean simple) {
		StringBuffer out = new StringBuffer();
				
		out.append("<div id=\"treemenu_" + this.getId() + "\">\n");
		out.append("<ul>\n");
		for(MappingElement e: roots) {
			e.printTree(out, simple);
		}
		out.append("</ul>\n");
		out.append("</div>");
		
		return out.toString();
	}
	
	public Map<String, MappingElement> getMap() {
		return this.map;
	}
}
