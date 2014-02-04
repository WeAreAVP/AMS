package gr.ntua.ivml.mint.concurrent.queue.util;



import gr.ntua.ivml.mint.util.Config;

import java.net.UnknownHostException;

import com.mongodb.DB;
import com.mongodb.Mongo;
import com.mongodb.MongoException;
import com.mongodb.gridfs.GridFS;

public class MongoDB {
	private static Mongo mongo = null;
	private static DB db = null;
	private static GridFS gridFS = null;
	
	static{
		String host = Config.get("mongo.host");
		int port = Integer.parseInt(Config.get("mongo.port"));
		String database = Config.get("mongo.db");
		
		String username = Config.get("mongo.username");
		String password = Config.get("mongo.password");
		boolean authenticate = !Config.getBoolean("mongo.noauth");
		
		try {
			mongo = new Mongo(host, port);
			db = mongo.getDB(database);
			gridFS = new GridFS( db );
			
			if(authenticate) {
				boolean auth = db.authenticate(username, password.toCharArray());
				if(!auth) {
					System.err.println("MongoDB authentication failed");					
				}
			}
		} catch (UnknownHostException e) {
			e.printStackTrace();
		} catch (MongoException e) {
			e.printStackTrace();
		}
		
		System.out.println("MongoDB connection started");
	}

	
	public static DB getDB(){
		return db;
	}
	
	public static Mongo getMongo(){
		return mongo;
	}
	
	public static GridFS getGridFS(){
		return gridFS;
	}
}
