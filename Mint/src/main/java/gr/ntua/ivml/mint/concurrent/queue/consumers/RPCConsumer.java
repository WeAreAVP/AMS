package gr.ntua.ivml.mint.concurrent.queue.consumers;

import java.io.IOException;
import java.io.UnsupportedEncodingException;
import java.text.ParseException;

import net.sf.json.JSONObject;
import net.sf.json.JSONSerializer;


import com.rabbitmq.client.AMQP.BasicProperties;
import com.rabbitmq.client.Channel;
import com.rabbitmq.client.Connection;
import com.rabbitmq.client.ConnectionFactory;
import com.rabbitmq.client.ConsumerCancelledException;
import com.rabbitmq.client.QueueingConsumer;
import com.rabbitmq.client.ShutdownSignalException;

import gr.ntua.ivml.mint.concurrent.queue.util.OAIRepositoryManager;
import gr.ntua.ivml.mint.util.Config;

public class RPCConsumer implements Runnable{

	private static final String RPC_QUEUE_NAME = Config.get("oai.rpc.queue.name");
	private ConnectionFactory factory;
	private Connection connection;
	private Channel channel;
	private QueueingConsumer consumer;
	private BasicProperties props;
	private BasicProperties replyProps;
	private OAIRepositoryManager manager;
	
	public RPCConsumer(){
		manager = new OAIRepositoryManager();
		
		factory = new ConnectionFactory();
		factory.setHost(Config.get("oai.rpc.queue.host"));

		try {
			connection = factory.newConnection();
			channel = connection.createChannel();
			channel.queueDeclare(RPC_QUEUE_NAME, false, false, false, null);
			channel.basicQos(1);
			consumer = new QueueingConsumer(channel);
			channel.basicConsume(RPC_QUEUE_NAME, false, consumer);
		} catch (IOException e) {
			e.printStackTrace();
		}
		
	}
	
	private String initReport(String orgName, String publicationDate, String publicationId){
		String res = null;
		try {
			res = manager.initReport(orgName.replace(" ", "_"), "add", publicationDate, publicationId);
		} catch (ParseException e) {
			e.printStackTrace();
		}
		return res;
	}
	
	private void resetSet(String orgName){
		manager.deleteDocumentsByOrg(orgName.replace(" ", "_"));
		manager.deleteConflictedItemsByOrg(orgName.replace(" ", "_"));
	}
	
	private String closeReport(String reportId){
		String res = null;
		manager.closeReport(reportId);
		res = manager.fetchReport(reportId);
		return res;
	}
	
	private String fetchReport(String reportId){
		String res = null;
		res = manager.fetchReport(reportId);
		return res;
	}
	
	@Override
	public void run() {
		while (true) {
		    try {
			    QueueingConsumer.Delivery delivery = consumer.nextDelivery();

			    props = delivery.getProperties();
			    replyProps = new BasicProperties
			                                     .Builder()
			                                     .correlationId(props.getCorrelationId())
			                                     .build();
			    String message = null;
				message = new String(delivery.getBody(), "UTF-8");
				JSONObject jsonObject = (JSONObject) JSONSerializer.toJSON( message );
				String response = null;
				
				if(jsonObject.getString("type").equals("init")){
					response = initReport(jsonObject.getString("orgName"), jsonObject.getString("publicationDate"),
											jsonObject.getString("publicationId"));
				}else if(jsonObject.getString("type").equals("delete")){
					resetSet(jsonObject.getString("orgName"));
				}else if(jsonObject.getString("type").equals("close")){
					response = closeReport(jsonObject.getString("reportId"));
				}else if(jsonObject.getString("type").equals("fetch")){
					response = fetchReport(jsonObject.getString("reportId"));
				}
				if(response == null){
					response = " ";
				}
			    
			    channel.basicPublish( "", props.getReplyTo(), replyProps, response.getBytes());

			    channel.basicAck(delivery.getEnvelope().getDeliveryTag(), false);
			} catch (UnsupportedEncodingException e) {
				e.printStackTrace();
			} catch (IOException e) {
				e.printStackTrace();
			} catch (ShutdownSignalException e) {
				e.printStackTrace();
			} catch (ConsumerCancelledException e) {
				e.printStackTrace();
			} catch (InterruptedException e) {
				e.printStackTrace();
			}
		}
	}

}
