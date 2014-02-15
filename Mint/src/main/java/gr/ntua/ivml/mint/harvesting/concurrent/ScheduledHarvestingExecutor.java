package gr.ntua.ivml.mint.harvesting.concurrent;

import gr.ntua.ivml.mint.harvesting.Harvester;

import java.util.Date;
import java.util.concurrent.Executors;
import java.util.concurrent.ScheduledExecutorService;
import java.util.concurrent.TimeUnit;

public class ScheduledHarvestingExecutor {
	
	 private static final ScheduledExecutorService scheduler; 
	       
	 static{
		 scheduler = Executors.newScheduledThreadPool(100);
	 }
	 
	 public static void addTask(Harvester harvester, long startTime, long period){
		 scheduler.scheduleWithFixedDelay(harvester, startTime, period,  TimeUnit.MILLISECONDS);
	 }
	 
	 public static void addTask(Harvester harvester, Date startTime, long period){
		 scheduler.scheduleWithFixedDelay(harvester, startTime.getTime() - System.currentTimeMillis(), period, TimeUnit.MILLISECONDS); 
	 }
}
