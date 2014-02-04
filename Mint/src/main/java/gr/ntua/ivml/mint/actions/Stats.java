package gr.ntua.ivml.mint.actions;

import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.db.GlobalPrefixStore;
import gr.ntua.ivml.mint.persistent.DataUpload;
import gr.ntua.ivml.mint.persistent.Transformation;
import gr.ntua.ivml.mint.persistent.XmlObject;
import gr.ntua.ivml.mint.util.StringUtils;

import java.util.ArrayList;
import java.util.List;

import org.apache.log4j.Logger;
import org.apache.struts2.convention.annotation.Action;
import org.apache.struts2.convention.annotation.Result;
import org.apache.struts2.convention.annotation.Results;

@Results({
	  @Result(name="error", location="stats.jsp"),
	  @Result(name="success", location="stats.jsp")
	})


public class Stats extends GeneralAction {

	protected final static Logger log = Logger.getLogger(Stats.class);
	
	public class Namespace {
		public void setPrefix(String prefix) {
			this.prefix = prefix;
		}

		public void setUri(String uri) {
			this.uri = uri;
		}

		String prefix;
		String uri;

		public String getPrefix() {
			if( prefix.equals(""))
				if( uri.equals(""))
					return "<empty>";
				else
					return "<default>";
			else 
				return prefix;
		}
		
		public String getUri() { return uri; }
	}
	
	public class StatView {
		String name;
		XmlObject xo;

		public String getName() {
			return name;
		}
		public String getXmlObjectId() {
			return xo.getDbID().toString();
		}
		
		public List<Namespace> getNamespaces() {
			List<Namespace> namespaces = new ArrayList<Namespace>();
			for( String uri: xo.listNamespaces()) {
				if( StringUtils.empty(uri)) continue;
				Namespace n  = new Namespace();
				n.uri = uri;
				n.prefix = GlobalPrefixStore.getPrefix( uri );
				namespaces.add( n ) ;
			}
			return namespaces;
		}
	}

	private List<StatView> statViews = null;
	
	String uploadId;

	public String getUploadId() {
		return uploadId;
	}

	public void setUploadId(String uploadId) {
		this.uploadId = uploadId;
	}

	
	public List<StatView> getViews() {
		if( statViews == null ) {
			statViews = new ArrayList<StatView>();
			try {
				StatView sv;
				Long id = Long.parseLong(getUploadId());
				DataUpload du = DB.getDataUploadDAO().getById( id, false );
				XmlObject xo = du.getXmlObject();
				if( xo != null ) {
					sv = new StatView();
					sv.name = du.getOriginalFilename();
					sv.xo = xo;
					
					statViews.add( sv );
				}
				if(!du.isDirect()){
					for( Transformation tr : du.getTransformations()) {
						xo = tr.getParsedOutput();
						if( xo != null ) {
							sv = new StatView();
							sv.name = "Transformation";
							sv.xo = xo;
							statViews.add( sv );
						}
					}
				}
			} catch( Exception e ) {
				log.error( "Some problem in stats.", e );
			}
		}
 		return statViews;
	}
	
	@Action(value="Stats")
	public String execute() throws Exception {
		return SUCCESS;
	}

}
