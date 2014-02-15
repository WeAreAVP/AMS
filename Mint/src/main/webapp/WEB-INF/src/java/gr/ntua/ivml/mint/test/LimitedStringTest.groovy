package gr.ntua.ivml.mint.test;

import gr.ntua.ivml.mint.util.LimitedStringBuilder
import groovy.util.GroovyTestCase

class LimitedStringTest extends GroovyTestCase {
	public void testCreate() {
		def ls = new LimitedStringBuilder( 30, " ..." );
		ls.append( "this works\n");
		ls.append( "this works\n");
		ls.append( "this works\n");
		ls.append( "this works\n");
		assert( ls.getContent().length() == 30 )
		assert( ls.getContent().endsWith( "..."))
		// and a short one
		ls = new LimitedStringBuilder( 5, " ..." )
		ls.append( "Rather stupid test")
		assert( ls.getContent().length() == 5 )
		assert( ls.getContent().startsWith( "R " ))
	}
}
