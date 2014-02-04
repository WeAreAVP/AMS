package gr.ntua.ivml.mint.harvesting.util;


import gr.ntua.ivml.mint.harvesting.SingleHarvester;

public class Test {

	/**
	 * @param args
	 */
	public static void main(String[] args) throws Exception{

		SingleHarvester harv = new SingleHarvester("http://rhea.image.ece.ntua.gr:8080/oaicat/OAIHandler", null, null, "oai_dc", null);
        harv.harvest();
        
	}

}
