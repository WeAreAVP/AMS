package gr.ntua.ivml.mint.util;

import gr.ntua.ivml.mint.persistent.DataUpload;
import gr.ntua.ivml.mint.persistent.XMLNode;
import gr.ntua.ivml.mint.xml.PathIterator;

import java.util.Iterator;

public class InputIterator implements Iterator<Tuple<DataUpload, XMLNode>> {

	private Iterator<DataUpload> uploads;
	private DataUpload currentUpload;
	private Iterator<XMLNode> nodes;
	private Tuple<DataUpload, XMLNode> next=null;
	
	public InputIterator( Iterator<DataUpload> i ) {
		while( uploads.hasNext() ) {
			currentUpload = uploads.next();
			nodes = PathIterator.fromUpload(currentUpload);
			if( nodes.hasNext()) {
				next = new Tuple<DataUpload, XMLNode>( currentUpload, nodes.next());
				break;
			}
		}			
	}
	
	public boolean hasNext() {
		return next != null;
	}
	
	public Tuple<DataUpload, XMLNode> next() {
		Tuple<DataUpload, XMLNode> result = next;
		// find next node
		next = null;
		if( nodes.hasNext() ) {
			next = new Tuple<DataUpload, XMLNode>( currentUpload, nodes.next());
		} else {
			while( uploads.hasNext() ) {
				currentUpload = uploads.next();
				nodes = PathIterator.fromUpload(currentUpload);
				if( nodes.hasNext()) {
					next = new Tuple<DataUpload, XMLNode>( currentUpload, nodes.next());
					break;
				}
			}
		}
		return result;
	}
	
	public void remove() {
		throw new UnsupportedOperationException();
	}
}