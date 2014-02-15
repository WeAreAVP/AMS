package gr.ntua.ivml.mint.persistent;

import static gr.ntua.ivml.mint.util.StringUtils.empty;
import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.db.XMLNodeDAO;

import java.io.PrintWriter;
import java.io.StringWriter;
import java.nio.charset.Charset;
import java.security.MessageDigest;
import java.util.ArrayList;
import java.util.Collections;
import java.util.List;
import java.util.Map;
import java.util.Stack;

import nu.xom.Attribute;
import nu.xom.Element;

import org.apache.commons.lang.StringEscapeUtils;
import org.apache.log4j.Logger;

/**
 * Struct to store a row of xml_node table
 * @author arne
 *
 */
public class XMLNode  {
	public static final byte ELEMENT = 1;
	public static final byte ATTRIBUTE = 2;
	public static final byte TEXT = 4;

	public long nodeId, parentNodeId, size;
	public String content;
	public XmlObject xmlObject;
	public XmlObject getXmlObject() {
		return xmlObject;
	}

	public void setXmlObject(XmlObject xmlObject) {
		this.xmlObject = xmlObject;
	}

	public byte nodeType;
	public String checksum;


	private XMLNode parentNode;
	private List<XMLNode> children = new ArrayList<XMLNode>();
	private XpathHolder xpathHolder;
	

	public static Logger log = Logger.getLogger( XMLNode.class );
	
	
	static { 
	}
	
	public static final class MyElement extends Element {
		public XMLNode node;
		
		public MyElement( String name, XMLNode n ) {
			super( name  );
			this.node= n;
		}

		public MyElement( String name, String namespace, XMLNode n ) {
			super( name, namespace );
			this.node= n;
		}
	}

	public static final class MyAttribute extends Attribute {
		public XMLNode node;
		public MyAttribute( String name, String value, XMLNode n ) {
			super( name, value );
			this.node = n;
		}
		
		public MyAttribute( String name, String namespace, String value, XMLNode n ) {
			super( name, namespace, value );
			this.node = n;
		}
	}
	
	
	public XMLNode() {
		size = 0l;
	}
	
	/**
	 * Removes children and parent.
	 * @param that
	 * @return
	 */
	private static XMLNode detachedCopy( XMLNode that ) {
		XMLNode res=new XMLNode();
		res.nodeType = that.getNodeType();
		res.checksum = that.getChecksum();
		res.nodeId = that.getNodeId();
		res.size = that.getSize();
		res.xmlObject = that.getXmlObject();
		res.content = that.getContent();
		res.xpathHolder = that.getXpathHolder();
		res.children = new ArrayList<XMLNode>();
		res.parentNode = null;
		
		return res;
	}
	
	public XMLNode( long id ) {
		nodeId = id;
	}
	
	public void updateChecksum ( String val ) {
		StringBuffer sb = new StringBuffer();
		MessageDigest md=null;
		try {
			md = MessageDigest.getInstance( "MD5");
		} catch( Exception e ) {
			log.error( "cant get message digest", e );
			throw new Error( e );
		}
		
		if( checksum == null ) {
			checksum = "";
		}
		md.update( checksum.getBytes( Charset.forName( "UTF-8")));
		md.update( val.getBytes( Charset.forName( "UTF-8")));
		byte[] md5 = md.digest();
		for( byte b: md5 ) {
			int i = (b&0xff);
			if( i < 16 )
				sb.append( "0" );
			sb.append( Integer.toHexString(i));
		}
		checksum = sb.toString();
	}
	
	/* fields for easy use with hibernate */
	/* not used during indexing */
	private DataUpload dataUpload;
	public long getNodeId() {
		return nodeId;
	}

	public void setNodeId(long nodeId) {
		this.nodeId = nodeId;
	}

	/**
	 * How many rows in the database does this node and all its children
	 * together occupy? 
	 * @return
	 */
	public long getSize() {
		return size;
	}

	public void setSize(long size) {
		this.size = size;
	}

	public String getContent() {
		return content;
	}

	public void setContent(String content) {
		this.content = content;
	}

	public String getXpath() {
		return xpathHolder.getXpath();
	}
	
	public String getXpathWithPrefix() {
		return xpathHolder.getXpathWithPrefix(false);
	}


	public byte getNodeType() {
		return nodeType;
	}

	public void setNodeType(byte nodeType) {
		this.nodeType = nodeType;
	}

	public String getChecksum() {
		return checksum;
	}

	public void setChecksum(String checksum) {
		this.checksum = checksum;
	}

	public DataUpload getDataUpload() {
		return dataUpload;
	}

	public void setDataUpload(DataUpload dataUpload) {
		this.dataUpload = dataUpload;
	}

	public XMLNode getParentNode() {
		return parentNode;
	}

	public void setParentNode(XMLNode parentNode) {
		this.parentNode = parentNode;
	}

	public List<XMLNode> getChildren() {
		return children;
	}

	public void setChildren(List<XMLNode> children) {
		this.children = children;
	}

	public void setXpathHolder(XpathHolder xpathHolder) {
		this.xpathHolder = xpathHolder;
	}


	public XpathHolder getXpathHolder() {
		return xpathHolder;
	}
	
	/**
	 * Will print this node only.
	 * @param out
	 */
	public void toXml( PrintWriter out ) {
		toXmlWithWrapper(out, false );
		out.flush();
	}
	
	/**
	 * Same as toXml( PrintWriter pw ) but returns a string.
	 * @return
	 */
	public String toXml( ) {
		StringWriter sw = new StringWriter();
		toXml( new PrintWriter( sw ));
		return sw.toString();
	}
	
	
	/**
	 * Will print as well all enclosing tags for this node.
	 * @param out
	 */
	public void toXmlWrapped( PrintWriter out ) {
		xmlWrapItem( this, out );
	}
	
	private void toXmlWithWrapper( PrintWriter out, boolean wrapper ) {
		out.println( "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>" );
		// find namespaces used and print them
		Map<String, String> namespaces = getXpathHolder().getNamespaces(true);
		
		StringBuilder sb = new StringBuilder();
		for( Map.Entry<String, String> entry: namespaces.entrySet()) {
			if(( entry.getValue() == null ) || (entry.getValue().length()==0)) 
				sb.append( "xmlns=\""+entry.getKey()+"\" " );
			else 
				sb.append( "xmlns:"+entry.getValue()+"=\"" + 
						entry.getKey()+"\" " );
		}
		
		toXml(0, out,  sb.toString());		
	}
	
	private void toXml( int depth, PrintWriter out, String namespaces ) {
		XpathHolder xp = getXpathHolder();
		if( nodeType==ATTRIBUTE ) {
			out.print( xp.getNameWithPrefix(true).substring(1));
			out.print( "=\"" + StringEscapeUtils.escapeXml(content) + "\" ");
		} else {
			// the biggy, sigh
			if( depth >= 0 ) {
				/*
				out.println("");
				for( int i=0; i<depth; i++ ) out.print( " " );
				*/
			}
			out.print( "<" +  xp.getNameWithPrefix(true) + " ");
			out.print( namespaces );
			for( XMLNode attr: getAttributes())
				attr.toXml( depth, out, "");
			if( !hasNonAttributes()) {
				out.print( "/>");
			} else {
				out.print( ">" );
				if( isMixed() || (depth<0) ) depth = -3;
				for( XMLNode n: getChildren()) {
					if( n.getNodeType() == ATTRIBUTE ) continue;
					if( n.getNodeType() != TEXT )
						n.toXml(depth+2, out, "" );
					else {
						//out.print( n.getContent().trim());
						out.print(StringEscapeUtils.escapeXml(n.getContent()));
					}
				}
				if( depth >= 0 ) {
					/*
					out.println("");
					for( int i=0; i<depth; i++ ) out.print( " " );
					*/
				}
				out.print( "</" +  xp.getNameWithPrefix(true) + ">");
			}
		}
	}
	
	public boolean hasTextContent() {
		return ( nodeType == TEXT ) && 
			( content.trim().length() > 0 );
	}
	
	/**
	 * Return all children for that path
	 * @param path
	 * @return
	 */
	public List<? extends XMLNode> getRelativeChildren( String path ) {
		return Collections.emptyList();
	}

	/**
	 * Text content of this node.
	 * @return
	 */
	public String getText() {
		StringBuffer sb = new StringBuffer();
		for( XMLNode node: getChildren() ) {
			if( node.getNodeType() == TEXT ) sb.append( node.getContent());
		}
		return sb.toString();
	}
	
	/**
	 * Concat all text of children.
	 * @return
	 */
	public String getTextRecursive() {
		StringBuffer sb = new StringBuffer();
		textRecursive( sb );
		return sb.toString();
	}
	
	
	private void textRecursive( StringBuffer sb ) {
		if( getNodeType() == TEXT ) sb.append( getContent() );
		else if( getNodeType() == ELEMENT ) {
			for( XMLNode node: getChildren() ) {
				node.textRecursive(sb);
			}
		}
	}
	
	/**
	 * Given an XpathHolder it returns all descendent nodes that have that holder.
	 * If the given holder is not a descendent of the current nodes holder, there
	 * will be no error, but no results.
	 * 
	 * Only use this on DOM trees or your memory and runtime will be unfavourable!
	 * @param path
	 * @return
	 */
	public List<? extends XMLNode> getChildrenByXpath( XpathHolder path ) {
		XpathHolder xp = path;
		Stack<XpathHolder> sxp = new Stack<XpathHolder>();
		while( (xp != null ) && ( xp != getXpathHolder())) {
			sxp.push( xp );
			xp = xp.getParent();
		}
		if(( xp != null ) && (xp.getDbID() == getXpathHolder().getDbID())) {
			sxp.push( getXpathHolder());
			List<XpathHolder> pathList = new ArrayList<XpathHolder>();
			Collections.reverse( sxp );
			pathList.addAll( sxp );
			List<XMLNode> result = new ArrayList<XMLNode>();
			getChildrenByXpath(pathList.toArray( new XpathHolder[pathList.size()]), 0, result);
			return result;
		} else {
			return Collections.emptyList();
		}
	}

	
	/**
	 * For Xpath queries, convert to XOMs really easy XML rep.
	 * 
	 * @return
	 */
	public Element toXOMElement() {
		if( getNodeType() != ELEMENT ) throw new IllegalArgumentException();
		MyElement e;
		XpathHolder xp = getXpathHolder();
		if( empty( xp.getUri())) {
			e = new MyElement( xp.getName(),this);
		} else {
			e = new MyElement( xp.getNameWithPrefix(true), xp.getUri(), this);
		}
		for( XMLNode node:getChildren()) {
			if( node.getNodeType()==TEXT) e.appendChild(node.getContent());
			else if( node.getNodeType() == ELEMENT ) e.appendChild(node.toXOMElement());
			else {
				MyAttribute a;
				if(empty( node.getXpathHolder().getUri())) {
					a = new MyAttribute( node.getXpathHolder().getName().substring(1), node.getContent(), node );
				} else {
					String namespace = node.getXpathHolder().getUri();
					log.debug( "Namespace " + namespace );
					a = new MyAttribute( node.getXpathHolder().getNameWithPrefix(true).substring(1),  
							namespace, node.getContent(), node );
				}
				e.addAttribute(a);
			}
		}
		return e;
	}
	
	
	
	/**
	 * add children from path[index] to result in this node
	 * @param path
	 * @param index
	 * @param result
	 */
	private void getChildrenByXpath( XpathHolder[] path, int index, List<XMLNode> result ) {
		// catch problems
		if( index >= path.length ) return;
		
		// test relevance first
		if( getXpathHolder().getDbID() != path[index].getDbID()) return;
		// ready, then add this node and return
		if( index == (path.length-1)) {
			result.add( this );
		} else {
			for( XMLNode x: getChildren()) {
				x.getChildrenByXpath(path, index+1, result);
			}			
		}
	}
	
	public List<? extends XMLNode> createList() {
		return new ArrayList<XMLNode>();
	}
	
	public List<? extends XMLNode> getAttributes() {
		List<XMLNode> res = new ArrayList<XMLNode>();
		for( XMLNode x: getChildren()) {
			if( x.nodeType == ATTRIBUTE)
				res.add( x );
		}
		return res;
	}
	
	/**
	 * Has text and element nodes mixed
	 * @return
	 */
	public boolean isMixed() {
		boolean hasText = false;
		boolean hasElements = false;
		for( XMLNode n: getChildren()) {
			if( n.getNodeType() == TEXT) hasText = true;
			if( n.getNodeType() == ELEMENT ) hasElements = true;
		}
		return hasText&&hasElements;
	}

	public boolean hasNonAttributes() {
		boolean result = false;
		for( XMLNode n:getChildren()) {
			if( n.nodeType != ATTRIBUTE)
				result = true;
		}
		return result;
	}

	/**
	 * Produce XML that wraps the given item node in with all elements and values of surrounding.
	 * All parent nodes are included. Excluded are subtrees that contain other item nodes but not
	 * the given one!
	 * @param itemNode
	 * @param out
	 */
	public static void xmlWrapItem( XMLNode itemNode, PrintWriter out ) {
		// algorithm like so
		/*
		 * From item node go upward, check all children
		 *  - if its from this node, include
		 *  - if it leads to other item exclude
		 *  - otherwise include
		 *  - go up and repeat
		 */
		XMLNode currentTree = DB.getXMLNodeDAO().getDOMTree(itemNode);
		currentTree.setParentNode(itemNode.getParentNode());
		while( currentTree.getParentNode() != null ) {
			currentTree = buildItemWrapTree( currentTree, itemNode );
		}
		// now currentTree should be detached and wrapping one item!
		// Detach, so that we don't auto retrieve other items during traversal
		currentTree.toXml(out);
	}
	
	
	public static XMLNode buildItemWrapTree( XMLNode itemNode ) {
		XMLNode currentTree = DB.getXMLNodeDAO().getDOMTree(itemNode);
		currentTree.setParentNode(itemNode.getParentNode());
		while( currentTree.getParentNode() != null ) {
			currentTree = buildItemWrapTree( currentTree, itemNode );
		}
		return currentTree;
	}
	
	/**
	 * Need a tree with nodes that don't link to the DB any more.
	 * So that traversal doesn't autoload more items.
	 * @param currentTree
	 * @param itemNode
	 * @return
	 */
	public static XMLNode buildItemWrapTree( XMLNode currentTree, XMLNode itemNode ) {
		XMLNode parent = currentTree.getParentNode();
		XMLNode newParent = detachedCopy(parent);
		newParent.setParentNode(parent.getParentNode());
		XMLNodeDAO dao = DB.getXMLNodeDAO();
		// check the children
		for( XMLNode childNode: dao.quickOtherSiblings(currentTree)) {
			// skip the currentTree 
			if( childNode.getNodeId() == currentTree.getNodeId()) {
				newParent.getChildren().add(currentTree);
				currentTree.setParentNode(newParent);
			}
			else {
				// include if it doesn't contain another item
				// cases where environment elements are in parts of the tree
				// that could be parent to an item will not be included
				if( itemNode.getXpathHolder().isDescendant(childNode.getXpathHolder())) continue;
				XMLNode newChild= dao.getDOMTree(childNode);
				newChild.setParentNode(newParent);
				newParent.getChildren().add( newChild );
			}
		}
		return newParent;
	}
	
	
	// need some functions for tree index nodes aaa first child, first child first child ..
	// xzz child 23+25*26+25 yzzzz zabababab
	public static String num2key( long num ) {
		if( num < 23 ) return num2Letters(num, 23, 1);
		num -= 23;
		
		if( num < (26*26)) return "x"+num2Letters( num, 26, 2 );		
		num -= (26*26);
		

		if( num < (26*26*26*26)) return "y"+num2Letters( num, 26, 4 );		
		num -= (26*26*26*26);
		if( num < (26l*26l*26l*26l*26l*26l*26l*26l)) return "z"+num2Letters( num, 26, 8 );
		throw new RuntimeException( "Number overflow");
	}	
	
	private static String num2Letters( long num, int base, int len ) {
		StringBuffer result = new StringBuffer();
		for( int i=0; i<len; i++ ) {
			int mod = (int)(num%base);
			num /= (long)base;
			result.insert( 0, (char)('a'+mod));
		}
		return result.toString();
	}
	
	public static long popNum( StringBuffer key) {
		if( key.length() ==0 ) return -1;
		char currentChar;
		currentChar = key.charAt( 0 );
		key.deleteCharAt(0);
		int diff = (int) ( currentChar- 'a');
		if( diff < 23 ) return ( long ) diff;
		
		if( diff == 23 ) return 23l+popNum( key, 2 );
		if( diff == 24 ) return 23l+26*26l+popNum( key, 4 );
		if( diff == 25 ) return 23l+26l*26l+26l*26l*26l*26l+popNum( key, 8 );	
		return -1l;
 	}
	
	private static long popNum( StringBuffer key, int len ) {
		long result=0l;
		for( int i=0; i<len; i++ ) {
			result *= 26l;
			char currentChar= key.charAt(0);
			key.deleteCharAt(0);
			int diff = (int) ( currentChar- 'a');
			result += diff;
		}
		return result;
	}
}
