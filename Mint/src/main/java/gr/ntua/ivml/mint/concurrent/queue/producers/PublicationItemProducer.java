package gr.ntua.ivml.mint.concurrent.queue.producers;

import gr.ntua.ivml.mint.util.Config;

import java.io.IOException;
import java.util.HashMap;



import com.rabbitmq.client.Channel;
import com.rabbitmq.client.Connection;
import com.rabbitmq.client.ConnectionFactory;
import com.rabbitmq.client.AMQP.BasicProperties;
import com.rabbitmq.client.AMQP.BasicProperties.Builder;

public class PublicationItemProducer {
	private String queueHost;
	private String queueName;
	private ConnectionFactory factory;
	private Connection connection;
	private Channel channel;
	private Builder builder;
	private BasicProperties pros;
	private HashMap<String, Object> header;
	//private OAIRepositoryManager manager;
	private String reportId;
	
	//the DocumentId of the report as it is retrieved by the RPCPRoducer.initiReport() method.
	public PublicationItemProducer(String reportId){
		queueHost = gr.ntua.ivml.mint.util.Config.get("oai.queue.host");
		queueName = gr.ntua.ivml.mint.util.Config.get("oai.queue.name");
		this.reportId = reportId;
		//manager = new OAIRepositoryManager();
		builder = new Builder();
		
		factory = new ConnectionFactory();
		factory.setHost(queueHost);
		try {
			connection = factory.newConnection();
			channel = connection.createChannel();
			channel.queueDeclare(queueName, true, false, false, null);
			
		} catch (IOException e) {
			e.printStackTrace();
		}
	}
	
	/*public void initReport(String orgName, String publicationDate, String publicationId){
		try {
			this.reportId = manager.initReport(orgName.replace(" ", "_"), "add", publicationDate, publicationId);
		} catch (ParseException e) {
			e.printStackTrace();
		}
	}*/
	
	//public void resetSet(String orgName){this.manager.deleteDocumentsByOrg(orgName.replace(" ", "_"));}
	
	public void sendItem(String xml, String orgName, String publicationDate){
		generateHeader(orgName, publicationDate);
		
		try {
			channel.basicPublish( "", queueName, 
			        pros,
			        xml.getBytes());
		} catch (IOException e) {
			e.printStackTrace();
		}
	}
	
	private void generateHeader(String orgName, String publicationDate){
		header = new HashMap<String, Object>();
		header.put("orgName", orgName.replace(" ", "_"));
		//header.put("isLastItem", isLastItem);
		header.put("publicationDate", publicationDate);
		header.put("prefix", Config.get("oai.prefix"));
		header.put("reportId", this.reportId);
		builder.headers(header);
		
		pros = builder.build();
	}
	
	public void close(){
		try {
			channel.close();
			connection.close();
		} catch (IOException e) {
			e.printStackTrace();
		}
	}
}
