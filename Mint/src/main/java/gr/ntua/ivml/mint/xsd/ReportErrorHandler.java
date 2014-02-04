package gr.ntua.ivml.mint.xsd;

import java.util.ArrayList;

import org.xml.sax.ErrorHandler;
import org.xml.sax.SAXException;
import org.xml.sax.SAXParseException;

import com.sun.org.apache.xerces.internal.impl.XMLErrorReporter;

public class ReportErrorHandler implements ErrorHandler {
	
	public ArrayList<SAXParseException> report = new ArrayList<SAXParseException>();
	
	private void handleException(SAXParseException e) {
		System.out.println(e.getMessage());
		report.add(e);
	}

	@Override
	public void error(SAXParseException e) throws SAXException {
		handleException(e);
	}

	@Override
	public void fatalError(SAXParseException e) throws SAXException {
		handleException(e);
	}

	@Override
	public void warning(SAXParseException e) throws SAXException {
		handleException(e);
	}

	public String getReportMessage() {
		StringBuffer result = new StringBuffer();
		if(isValid()) {
			return "XML is valid";
		} else {
			for(SAXParseException e: report) {
				result.append(e.getMessage());
				result.append("\n");
			}
		}
		
		return result.toString();
	}

	public boolean isValid() {
		return report.isEmpty();
	}

	public ArrayList<SAXParseException> getReport() {
		return report;
	}

}