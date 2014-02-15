package gr.ntua.ivml.mint.actions;
import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.persistent.DataUpload;
import gr.ntua.ivml.mint.persistent.Organization;
import gr.ntua.ivml.mint.persistent.Publication;
import gr.ntua.ivml.mint.persistent.Transformation;

import java.io.*;


import org.apache.log4j.Logger;
import org.apache.struts2.convention.annotation.Action;

import java.io.InputStream;

import java.util.List;

/**
 * Action for download page.
 
 */

public class Download extends GeneralAction {

	protected final Logger log = Logger.getLogger(getClass());
	public String filename;
	private InputStream inputStream;
	private String contentType;
	private String contentDisposition;
	private DataUpload du;
	private long orgId;
	private boolean transformed=false;
	private boolean published=false;

	public void setDbId(String dbId){
		this.du=DB.getDataUploadDAO().getById(Long.parseLong(dbId), false);
		
	}
	
	public InputStream getInputStream()
	{
		return inputStream;
	}
	
	public void setOrgId(long orgid){
		this.orgId=orgid;
	}
	
	
	public void setTransformed(boolean transformed){
		this.transformed=transformed;
	}
	
	public void setPublished(boolean published){
		this.published=published;
	}

	
	public void setInputStream(InputStream is){
		inputStream=is;
	}
	
	public void setContentType(String ct){
	   this.contentType=ct;	
	}
	
	public String getContentType(){
		return(contentType);
	}
	
	
	public void setContentDisposition(String cd){
		   this.contentDisposition=cd;	
		}
	
	public String getContentDisposition(){
		return(contentDisposition);
	}
	
	

	public void setFilename(){
		if(published)
		{   Organization o=DB.getOrganizationDAO().findById(this.orgId, false);
		    String fname=o.getName();
		    fname=fname.replace(' ','_');
			this.filename=fname+"_Published.zip";
		}
		else{
			if(du.isOaiHarvest()){
					this.filename=du.getOriginalFilename().replace(' ','_')+".zip";
				}else{
					if(du.getOriginalFilename().indexOf(".xml")>-1){
					   this.filename=(du.getOriginalFilename().substring(0, du.getOriginalFilename().indexOf(".xml"))).replace(' ','_')+".zip";
				    }
				    else{this.filename=du.getOriginalFilename().replace(' ','_');}
				
				}
	   }
	}
	  
	public String getFilename(){
		 return(this.filename);
	}


	
	@Action(value="Download")
	public String execute() throws Exception {
		setFilename();
		String fs=System.getProperty("file.separator");
		String newname=filename.substring(filename.lastIndexOf(fs)+1, filename.length());
		this.setContentDisposition("attachment; filename=" + newname);
		if(transformed==false && published==false){
			this.setContentType("text/xml");
			if(du.isZippedUpload()){
				this.setContentType("application/x-zip-compressed");
			}
			this.setInputStream(du.getDownloadStream());	
		}
        else if (transformed==true)
		{   
        	this.setContentType("application/x-zip-compressed");
			List<Transformation> lt = DB.getTransformationDAO().findByUpload(du);
	    	Transformation tr=lt.get(0);
	    	
			this.setInputStream(tr.getDownloadStream());
		}
        else if (published==true)
		{   
        	this.setContentType("application/x-zip-compressed");
        	Organization o=DB.getOrganizationDAO().findById(this.orgId, false);
        	Publication p=DB.getPublicationDAO().findByOrganization(o);
				    	
			this.setInputStream(p.getDownloadStream());
		}
		return SUCCESS; 
	}
	

}
	  
