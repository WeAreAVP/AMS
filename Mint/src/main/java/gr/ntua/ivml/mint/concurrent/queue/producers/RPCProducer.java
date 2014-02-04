package gr.ntua.ivml.mint.concurrent.queue.producers;

import net.sf.json.JSONObject;
import gr.ntua.ivml.mint.util.Config;

import com.rabbitmq.client.AMQP.BasicProperties;
import com.rabbitmq.client.Channel;
import com.rabbitmq.client.Connection;
import com.rabbitmq.client.ConnectionFactory;
import com.rabbitmq.client.QueueingConsumer;

public class RPCProducer {
	private Connection connection;
	private Channel channel;
	private String requestQueueName = Config.get("oai.rpc.queue.name");
	private String replyQueueName;
	private QueueingConsumer consumer;
	private JSONObject jObject;
	
	public RPCProducer() throws Exception {
	    ConnectionFactory factory = new ConnectionFactory();
	    factory.setHost(Config.get("oai.rpc.queue.host"));
	    connection = factory.newConnection();
	    channel = connection.createChannel();

	    replyQueueName = channel.queueDeclare().getQueue(); 
	    consumer = new QueueingConsumer(channel);
	    channel.basicConsume(replyQueueName, true, consumer);
	}

	private String call(String message) throws Exception {     
	    String response = null;
	    String corrId = java.util.UUID.randomUUID().toString();
	    
	    BasicProperties props = new BasicProperties
	                                .Builder()
	                                .correlationId(corrId)
	                                .replyTo(replyQueueName)
	                                .build();

	    channel.basicPublish("", requestQueueName, props, message.getBytes());

	    while (true) {
	        QueueingConsumer.Delivery delivery = consumer.nextDelivery();
	        if (delivery.getProperties().getCorrelationId().equals(corrId)) {
	            response = new String(delivery.getBody());
	            break;
	        }
	    }

	    return response; 
	}

	public String initReport(String orgName, String publicationDate, String publicationId){
		String res = null;
		jObject = new JSONObject();
		jObject.element("orgName", orgName).element("publicationDate", publicationDate)
				.element("publicationId", publicationId).element("type", "init");
		try {
			res = this.call(jObject.toString());
		} catch (Exception e) {
			e.printStackTrace();
		}
		return res;
	}
	
	public void resetSet(String orgName){
		jObject = new JSONObject();
		jObject.element("type", "delete").element("orgName", orgName);
		try {
			this.call(jObject.toString());
		} catch (Exception e) {
			e.printStackTrace();
		}
	}
	
	public String closeReport(String reportId){
		String res = null;
		jObject = new JSONObject();
		jObject.element("type", "close").element("reportId", reportId);
		try {
			res = this.call(jObject.toString());
		} catch (Exception e) {
			e.printStackTrace();
		}
		return res;
	}
	
	public String fetchReport(String reportId){
		String res = null;
		jObject = new JSONObject();
		jObject.element("type", "fetch").element("reportId", reportId);
		try {
			res = this.call(jObject.toString());
		} catch (Exception e) {
			e.printStackTrace();
		}
		return res;
	}
	
	public void close() throws Exception {
	    connection.close();
	}
}
