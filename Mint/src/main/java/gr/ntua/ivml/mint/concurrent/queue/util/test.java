package gr.ntua.ivml.mint.concurrent.queue.util;

import gr.ntua.ivml.mint.concurrent.queue.consumers.PublicationItemConsumer;
import gr.ntua.ivml.mint.concurrent.queue.consumers.RPCConsumer;
import gr.ntua.ivml.mint.concurrent.queue.producers.PublicationItemProducer;
import gr.ntua.ivml.mint.concurrent.queue.producers.RPCProducer;
import gr.ntua.ivml.mint.concurrent.queue.util.OAIRepositoryManager;

import java.text.ParseException;

import net.sf.json.JSONObject;

public class test {

	/**
	 * @param args
	 * @throws ParseException 
	 */
	public static void main(String[] args) throws ParseException {
		// TODO Auto-generated method stub
		//OAIRepositoryManager man = new OAIRepositoryManager();
		//man.deleteReportsByOrg("DSI, University of Florence");
		//man.deleteDocumentsByOrg("DSI,_University_of_Florence");
		//PublicationItemProducer prod = new PublicationItemProducer();
		//prod.initReport("lala", "2011-06-06 16:15:33");
		//prod.resetSet("lala");
		//prod.sendItem("payload", "lala", true, "2011-06-06 16:15:33");
		//prod.close();
		//String lala = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>Whatever";
		//int ind = lala.indexOf("?>");
		//System.out.println(lala.substring(ind+2));
		/*PublicationItemConsumer con = new PublicationItemConsumer();
		Thread thread = new Thread(con, "lala1");
		thread.start();
		PublicationItemConsumer con1 = new PublicationItemConsumer();
		Thread thread1 = new Thread(con1, "lala2");
		thread1.start();
		PublicationItemConsumer con2 = new PublicationItemConsumer();
		Thread thread2 = new Thread(con2, "lala3");
		thread2.start();
		PublicationItemConsumer con3 = new PublicationItemConsumer();
		Thread thread3 = new Thread(con3, "lala4");
		thread3.start();*/

		RPCConsumer conR = new RPCConsumer();
		Thread threadR = new Thread(conR, "RPC#1");
		threadR.start();
		
		for(int i = 0;i < 10;i++){
			PublicationItemConsumer con = new PublicationItemConsumer();
			Thread thread = new Thread(con, "oaiConsumer#"+i);
			thread.start();
		}
		//String id = man.initReport("lala", "add", "2011-06-06 16:15:33");
		//man.increaseTotalItems(id);
		//man.increaseConflictedItems(id);
		//man.increaseInsertedItems(id);
		//man.closeReport(id);
	}

}
