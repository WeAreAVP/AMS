package gr.ntua.ivml.mint.concurrent.queue.util;

import java.text.DateFormat;
import java.text.ParseException;
import java.text.SimpleDateFormat;

import org.bson.types.ObjectId;

import com.mongodb.BasicDBObject;
import com.mongodb.DBCollection;
import com.mongodb.util.JSON;

public class OAIRepositoryManager {
	
	private DBCollection registry;
	private DBCollection reports;
	private DBCollection conflicts;
	
	public OAIRepositoryManager(){
		this.registry = MongoDB.getDB().getCollection("registry");
		this.reports = MongoDB.getDB().getCollection("reports");
		this.conflicts = MongoDB.getDB().getCollection("conflicts");
	}
	
	public boolean itemExists(String hash, String orgName){

		boolean result = false;
		BasicDBObject query = new BasicDBObject();
		query.put("id", hash);
		query.put("SetSpec", orgName);
		BasicDBObject res = (BasicDBObject)this.registry.findOne(query);
		//System.out.println(res);
		if(res != null){
			result = true;
		}else{
			result = false;
		}
		
		return result;
	}
	
	public void deleteReportsByOrg(String orgName){
		BasicDBObject query = new BasicDBObject();
		query.put("orgName", orgName);
		this.reports.remove(query);
	}
	
	public void deleteDocumentsByOrg(String orgName){
		BasicDBObject query = new BasicDBObject();
		query.put("SetSpec", orgName);
		this.registry.remove(query);
	}
	
	public void deleteConflictedItemsByOrg(String orgName){

		BasicDBObject query = new BasicDBObject();
		query.put("orgName", orgName);
		this.conflicts.remove(query);
	}
	
	public String initReport(String orgName, String type, String publicationDate, String publicationId) throws ParseException{
		BasicDBObject doc = new BasicDBObject();
		doc.put("orgName", orgName);
		doc.put("type", type);
		doc.put("publicationId", publicationId);
		//doc.put(key, val);
		DateFormat dateFormat = new SimpleDateFormat("yyyy/MM/dd HH:mm:ss");
		java.util.Date date = new java.util.Date();
		String dateTime = dateFormat.format(date);
 		String[] dates = dateTime.split(" ");
 		BasicDBObject times = new BasicDBObject();
 		times.put("date", dates[0]);
 		times.put("time", dates[1]);
 		doc.put("created", times);
 		
 		DateFormat dateFormat1 = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
 		java.util.Date date1 = dateFormat1.parse(publicationDate);
 		String pubDate = dateFormat1.format(date1);
 		String[] pubDates = pubDate.split(" ");
 		BasicDBObject pubTimes = new BasicDBObject();
 		pubTimes.put("date", pubDates[0]);
 		pubTimes.put("time", pubDates[1]);
 		doc.put("publicationDate", pubTimes);
 		
 		doc.put("InsertedNumber", 0);
 		doc.put("ConflictsNumber", 0);
 		doc.put("TotalItems", 0);
		this.reports.insert(doc);
		return doc.getString("_id");
	}
	
	public void closeReport(String documentId){
		ObjectId oId = new ObjectId(documentId);
		BasicDBObject doc = (BasicDBObject)this.reports.findOne(oId);
		DateFormat dateFormat = new SimpleDateFormat("yyyy/MM/dd HH:mm:ss");
		java.util.Date date = new java.util.Date();
		String dateTime = dateFormat.format(date);
 		String[] dates = dateTime.split(" ");
 		BasicDBObject times = new BasicDBObject();
 		times.put("date", dates[0]);
 		times.put("time", dates[1]);
 		doc.put("closed", times);
 		this.reports.save(doc);
	}
	
	public String fetchReport(String reportId){
		String res = null;
		ObjectId oId = new ObjectId(reportId);
		BasicDBObject doc = (BasicDBObject)this.reports.findOne(oId);
		if(doc != null){
			String name = doc.getString("orgName");
			int countC = this.countConflictedItemsByOrgName(name);
			int countI = this.countImportedItemsByOrgName(name);
			this.increaseConflictedItems(reportId, countC);
			this.increaseInsertedItems(reportId, countI);
			this.increaseTotalItems(reportId, countI+countC);
			doc = (BasicDBObject)this.reports.findOne(oId);
			res = JSON.serialize(doc);
		}
		return res;
	}
	
	private int countImportedItemsByOrgName(String orgName){
		int res = 0;
		BasicDBObject query = new BasicDBObject();
		query.put("SetSpec", orgName);
		res = this.registry.find(query).count();
		return res;
	}
	
	private int countConflictedItemsByOrgName(String orgName){
		int res = 0;
		BasicDBObject query = new BasicDBObject();
		query.put("orgName", orgName);
		res = this.conflicts.find(query).count();
		return res;
	}
	
	private void increaseTotalItems(String reportId, int n){
		BasicDBObject updateQuery = new BasicDBObject("$set", new BasicDBObject("TotalItems", n));
		this.reports.update(new BasicDBObject("_id", new ObjectId(reportId)), updateQuery);
	}
	
	private void increaseConflictedItems(String reportId, int n){
		BasicDBObject updateQuery = new BasicDBObject("$set", new BasicDBObject("ConflictsNumber", n));
		this.reports.update(new BasicDBObject("_id", new ObjectId(reportId)), updateQuery);
	}
	
	private void increaseInsertedItems(String reportId, int n){
		BasicDBObject updateQuery = new BasicDBObject("$set", new BasicDBObject("InsertedNumber", n));
		this.reports.update(new BasicDBObject("_id", new ObjectId(reportId)), updateQuery);
	}
	
	public void increaseTotalItems(String reportId){
		BasicDBObject updateQuery = new BasicDBObject("$inc", new BasicDBObject("TotalItems", 1));
		this.reports.update(new BasicDBObject("_id", new ObjectId(reportId)), updateQuery);
	}
	
	public void increaseConflictedItems(String reportId){
		BasicDBObject updateQuery = new BasicDBObject("$inc", new BasicDBObject("ConflictsNumber", 1));
		this.reports.update(new BasicDBObject("_id", new ObjectId(reportId)), updateQuery);
	}
	
	public void increaseInsertedItems(String reportId){
		BasicDBObject updateQuery = new BasicDBObject("$inc", new BasicDBObject("InsertedNumber", 1));
		this.reports.update(new BasicDBObject("_id", new ObjectId(reportId)), updateQuery);
	}
	
	public void addItem(String hash, String value, String SetSpec, String prefix){
		BasicDBObject doc = new BasicDBObject();
		doc.put("id", hash);
		doc.put("value", value);
		doc.put("SetSpec", SetSpec);
		doc.put("prefix", prefix);
		DateFormat dateFormat = new SimpleDateFormat("yyyy/MM/dd HH:mm:ss");
		java.util.Date date = new java.util.Date();
		String dateTime = dateFormat.format(date);
 		String[] dates = dateTime.split(" ");
 		BasicDBObject times = new BasicDBObject();
 		times.put("date", dates[0]);
 		times.put("time", dates[1]);
 		doc.put("datestamp", times);
 		
		this.registry.insert(doc);
	}
	
	public void addConflictedItem(String hash, String reportId, String orgName){
		BasicDBObject doc = new BasicDBObject();
		doc.put("hash", hash);
		doc.put("reportId", reportId);
		doc.put("orgName", orgName);
		this.conflicts.insert(doc);
	}
}
