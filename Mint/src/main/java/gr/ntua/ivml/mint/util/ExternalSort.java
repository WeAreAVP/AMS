package gr.ntua.ivml.mint.util;

// filename: ExternalSort.java
import java.util.*;
import java.io.*;

/**
* Goal: offer a generic external-memory sorting program in Java.
* 
* It must be : 
*  - hackable (easy to adapt)
*  - scalable to large files
*  - sensibly efficient.
*
* This software is in the public domain.
*
* By (in alphabetical order) 
*   Philippe Beaudoin,  Jon Elsas,  Christan Grant, Daniel Haran, Daniel Lemire, 
*  April 2010
* originally posted at 
*  http://www.daniel-lemire.com/blog/archives/2010/04/01/external-memory-sorting-in-java/
*/
public class ExternalSort {
	
	// how many files to merge maximally
	public static int NWAYMERGE = 16;
	
	// buffer to use in the n-way-merge
	public static int NWAYBUFFER = (1<<20);
	
	// how much memory we want to use
	public static long estimateBestSizeOfBlocks() {
		Runtime.getRuntime().gc();
		long freemem = Runtime.getRuntime().freeMemory();
		return (long) (freemem*0.7f);
	}

	/**
	 * This will simply load the file by blocks of x rows, then
	 * sort them in-memory, and write the result to a bunch of 
	 * temporary files that have to be merged later.
	 * 
	 * If the sort can fit into memory it just writes the output straight away
	 * @param file some flat  file
	 * @return a list of temporary flat files
	 */
	public static List<File> sortInBatch(Reader inFile, Writer outFile, Comparator<String> cmp) throws IOException {
		List<File> files = new ArrayList<File>();
		BufferedReader fbr = new BufferedReader( inFile );
		long blocksize = estimateBestSizeOfBlocks();// in bytes
		System.out.println( "Using " + blocksize + " bytes of memory");
		
		try{
			List<String> tmplist =  new ArrayList<String>();
			List<String> pass = new ArrayList<String>();
			String line = "";
			try {
				while(line != null) {
					long currentblocksize = 0;// in bytes
					while((currentblocksize < blocksize) 
					&&(   (line = fbr.readLine()) != null) ){ // as long as you have 2MB
						if( line.startsWith("#")) 
							// pass through to outfile
							pass.add( line );
						else {
							tmplist.add(line);
							currentblocksize += line.length() * 2; // java uses 16 bits per character?
							currentblocksize += 16; // plus some object overhead in the list
						}
					}
					
					BufferedWriter bw = new BufferedWriter( outFile );
					for( String comment: pass ) {
						bw.write( comment );
						bw.newLine();
					}
					pass.clear();
					bw.close();
					
					if(( line == null ) && files.isEmpty()) {
						sortAndSave( tmplist, cmp, outFile );
					} else {
						File tmpFile = File.createTempFile("sortInBatch", "flatfile");
						tmpFile.deleteOnExit();
						Writer tmpWriter = new FileWriter( tmpFile );
						sortAndSave(tmplist,cmp, tmpWriter);
						files.add( tmpFile );
						tmplist.clear();
					}
				}
			} catch(EOFException oef) {
				if(tmplist.size()>0) {
					File tmpFile = File.createTempFile("sortInBatch", "flatfile");
					tmpFile.deleteOnExit();
					Writer tmpWriter = new FileWriter( tmpFile );
					sortAndSave(tmplist,cmp, tmpWriter);
					files.add( tmpFile );
					tmplist.clear();
				}
			}
		} finally {
			fbr.close();
		}
		return files;
	}


	public static void sortAndSave(List<String> tmplist, Comparator<String> cmp, Writer outFile ) throws IOException  {
		Collections.sort(tmplist,cmp);  // 
		BufferedWriter fbw = new BufferedWriter(outFile );
		try {
			for(String r : tmplist) {
				fbw.write(r);
				fbw.newLine();
			}
		} finally {
			fbw.close();
		}
	}
	/**
	 * This merges a bunch of temporary flat files. This might be a bit chaotic if there are a thousand files.
	 * Sending the head around a lot. Better do n way merges with big buffers.
	 * @param files
	 * @param output file
         * @return The number of lines sorted. (P. Beaudoin)
	 */
	public static int mergeSortedFiles(List<File> files, Writer outputWriter, final Comparator<String> cmp) throws IOException {
		PriorityQueue<BinaryFileBuffer> pq = new PriorityQueue<BinaryFileBuffer>(11, 
            new Comparator<BinaryFileBuffer>() {
              public int compare(BinaryFileBuffer i, BinaryFileBuffer j) {
                return cmp.compare(i.peek(), j.peek());
              }
            }
        );
		
		ArrayDeque<File> toDoFiles = new ArrayDeque<File>();
		toDoFiles.addAll( files );
		int rowCounter = 0;
		while( ! toDoFiles.isEmpty() ) {
			while( !toDoFiles.isEmpty() && pq.size()<NWAYMERGE) {
				File f = toDoFiles.pollFirst();
				BinaryFileBuffer bfb = new BinaryFileBuffer(f, NWAYBUFFER);
				pq.add(bfb);
			}		

			BufferedWriter fbw;
			if( toDoFiles.isEmpty()) {
				fbw = new BufferedWriter( outputWriter );
				rowCounter=0;
			} else {
				File tmpFile =  File.createTempFile("sortInBatch", "flatfile");
				tmpFile.deleteOnExit();
				fbw = new BufferedWriter(new FileWriter(tmpFile));
				toDoFiles.addLast( tmpFile );
			}

			try {
				while(pq.size()>0) {
					BinaryFileBuffer bfb = pq.poll();
					String r = bfb.pop();
					fbw.write(r);
					fbw.newLine();
					++rowCounter;
					if(bfb.empty()) {
						bfb.fbr.close();
						bfb.originalfile.delete();// we don't need you anymore
					} else {
						pq.add(bfb); // add it back
					}
				}
			} finally { 
				fbw.close();
				for(BinaryFileBuffer bfb : pq ) bfb.close();
				// make sure everything is deleted in case of exception
				for( File f: files ) {
					try {
						if(( f != null) && ( f.exists())) f.delete();
					} catch( Exception e ) { 
						// ignore them, its just the garbage
					}
				}
			}
		}
		return rowCounter;
	}

	/**
	 * A File version of the Reader Writer sort.
	 * @param in
	 * @param out
	 * @param c
	 * @throws IOException
	 */
	public static void sort( File in, File out, Comparator<String> c ) throws IOException {
		Reader r = new FileReader( in );
		Writer w = new FileWriter( out );
		sort( r,w,c);
	}
	
	/**
	 * Uses external memory (tmp files) to sort the reader into the writer.
	 * If everything fits in the memory, doesnt use tmp-files (obviously).
	 * Your line Comparator needs to be efficient (most critical part).
	 * @param in
	 * @param out
	 * @param c - Compares lines, as elaborate or as simple as you like.
	 * @throws IOException
	 */
	public static void sort( Reader in, Writer out, Comparator<String> c ) throws IOException {
		List<File> l = sortInBatch(in, out, c) ;
		if( !l.isEmpty())
			mergeSortedFiles(l, out, c);
	}
	
	public static void main( String[] args ) {
		long start = System.currentTimeMillis();
		File in = new File( "testfile" );
		File out = new File( "testfile.sort" );
		TabbedStringComparator c = new TabbedStringComparator();
		c.addKey( 2, true, false );
		try {
			sort( in, out, c );
		} catch( Exception e ) {
			e.printStackTrace();
		}
		System.out.printf("%5.3f secs", (System.currentTimeMillis()-start)/1000d );
	}
 }


class BinaryFileBuffer  {
	public BufferedReader fbr;
	public File originalfile;
	private String cache;
	private boolean empty;
	
	public BinaryFileBuffer(File f, int bufferSize ) throws IOException {
		originalfile = f;
		fbr = new BufferedReader(new FileReader(f), bufferSize );
		reload();
	}
	
	public boolean empty() {
		return empty;
	}
	
	private void reload() throws IOException {
		try {
          if((this.cache = fbr.readLine()) == null){
            empty = true;
            cache = null;
          }
          else{
            empty = false;
          }
      } catch(EOFException oef) {
        empty = true;
        cache = null;
      }
	}
	
	public void close() throws IOException {
		fbr.close();
	}
	
	
	public String peek() {
		if(empty()) return null;
		return cache.toString();
	}
	public String pop() throws IOException {
	  String answer = peek();
		reload();
	  return answer;
	}
	
	

}