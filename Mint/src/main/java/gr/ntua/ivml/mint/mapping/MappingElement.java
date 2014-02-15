package gr.ntua.ivml.mint.mapping;

import gr.ntua.ivml.mint.persistent.XpathHolder;
import gr.ntua.ivml.mint.xml.Handler.Node;

import java.util.ArrayList;
import java.util.List;

public class MappingElement extends XpathHolder {
	private XpathHolder holder = null;
	private String id = "";
	private String xpath = "";
	private ArrayList<MappingElement> children = new ArrayList<MappingElement>();
	private ArrayList<MappingElement> attributes = new ArrayList<MappingElement>();
	private boolean isattr = false;
	

	public MappingElement(XpathHolder xpath) {
		this(xpath, false);
	}
	
	public MappingElement(XpathHolder xpathholder, boolean attr) {
		this.holder = xpathholder;
		if(xpathholder != null) {
			this.xpath = xpathholder.getXpathWithPrefix(true);
		} else {
			this.xpath = "";
		}
		this.setAttribute(attr);
		List<? extends XpathHolder> list = xpathholder.getChildren();
		for(XpathHolder xp: list) {
			// TODO: must be romoved. text() node should not even be here at the first place
			if(xp.getName().equalsIgnoreCase("text()")) continue;
			
			if(xp.getName().startsWith("@")) {
				MappingElement element = new MappingElement(xp, true);
				attributes.add(element);
			} else {
				MappingElement element = new MappingElement(xp);
				children.add(element);
			}
		}
	}
	
	public MappingElement(Node node) {
		this(node, false);
	}
	
	public MappingElement(Node node, boolean attr) {
		this.holder = new XpathHolder();
		this.holder.setName(node.getName());
		this.holder.setXpath(node.getPath());
		this.setAttribute(attr);
		List<Node> nodes = node.getChildren();
		for(Node n: nodes) {
			if(n.getName().startsWith("@")) {
				MappingElement element = new MappingElement(n, true);
				attributes.add(element);
			} else {
				MappingElement element = new MappingElement(n);
				children.add(element);
			}
		}
	}

	public String getXPath() {
		return this.xpath;
	} 
	
	public XpathHolder getXPathHolder() {
		return this.holder;
	}
	
	public String getName() {
		if(holder != null) {
//			return holder.getUriPrefix() + ":" + holder.getName();
			return holder.getName();
		} else {
			return "";
		}
	}
	
	public List<? extends XpathHolder> getXPathChildren() { return this.children; }
	public ArrayList<MappingElement> getChildren() { return this.children; }
	public MappingElement getChild(int index) { return this.children.get(index); }

	public ArrayList<MappingElement> getAttributes() { return this.attributes; }
	public MappingElement getAttribute(int index) { return this.attributes.get(index); }

	public void setAttribute(boolean isattr) { this.isattr = isattr; }
	public boolean isAttribute() { return isattr; }

	public void setId(String id) { this.id = id; }
	public String getId() { return id; }
	
	public void print(StringBuffer out, boolean simple) {
		String className = (this.isAttribute())?"xmlattribute":"xmlelement";
		String xpath = holder.getXpathWithPrefix(true);
		XpathHolder textNode = holder.getChild("text()");
		Long xpid = new Long(0);
		if(textNode != null) {
			xpid = textNode.getDbID();
		}
		
		out.append("<div id=\"" + this.getId() + "\" xpid=\"" + xpid + "\" xpath=\"" + xpath + "\" class=\"" + className + "\">");
		if(!simple) {
			if(children.isEmpty()) {
				this.printIcon(out);
			} else {
				this.printIconDisabled(out);
			}
		}
		out.append(this.getName() + "\n");
		out.append("</div>\n");
		
	}
	
	public void printIcon(StringBuffer out) {
		out.append("<img width=\"14\" height=\"14\" src=\"custom/images/i-icon-nofill.gif\" onclick=\"javascript:showTooltip('" + this.getId() + "')\"/>\n");
	}
	
	public void printIconDisabled(StringBuffer out) {
		out.append("<img width=\"14\" height=\"14\" src=\"images/i-icon-grey.gif\" onclick=\"javascript:showTooltip('" + this.getId() + "')\"/>\n");
	}
	
	public void printTree(StringBuffer out, boolean simple) {
		
		out.append("<li>\n");
		
		this.print(out, simple);

		if(!attributes.isEmpty() || !children.isEmpty()) {
			out.append("<ul>\n");
			for(MappingElement e: attributes) {
				e.printTree(out, simple);
			}			
			for(MappingElement e: children) {
				e.printTree(out, simple);
			}
			out.append("</ul>\n");
		}
		
		out.append("</li>\n");
	}
}
