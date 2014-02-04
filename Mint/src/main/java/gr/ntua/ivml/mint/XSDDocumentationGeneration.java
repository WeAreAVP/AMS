package gr.ntua.ivml.mint;


import java.io.File;
import java.io.FileWriter;

import javax.swing.JFileChooser;

import net.sf.json.JSONObject;

import gr.ntua.ivml.mint.xsd.*;

public class XSDDocumentationGeneration {

	/**
	 * @param args
	 */
	public static void main(String[] args) throws Exception{
		JFileChooser chooser = new JFileChooser();
		if(chooser.showOpenDialog(null) == JFileChooser.APPROVE_OPTION) {
			File selected = chooser.getSelectedFile();
			String path = selected.getAbsolutePath();
			XSDParser parser = new XSDParser(path);
			JSONObject documentation = parser.buildDocumentation();

			String output = selected.getParent() + "/documentation.json";
			FileWriter writer = new FileWriter(new File(output)); 
			writer.write(documentation.toString());
			writer.flush();
			writer.close();
		}
	}

}
