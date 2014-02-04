package gr.ntua.ivml.mint.xml.util;

public class ElementValueMap {
	
	private String Xpath;
	private String value;
	
	public ElementValueMap(String xpath, String value){
		this.Xpath = xpath;
		this.value = value;
	}

	public String getXpath() {
		return Xpath;
	}

	public void setXpath(String xpath) {
		Xpath = xpath;
	}

	public String getValue() {
		return value;
	}

	public void setValue(String value) {
		this.value = value;
	}

}
