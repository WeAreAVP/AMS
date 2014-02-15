package gr.ntua.ivml.mint.util;

/**
 * Primitive StringBuilder wrapper to have something that only captures until
 * capacity characters and puts the endMark. Would be nicer to be a StringBuilder,
 * but that one is final.
 * 
 * @author Arne Stabenau 
 *
 */
public class LimitedStringBuilder {
	int capacity;
	boolean closed;
	StringBuilder content;
	String endMark;
	
	public LimitedStringBuilder( int capacity, String endMark ) {
		content = new StringBuilder();
		closed = false;
		this.capacity = capacity - endMark.length(); 
		this.endMark = endMark;
	}
	
	public LimitedStringBuilder append( String s ) {
		if( !closed ) {
			if( capacity > 0 ) {
				if( capacity > s.length() ) {
					content.append(s);
					capacity -= s.length();
				} else {
					content.append( s.substring(0, capacity ));
					content.append( endMark );
					closed = true;
					capacity = 0;
				}
			} else {
				content.append( endMark );
				closed = true;
			}
		}
		return this;
	}
	
	public String getContent() {
		return content.toString();
	}
}
