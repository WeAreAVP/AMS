package gr.ntua.ivml.mint.concurrent.queue.consumers;

import gr.ntua.ivml.mint.concurrent.queue.util.OAIRepositoryManager;
import gr.ntua.ivml.mint.concurrent.queue.util.SHA1Generator;

import java.io.IOException;
import java.io.UnsupportedEncodingException;
import java.util.HashMap;

import com.rabbitmq.client.Channel;
import com.rabbitmq.client.Connection;
import com.rabbitmq.client.ConnectionFactory;
import com.rabbitmq.client.ConsumerCancelledException;
import com.rabbitmq.client.QueueingConsumer;
import com.rabbitmq.client.ShutdownSignalException;

public class PublicationItemConsumer implements Runnable{
	
	private String queueHost;
	private String queueName;
	private ConnectionFactory factory;
	private Connection connection;
	private Channel channel;
	private QueueingConsumer consumer;
	private QueueingConsumer.Delivery delivery;
	
	private HashMap<String, Object> header;
	private OAIRepositoryManager manager;
	private SHA1Generator gen;
	
	private String orgName;
	//private boolean isLastItem;
	private String publicationDate;
	private String prefix;
	private String payload;
	private String reportId;
	
	public PublicationItemConsumer(){
		gen = new SHA1Generator();
		manager = new OAIRepositoryManager();
		factory = new ConnectionFactory();
		
		queueHost = gr.ntua.ivml.mint.util.Config.get("oai.queue.host");
		queueName = gr.ntua.ivml.mint.util.Config.get("oai.queue.name");
		
	    factory.setHost(queueHost);
	    
	    try {
			connection = factory.newConnection();
		    channel = connection.createChannel();
		    channel.queueDeclare(queueName, true, false, false, null);
		    channel.basicQos(1);
		    consumer = new QueueingConsumer(channel);
		    channel.basicConsume(queueName, false, consumer);
		} catch (IOException e) {
			e.printStackTrace();
		}
	}

	private String cleanXML(String xml){
		int ind = xml.indexOf("?>");
		xml = xml.substring(ind+2);
		return xml;
	}
	
	@Override
	public void run() {
		while(true){
			try {
				delivery = consumer.nextDelivery();
				header = (HashMap<String, Object>)delivery.getProperties().getHeaders();
				
				this.orgName = header.get("orgName").toString();
				this.prefix = header.get("prefix").toString();
				this.publicationDate = header.get("publicationDate").toString();
				//this.isLastItem = (Boolean)header.get("isLastItem");
				this.reportId = header.get("reportId").toString();
				payload = new String(delivery.getBody(), "UTF-8");
				payload = this.cleanXML(payload);
				String hash = gen.generate(payload);
				if(manager.itemExists(hash, this.orgName)){
					manager.addConflictedItem(hash, this.reportId, this.orgName);
					//manager.increaseConflictedItems(this.reportId);
					//manager.increaseTotalItems(this.reportId);
				}else{
					manager.addItem(hash, payload, this.orgName, this.prefix);
					//manager.increaseInsertedItems(this.reportId);
					//manager.increaseTotalItems(this.reportId);
				}
				/*if(isLastItem){
					manager.closeReport(this.reportId);
					System.out.println("Last Item Found");
				}*/
				try {
					channel.basicAck(delivery.getEnvelope().getDeliveryTag(), false);
				} catch (IOException e) {
					e.printStackTrace();
				}
				
			} catch (ShutdownSignalException e) {
				e.printStackTrace();
			} catch (ConsumerCancelledException e) {
				e.printStackTrace();
			} catch (InterruptedException e) {
				e.printStackTrace();
			} catch (UnsupportedEncodingException e) {
				e.printStackTrace();
			}
		}
		
	}
}
