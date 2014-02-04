package gr.ntua.ivml.mint.util;

import java.util.ArrayList;
import java.util.Comparator;

/**
 * For a typical tab delimited data file this class is a comparator.
 * Compare by many columns, numeric or not, ascending or descending.
 * 
 * @author Arne Stabenau 
 *
 */
public class TabbedStringComparator implements Comparator<String> {
	public class Sorter {
		public boolean numeric;
		public boolean descending;
		public int column;
		
		// vars to find the column indexes
		private int start, end;
		
		private final void columnIndex( String val ) {
			start = 0;
			int col = column;
			while(( col > 0 ) && ( start < val.length())) {
				if( val.charAt(start) == '\t' ) {
					col -= 1;
				}
				start += 1;
			}
			end = start;
			while((end < val.length()) && (val.charAt(end) != '\t')) 
				end += 1;
		}
		
		
		// compare line a and b according to this sorter
		public final int compare( String a, String b ) {
			
			columnIndex( a );
			int startA = start;
			int endA = end;
			columnIndex( b );
			int startB = start;
			int endB = end;
			
			int result = 0;
			if( numeric ) {
				double ad = 0d;
				try {
					ad = Double.parseDouble( a.substring(startA, endA+1) );
				} catch( Exception e ) {}
				
				double bd = 0d;
				try {
					bd = Double.parseDouble( b.substring( startB, endB+1 ));
				} catch( Exception e ) {}
				if( ad < bd ) result = -1;
				else if ( ad > bd ) result = 1;
			} else {
				result = a.substring(startA, endA+1).compareTo(b.substring( startB, endB+1 ));
			}
			
			if( descending ) result *= -1;
			return result;
		}
	}
	
	ArrayList<Sorter> sorters = new ArrayList<Sorter>();
	public void addKey( int column, boolean numeric, boolean descending ) {
		Sorter s = new Sorter();
		s.column = column;
		s.numeric = numeric;
		s.descending = descending;
		sorters.add( s );
	}

	@Override
	/**
	 * I tried to split on \t but apparently the String[] array was the killer.
	 * Not splitting and using ints to find the substrings is 
	 * (in memory testing) 4 times faster and almost as fast as the C sort.
	 */
	public final int compare(String o1, String o2) {
		for( Sorter s: sorters ) {
			int res = s.compare( o1, o2);
			if( res != 0 ) return res;
		}
		return 0;
	}
	
}
