package gr.ntua.ivml.mint.xml;

import gr.ntua.ivml.mint.persistent.DataUpload;
import gr.ntua.ivml.mint.persistent.Publication;
import gr.ntua.ivml.mint.persistent.Transformation;
import gr.ntua.ivml.mint.persistent.XMLNode;
import gr.ntua.ivml.mint.persistent.XmlObject;
import gr.ntua.ivml.mint.persistent.XmlSchema;
import gr.ntua.ivml.mint.persistent.XpathHolder;

import java.util.Iterator;
import java.util.List;

public class PathIterator implements Iterator<XMLNode> {
	private static final XMLNode[] templatePage = new XMLNode[0];

	Iterator<XmlObject> iterXmlObj;
	XmlObject currentXmlObj;
	
	String path;
	XpathHolder currentHolder;
	
	XMLNode nextItem;
	XMLNode[] page;
	int nextInPage;
	

	// Helper. Not strictly needed here
	public static PathIterator fromTransformations( List<Transformation> l, String path ) {
		final Iterator<Transformation> iterTransformation = l.iterator();
		Iterator<XmlObject> iterXml = new Iterator<XmlObject>() {

			@Override
			public boolean hasNext() {
				return iterTransformation.hasNext();
			}

			@Override
			public XmlObject next() {
				Transformation t = iterTransformation.next();
				return t.getParsedOutput();
			}

			@Override
			public void remove() {
				throw new UnsupportedOperationException();
			}
		};
		return new PathIterator( iterXml, path );
	}
	
	// create an iterator over the items for an upload
	public static PathIterator fromUpload( DataUpload du ) {
		final XmlObject obj = du.getXmlObject();
		String path = du.getItemXpath().getXpath();
		Iterator<XmlObject> iter = new Iterator<XmlObject>() {
			boolean hasNext = true;
			@Override
			public boolean hasNext() {
				return hasNext;
			}

			@Override
			public XmlObject next() {
				if( hasNext ) {
					hasNext = false;
					return obj;
				}
				return null;
			}

			@Override
			public void remove() {
				throw new UnsupportedOperationException();
			}
		};
		return new PathIterator( iter, path );
	}
	
	
	// create an iterator over the items for a transformation
	public static PathIterator fromTransform( Transformation t , XmlSchema xsch) {
		final XmlObject obj = t.getParsedOutput();
		String path = xsch.getItemPath();
		Iterator<XmlObject> iter = new Iterator<XmlObject>() {
			boolean hasNext = true;
			@Override
			public boolean hasNext() {
				return hasNext;
			}

			@Override
			public XmlObject next() {
				if( hasNext ) {
					hasNext = false;
					return obj;
				}
				return null;
			}

			@Override
			public void remove() {
				throw new UnsupportedOperationException();
			}
		};
		return new PathIterator( iter, path );
	}
	
	
	public PathIterator( Iterator<XmlObject> iter, String path ) {
		this.iterXmlObj = iter;
		this.path = path;
		next();
	}
	
	public PathIterator( List<XmlObject> l, String path ) {
		iterXmlObj = l.iterator();
		this.path = path;
		next();
	}
	
	@Override
	public boolean hasNext() {
		return nextItem != null;
	}

	
	private boolean nextHolder() {
		if( iterXmlObj.hasNext() ) {
			currentXmlObj = iterXmlObj.next();
			currentHolder =  currentXmlObj.getRoot().getByRelativePath(path);
			if( currentHolder != null )
				Publication.log.debug( "Current transformation has " + currentHolder.getCount() + " items." );
		} else {
			currentHolder = null;
		}
		return currentHolder != null;
	}

	/**
	 * Retrieve next page from current holder or first from next
	 * @return if there is stuff left
	 */
	private boolean nextPage() {
		List<XMLNode> l = null;
		if( page != null )
			l= currentHolder.getNodes( page[page.length-1], 100);
		if(( page== null ) || ( l.size() == 0 ))  {
			nextHolder();
			if( currentHolder == null ) return false;
			l= currentHolder.getNodes( 0, 100);
			if( l.size() == 0 ) throw new RuntimeException( "Unexpected result, should have nodes");				
		}
		page = l.toArray(templatePage);
		nextInPage = 0;			
		return true;
	}
	
	private XMLNode nextInPage() {
		XMLNode result=null;
		if(( page==null ) || ( nextInPage==page.length )) {
			if( ! nextPage()) return null;
		} 
		result = page[nextInPage];
		nextInPage+=1;
		return result;
	}
	
	@Override
	public XMLNode next() {
		XMLNode result = nextItem;
		nextItem = nextInPage();
		return result;
	}

	@Override
	public void remove() {
		throw new UnsupportedOperationException();
	}
}