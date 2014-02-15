package gr.ntua.ivml.mint.concurrent;

import java.util.Timer;
import java.util.TimerTask;

/**
 * Simple class to enable regular reports.
 * Use like this:
 * t = new Ticker( 60 )  //every 60 seconds the Ticker becomes set.
 * if( t.isSet() ) { t.reset(), report } t.cancel()
 * 
 * 
 * @author Arne Stabenau 
 *
 */
public class Ticker extends TimerTask {
	private static final Timer t = new Timer();
	private boolean flag;
	private int seconds;
	private boolean restartOnReset;
	
	public Ticker( int seconds ) {
		flag = false;
		restartOnReset = false;
		this.seconds = seconds;
		t.schedule( this, seconds*1000l, seconds*1000l);
	}
	
	/**
	 * If restart on Reset is true, the ticker only starts counting from
	 * the last reset. 
	 * @param seconds
	 * @param restartOnReset
	 */
	public Ticker( int seconds, boolean restartOnReset ) {
		this.restartOnReset = restartOnReset;
		this.seconds = seconds;
		this.flag = true;
	}
	
	public void run() {
		flag = true;
	}

	public void reset() {
		flag = false;
		if( restartOnReset ) {
			t.schedule(this, seconds*1000l);
		}
	}
	public boolean isSet() {
		return flag;
	}
}
