package gr.ntua.ivml.mint.harvesting.concurrent;

import gr.ntua.ivml.mint.harvesting.Harvester;
import gr.ntua.ivml.mint.persistent.User;

import java.util.ArrayList;
import java.util.Iterator;
import java.util.concurrent.ArrayBlockingQueue;
import java.util.concurrent.BlockingQueue;
import java.util.concurrent.ThreadPoolExecutor;
import java.util.concurrent.TimeUnit;

public class HarvestingExecutor {

	private static ThreadPoolExecutor executor;
	private static BlockingQueue<Runnable> pendingTasksQueue;
	
	static{
		pendingTasksQueue = new ArrayBlockingQueue<Runnable>(100);
		executor = new ThreadPoolExecutor(8, 16, 1000, TimeUnit.MILLISECONDS, pendingTasksQueue);
	}
	
	public static void executeSigleHarvester(String url){
		Harvester harvester = new Harvester(url);
		executor.execute(harvester);
	}
	
	public static void executeSigleHarvester(String url, User user){
		Harvester harvester = new Harvester(url, user);
		executor.execute(harvester);
	}
	
	public static void executeMultipleHarvesters(ArrayList<String> urls){
		Iterator<String> itr = urls.iterator();
		while(itr.hasNext()){
			Harvester harvester = new Harvester(itr.next());
			executor.execute(harvester);
		}
	}
	
	public static void shutdown(){
		executor.shutdown();
	}
}

	