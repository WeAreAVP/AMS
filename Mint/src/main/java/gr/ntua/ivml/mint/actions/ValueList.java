package gr.ntua.ivml.mint.actions;

import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.persistent.Organization;
import gr.ntua.ivml.mint.persistent.User;
import gr.ntua.ivml.mint.persistent.XmlObject;
import gr.ntua.ivml.mint.persistent.XpathHolder;

import java.util.List;

import net.sf.json.JSONArray;
import net.sf.json.JSONObject;

import org.apache.log4j.Logger;
import org.apache.struts2.convention.annotation.Action;
import org.apache.struts2.convention.annotation.Result;
import org.apache.struts2.convention.annotation.Results;

@Results({
	@Result(name="error", location="json.jsp"),
	@Result(name="success", location="json.jsp")
})

public class ValueList extends GeneralAction {

	public static final Logger log = Logger.getLogger(ValueList.class ); 
	public JSONObject json;
	public int start, max;
	public String filter;
	public long xpathHolderId;
	public boolean totalCount;
	
	@Action(value="ValueList")
	public String execute() {
		json = new JSONObject();
		json.element( "start", start );
		json.element("max", max);
		json.element("filter", filter );
		json.element( "xpathHolderId", xpathHolderId );
		try {
			XpathHolder path = getHolder();
			if( path != null ) {
				if( isTotalCount() ) {
					long l = DB.getXMLNodeDAO().countDistinct(path);
					json.element( "totalCount", l );
				}
				getValues( path );
			}
		} catch( Exception e ) {
			json.element( "error", e.getMessage());
			log.error( "No values", e );
		}
		return SUCCESS;
	}
	
	/**
	 * Get the holder and check the read permission of the user.
	 * @return
	 */
	private XpathHolder getHolder() {
		boolean allow = false;
		XpathHolder result = null;
		result = DB.getXpathHolderDAO().getById(xpathHolderId, false);
		if( result == null ) {
			json.element( "error", "No such xpath." );
			return null;
		}
		
		XmlObject xo = result.getXmlObject();
		
		if( getUser().hasRight(User.SUPER_USER)) allow=true;
		else {
			Organization owner = DB.getOrganizationDAO().findByXmlObject(xo);
			if( owner == null ) {
				log.warn( "xml object " + xo.getDbID() + " belongs to no organization." );
			} else {
				if( getUser().can("view data", owner )) allow = true;
			}
		}
		
		if( !allow ) {
			json.element( "error", "No access rights" );
			return null;
		}
		
		return result;	
	}
	
	
	private void getValues( XpathHolder path ) {
		try {
			List<Object[]> values = null;
			if(( filter!= null ) && (filter.length()>=3 )) {
				values = DB.getXMLNodeDAO().getValues(path, start, max, filter );
			} else {
				values = DB.getXMLNodeDAO().getValues(path, start, max );
			}
			
			JSONArray theValues = new JSONArray();
			for( Object[] val: values ) {
				JSONObject valJ = new JSONObject();
				valJ.element( "value", val[0]);
				valJ.element( "count", val[1] );
				theValues.add(valJ);
			}
			json.element( "values", theValues);
		} catch( Exception e ) {
			log.error( "No values extracted on xpath " + xpathHolderId, e );
		}
	}
	
	public int getStart() {
		return start;
	}



	public void setStart(int start) {
		this.start = start;
	}



	public int getMax() {
		return max;
	}



	public void setMax(int max) {
		this.max = max;
	}



	public String getFilter() {
		return filter;
	}



	public void setFilter(String filter) {
		this.filter = filter;
	}



	public long getXpathHolderId() {
		return xpathHolderId;
	}



	public void setXpathHolderId(long xpathHolderId) {
		this.xpathHolderId = xpathHolderId;
	}



	public boolean isTotalCount() {
		return totalCount;
	}

	public void setTotalCount(boolean totalCount) {
		this.totalCount = totalCount;
	}

	public void setJson(JSONObject json) {
		this.json = json;
	}



	public JSONObject getJson() {
		return json;
	}
}
