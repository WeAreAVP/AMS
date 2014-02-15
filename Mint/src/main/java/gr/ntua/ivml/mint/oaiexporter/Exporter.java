package gr.ntua.ivml.mint.oaiexporter;

import java.io.IOException;

public class Exporter {

	/**
	 * @param args
	 * @throws IOException 
	 */
	public static void main(String[] args) throws IOException {
		PublicationIterator.iteratePublications();
	}

}
