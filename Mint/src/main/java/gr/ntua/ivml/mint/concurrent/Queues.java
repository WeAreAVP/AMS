package gr.ntua.ivml.mint.concurrent;

import gr.ntua.ivml.mint.persistent.DataUpload;
import gr.ntua.ivml.mint.persistent.Transformation;

import java.util.HashMap;
import java.util.Iterator;
import java.util.Map;
import java.util.Map.Entry;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;
import java.util.concurrent.Future;

import org.apache.log4j.Logger;

/**
 * Use queue to put your runnables into the appropriate queue. Add one if you think
 * you need your own.
 * @author Arne Stabenau 
 *
 */
public class Queues {
	
	static Map<String, ExecutorService> queues = new HashMap<String,ExecutorService>();
	static Map<Runnable, Future<?>> futures = new HashMap<Runnable, Future<?>>();
	// only one queue at the moment
	static {
		// db queue allows 2 parallel jobs on db heavy stuff
		// transformation goes here and upload part two ( parse and index )
		ExecutorService e = Executors.newFixedThreadPool(2);
		queues.put( "db", e);
		// net queue allows for parallel network heavy operations
		// upload part one goes here (with blob to db part)
		e = Executors.newFixedThreadPool(4);
		queues.put( "net", e);
		
		// for stuff that is not thread safe
		e = Executors.newFixedThreadPool(1);
		queues.put( "single", e);
		
		// Jobs that need a thread very often and immediately use this one
		e =  Executors.newCachedThreadPool();
		queues.put( "now", e);
	}

	static Logger log = Logger.getLogger(Queues.class);
	
	/**
	 * Lookup of the queue and put the Job there.
	 * 
	 * "db" - queue for database heavy tasks (parsing / indexing)
	 * "net" - queue for network access (currently oai / ftp / http download )
	 * "now" - queue for simple thread pool access and immediate execution
	 * @param r
	 * @param queueName
	 */
	public synchronized static void queue( Runnable r, String queueName ) {
		ExecutorService es = queues.get( queueName );
		log.info( "Submitting "+ r.getClass().getCanonicalName() + " to " + queueName );
		Future<?> f = es.submit(r);
		
		// cleanup the futures
		Iterator<Entry<Runnable,Future<?>>> i = futures.entrySet().iterator();
		while( i.hasNext()) {
			Entry<Runnable,Future<?>> e = i.next();
			if( e.getValue().isDone())
				i.remove();
		}
		// put the next
		futures.put( r, f );
	} 
	
	/**
	 * Convenience to queue transform. Its db heavy, so goes to db queue
	 * @param tr
	 */
	public static void queueTransformation(Transformation tr){
		XSLTransform ts = new XSLTransform(tr);
		queue( ts, "db" );
	}

	/**
	 * Try to cancel an upload. If its in the Queue or running
	 * this will do something, but it might not be here yet...
	 * It might still be in its own thread.
	 * @param du
	 */
	public synchronized static boolean cancelUpload( DataUpload du ) {
		Long id = du.getDbID();
		boolean success = false;
		for( Entry<Runnable, Future<?>> e: futures.entrySet()) {
			try {
				Runnable r = e.getKey();
				if( r instanceof UploadIndexer ) {
					UploadIndexer ui = (UploadIndexer) r;
					if( ui.getDataUpload().getDbID().equals( du.getDbID())) {
						success = e.getValue().cancel(true);
					}
				}
			} catch( Exception e2 ) {
				log.error( "couldnt cancel Upload "+du.getDbID(), e2);
			}
		}
		return success;
	}
	
	// TODO: This might work simply with Hash access .. :-) too scared to try
	private static Future<?> getFuture( Runnable r ) {
		for( Entry<Runnable, Future<?>> e: futures.entrySet()) 
			if( e.getKey() == r ) return e.getValue();
		return null;
	}
	
	
	/**
	 * Wait until the given Runnable has finished.
	 * @param r
	 */
	public static void join( Runnable r ) {
		Future<?> f = getFuture(r);
		if( f == null ) return;
		else {
			try {
				log.debug( "Waiting for "+ (r.getClass().toString()));
				f.get();
				log.debug( "Done");
			} catch( Exception e ) {
				log.error( "Task didnt complete well ", e );
			}
		}
	}
}
