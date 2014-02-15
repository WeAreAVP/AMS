package gr.ntua.ivml.mint.persistent;

import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.db.GlobalPrefixStore;
import gr.ntua.ivml.mint.util.StringUtils;
import gr.ntua.ivml.mint.util.TraversableI;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

public class XpathHolder implements TraversableI {
	public Long dbID;
	public String xpath;
	public long count;
	
	public String uri;
	public String uriPrefix;
	public String name;
	public XpathHolder parent;
	public XmlObject xmlObject;
	public boolean optional, multiple;
	public String description;
	
	public int occurences;
	
	
	public String getDescription() {
		return description;
	}

	public void setDescription(String description) {
		this.description = description;
	}

	public XpathHolder() {
		optional = false;
		multiple = false;
		occurences = -1;
	}
	
	public boolean isOptional() {
		return optional;
	}
	public void setOptional(boolean optional) {
		this.optional = optional;
	}
	public boolean isMultiple() {
		return multiple;
	}
	public void setMultiple(boolean multiple) {
		this.multiple = multiple;
	}
	public XmlObject getXmlObject() {
		return xmlObject;
	}
	public void setXmlObject(XmlObject xmlObject) {
		this.xmlObject = xmlObject;
	}

	public List<XpathHolder> children = new ArrayList<XpathHolder>();
	
	public String getUriPrefix() {
		return uriPrefix;
	}
	public void setUriPrefix(String uriPrefix) {
		this.uriPrefix = uriPrefix;
	}
	public String getUri() {
		return uri;
	}
	public void setUri(String uri) {
		this.uri = uri;
	}
	public String getName() {
		return name;
	}
	public void setName(String name) {
		this.name = name;
	}
	public XpathHolder getParent() {
		return parent;
	}
	public void setParent(XpathHolder parent) {
		this.parent = parent;
	}
	public List<? extends XpathHolder> getChildren() {
		return children;
	}
	public void setChildren(List<XpathHolder> children) {
		this.children = children;
	}
	public Long getDbID() {
		return dbID;
	}
	public void setDbID(Long dbID) {
		this.dbID = dbID;
	}
	public String getXpath() {
		return xpath;
	}
	public void setXpath(String xpath) {
		this.xpath = xpath;
	}
	public long getCount() {
		return count;
	}
	public void setCount(long count) {
		this.count = count;
	}

	public XpathHolder getByNameUri( String name, String uri ) {
		// if(( uri == null ) || ( uri.trim().length() == 0 )) uri = "self";
		for( XpathHolder xp: children ) {
			if( xp.name.equals(name) && xp.uri.equals(uri ))
				return xp;
		}
		
		XpathHolder result = new XpathHolder();
		result.parent = this;
		children.add( result );
		result.uri = uri;
		// if( "self".equals( uri )) result.uriPrefix="_";
		result.name = name;
		result.xpath = xpath+"/"+name;
		result.xmlObject = xmlObject;
		return result;
	}
	
	/**
	 * Get a list of nodes with start and end, so you can page through big lists.
	 * @param from
	 * @param to
	 * @return
	 */
	public List<XMLNode> getNodes( long from, long count ) {
		return DB.getXMLNodeDAO().getByXpathHolder(this, from, count );
	}

	/**
	 * Alternative to paging with offset for high throughput processing
	 *  This is potentially better supported with index ..
	 * @param from
	 * @param count
	 * @return
	 */
	public List<XMLNode> getNodes( XMLNode start, long count ) {
		return DB.getXMLNodeDAO().getByXpathHolder(this, start, count );
	}
	
	public List<? extends XpathHolder> getAttributes() {
		List<XpathHolder> res = new ArrayList<XpathHolder>();
		for( XpathHolder xp: children ) {
			if( xp.getName().startsWith("@"))
				res.add( xp );
		}
		return res;
	}
	
	public XpathHolder getChild( String name ) {
		for( XpathHolder xp: children )
			if( xp.getName().equals(name))
				return xp;
		return null;
	}
	
	/**
	 * Check if this path has given ancestor.
	 * @param ancestor
	 * @return true if it has
	 */
	public boolean isDescendant( XpathHolder ancestor ) {
		XpathHolder current = this;
		while( current != null ) {
			if( current.getDbID() == ancestor.getDbID()) return true;
			// shortcut, but doesn't respect namespaces
			// ancestor could have same xpath but with different namespace somewhere
			if( ! current.getXpath().startsWith( ancestor.getXpath())) return false;
			current = current.getParent();
		}
		return false;
	}
	
	/**
	 * The name with prefix for the node. If global, use global namespace prefixes, otherwise the local one.
	 * @param name
	 * @param global
	 * @return
	 */
	public XpathHolder getChild( String name, boolean global ) {
		for( XpathHolder xp: children ) {
			if( xp.getNameWithPrefix(global).equals(name)) return xp;
		}
		return null;
	}
	
	
	/**
	 * The name with prefix for the node. Provide namespace to prefix mappings.
	 * @param name
	 * @param global
	 * @return
	 */
	public XpathHolder getChild( String name, Map<String, String> prefixes ) {
		for( XpathHolder xp: children ) {
			if( xp.getNameWithPrefix(prefixes).equals(name)) return xp;
		}
		return null;
	}
	
	/**
	 * Get with a relative xpath. 
	 * @param path
	 * @return XpathHolder or null if not exists
	 */
	public XpathHolder getByRelativePath( String path ) {
		if( path.startsWith("/"))
			path = path.substring(1);
		
		int index = path.indexOf('/');
		String head, tail;
		
		if( index != -1 ) {
			head = path.substring(0, index );
			tail = path.substring(index+1 );
			XpathHolder child = getChild( head );
			if( child == null) return null;
			return child.getByRelativePath(tail);
		} else 
			return getChild( path );
	}
	
	/**
	 * Get with a relative xpath with uri prefix. If global, use global prefix table.
	 * @param path
	 * @return XpathHolder or null if not exists
	 */
	public XpathHolder getByRelativePathWithPrefix( String path, boolean global ) {
		
		String nonPrefix = path.replaceAll("/[^:/]+:", "/");
		List<XpathHolder> lx = DB.getXpathHolderDAO().getByRelativePath(this, nonPrefix);
		if( lx.size() == 0 ) return null;
		if( lx.size() == 1 ) return lx.get(0);
		
		String targetPath = getXpathWithPrefix(global)+path;
		for( XpathHolder xp: lx ) {
			if( targetPath.equals( xp.getXpathWithPrefix(global)))
				return xp;
		}
		
		return null;
	}
	

	/**
	 * Get with a relative xpath with uri prefix. 
	 * Provide prefixes with namespaces.
	 * @param path
	 * @return XpathHolder or null if not exists
	 */
	public XpathHolder getByRelativePathWithPrefix( String path, Map<String, String> prefixes ) {
		if( path.startsWith("/"))
			path = path.substring(1);
		
		int index = path.indexOf('/');
		String head, tail;
		
		if( index != -1 ) {
			head = path.substring(0, index );
			tail = path.substring(index+1 );
			
			XpathHolder child = getChild( head, prefixes );
			if( child == null) return null;
			return child.getByRelativePathWithPrefix(tail, prefixes);
		} else 
			return getChild( path,prefixes );
	}
	

	
	/**
	 * Gets it with one query, just needs the DB, the other works with any XpathHolder
	 * by traversing the children.
	 * @param path
	 * @return
	 */
	public XpathHolder getByRelativePathQuick( String path ) {
		List<XpathHolder> lx = DB.getXpathHolderDAO().getByRelativePath(this, path);
		if( lx.size() > 0 ) return lx.get(0);
		else return null;
	}
	
	public long getDistinctCount() {
		return DB.getXMLNodeDAO().countDistinct( this );
	}
	
	public float getAvgLength() {
		return DB.getXMLNodeDAO().getAvgLength( this );		
	}
	
	// TODO: move the node type to the xpathHolder
	public boolean isTextNode() {
		return getName().equals("text()");
	}
	
	// TODO: move the node type to the xpathHolder
	public boolean isAttributeNode() {
		return getName().startsWith("@");
	}

	/**
	 * Get the first child which is Text. There could be many ..
	 * @return
	 */
	public XpathHolder getTextNode() {
		for( XpathHolder xp: getChildren()) {
			if( xp.isTextNode()) return xp;
		}
		return null;
	}
	
	/**
	 * Correct way of getting paths with prefixes by supplying the prefixes!
	 * @param prefixes
	 * @return
	 */
	public String getXpathWithPrefix(Map<String,String> prefixes) {
		String parentPath;
		String myPath = getNameWithPrefix(prefixes); 
		if( getParent() != null ) {
			parentPath = getParent().getXpathWithPrefix(prefixes);
			return parentPath+"/"+myPath;
		} else 
			if( StringUtils.empty( myPath ) || myPath.equals( "/" ))
				return "";
			else
				return "/"+myPath;
	}
		
	public String getXpathWithPrefix(boolean global) {
		String parentPath;
		String myPath = getNameWithPrefix(global); 
		if( getParent() != null ) {
			parentPath = getParent().getXpathWithPrefix(global);
			return parentPath+"/"+myPath;
		} else 
			if( StringUtils.empty( myPath ) || myPath.equals( "/" ))
				return "";
			else
				return "/"+myPath;
	}
		
	/**
	 * Give frequency per value but only limit entries
	 * @param limit
	 * @return
	 */
	public List<Object[]> getCountByValue( int limit ) {
		return DB.getXMLNodeDAO().getCountByValue( this, limit);	
	}

	/**
	 * Get back content field for this Holder. Only attributes and text() nodes have
	 * content. Gets back all distinct values sorted ascending. 
	 * @return list of [ string value, count ]
	 */
	public List<Object[]> getValues() {
		return DB.getXMLNodeDAO().getValues(this, 0, -1);
	}
	
	/**
	 * Get back content field for this Holder. Only attributes and text() nodes have
	 * content. Gets back all distinct values sorted ascending. This version allows for
	 * paging.
	 * @return list of [string value, count ]
	 */
	
	public List<Object[]> getValues( int start, int maxCount ) {
		return DB.getXMLNodeDAO().getValues(this, start	, maxCount);
	}
	
	
	
	/**
	 * Finds all used namespaces underneath this holder. You should not regard the prefixes in general.
	 * The global=true prefixes are probably good though.
	 * @return
	 */
	public Map<String, String> getNamespaces(boolean global) {
		Map<String, String> result = new HashMap<String, String>();
		getNamespaces(result, global);
		return result;
	}
	
	/**
	 * Finds all used namespaces underneath this holder. The prefixes should be ignored or
	 * fixed, as they can repeat.
	 * @return
	 */
	private void getNamespaces( Map<String, String> result, boolean global ) {
		if(( getUri() !=null ) && ( getUri().length()>0 )) {
			if(global)
				result.put( getUri(), GlobalPrefixStore.getPrefix(getUri()));
			else
				result.put( getUri(), getUriPrefix());
		}
		for( XpathHolder xp: getChildren()) {
			xp.getNamespaces(result, global);
		}
	}

	/**
	 * how many levels down from the root??
	 * @return
	 */
	public int getDepth() {
		if( parent != null ) return parent.getDepth()+1;
		else return 0;
	}
	/**
	 * Small helper to get the whole tree in a list
	 * @return
	 */
	public List<XpathHolder> getChildrenRecursive() {
		// TODO Auto-generated method stub
		ArrayList<XpathHolder> result = new ArrayList<XpathHolder>();
		getChildrenRecursive(this, result);
		return result;
	}
	
	
	/**
	 * Correct way of handling prefixes, with a map urn->prefix
	 * @param prefixes
	 * @return
	 */
	public String getNameWithPrefix(Map<String,String> prefixes) {
		StringBuffer res = new StringBuffer();
		String name = getName();
		if( StringUtils.empty(name)) return "";
		res.append( name );
		String uri =  getUri();
		if( ! StringUtils.empty( uri )) {
			String prefix = prefixes.get( uri );
			
			if(! StringUtils.empty(prefix)) {
				prefix = prefix+":";
				int insert = 0;
				if(res.charAt(0)=='@') insert=1;
				res.insert(insert, prefix);
			} 
		}
		return res.toString();
	}
	
	public String getNameWithPrefix(boolean global) {
		StringBuffer res = new StringBuffer();
		String name = getName();
		if( StringUtils.empty(name)) return "";
		res.append( name );
		String uri =  getUri();
		if( ! StringUtils.empty( uri )) {
			String prefix;
			if( global ) prefix = GlobalPrefixStore.getPrefix(uri);
			else prefix = getUriPrefix();
			if(! StringUtils.empty(prefix)) {
				prefix = prefix+":";
				int insert = 0;
				if(res.charAt(0)=='@') insert=1;
				res.insert(insert, prefix);
			} 
		}
		return res.toString();
	}
	
	
	private void getChildrenRecursive( XpathHolder parent, List<XpathHolder> result ) {
		if( parent != null ) {
			result.add( parent );
			for( XpathHolder child: parent.getChildren()) getChildrenRecursive(child, result);
		}
	}
	
	public ArrayList<String> listOfXPaths( boolean global ) {
		ArrayList<String> list = new ArrayList<String>();
		
		list.add(this.getXpathWithPrefix(global));
		for(XpathHolder child: this.getChildren()) {
			list.addAll(child.listOfXPaths(global));
		}
			
		return list;
	}

	public ArrayList<String> listOfXPaths(Map<String, String> prefixes ) {
		ArrayList<String> list = new ArrayList<String>();
		for( XpathHolder xp: getChildrenRecursive())
			list.add( xp.getXpathWithPrefix(prefixes));
		return list;
	}
}
