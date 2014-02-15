package gr.ntua.ivml.mint.xml.transform;

import gr.ntua.ivml.mint.actions.XMLPreview;
import gr.ntua.ivml.mint.mapping.MappingManager;
import gr.ntua.ivml.mint.persistent.XmlSchema;
import gr.ntua.ivml.mint.util.Config;
import gr.ntua.ivml.mint.util.StringUtils;

import java.io.File;
import java.util.ArrayList;
import java.util.Iterator;

import org.apache.log4j.Logger;

import net.sf.json.JSONArray;
import net.sf.json.JSONObject;
import net.sf.json.JSONSerializer;

public class ChainTransform {
	protected static final Logger log = Logger.getLogger( MappingManager.class);
	
	protected static final String TRANSFORM_CUSTOM = "custom";
	protected static final String TRANSFORM_XSL = "xsl";
	protected static final String TRANSFORM_HTML = "html";
	protected static final String TRANSFORM_JSP = "jsp";
	protected static final String TRANSFORM_RDF = "rdf";
	protected static final String TRANSFORM_TEXT = "text";

	public ChainTransform()
	{
	}
	
	public ArrayList<XMLPreview.PreviewTab> transform(String input, XmlSchema schema) throws Exception{
		JSONObject configuration = (JSONObject) JSONSerializer.toJSON(schema.getJsonConfig());

		if(configuration.has("preview")) {
			return this.transform(input, configuration.getJSONArray("preview"));
		}
		
		return new ArrayList<XMLPreview.PreviewTab>();
	}
	

	public ArrayList<XMLPreview.PreviewTab> transform(String input, JSONArray previews) {
		ArrayList<XMLPreview.PreviewTab> result = new ArrayList<XMLPreview.PreviewTab>();

		Iterator i = previews.iterator();
		while(i.hasNext()) {
			JSONObject preview = (JSONObject) i.next();
			
			log.debug("getting preview for : " + preview);
			
			// default preview tab values;
			String type = TRANSFORM_CUSTOM;
			String output_type = XMLPreview.PreviewTab.TYPE_TEXT;
			String url = null;
			String output = null;
			String label = "Preview";
			ItemTransform transform = null;

			XMLPreview.PreviewTab tab = null;

			// load preview tab values
			if(preview.has("type")) {
				type = preview.getString("type");
			}

			if(preview.has("label")) {
				label = preview.getString("label");
			}
			
			if(preview.has("output")) {
				output_type = preview.getString("output");
			}
			
			// initialise preview transform
			if(preview.has("xsl")) {
				String xsl = preview.getString("xsl");
				XSLTransform xslt = new XSLTransform();
				File file = new File(Config.getXSLPath(xsl));
				
				try {
					xsl = StringUtils.fileContents(file).toString();
					xslt.setXSL(xsl);
					transform = xslt;
				} catch (Exception e) {
					e.printStackTrace();
				}
			} else if(preview.has("jsp")) {
				String jsp = preview.getString("jsp");
				tab = new XMLPreview.PreviewTab(label, input, XMLPreview.PreviewTab.TYPE_JSP, jsp);
			} else if(preview.has("transform")) {
				String cname = preview.getString("transform");
				try {
					Class c = ChainTransform.class.getClassLoader().loadClass(cname);
					transform = (ItemTransform) c.newInstance();
				} catch (Exception e) {
					e.printStackTrace();
				}
			}
			
			// if valid transform then generate tab
			if(transform != null) {
				output = transform.transform(input);					
				tab = new XMLPreview.PreviewTab(label, output, output_type, url);
			}
			
			// if valid tab then add and check for next transformation.
			if(tab != null) {
				result.add(tab);
				
				if(preview.has("preview")) {
					JSONArray p = preview.getJSONArray("preview");
					ArrayList<XMLPreview.PreviewTab> more = this.transform(output, p);
					result.addAll(more);
				}
			}
		}

		return result;
	}
}
