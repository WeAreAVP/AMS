
package gr.ntua.ivml.mint.actions;

import static com.opensymphony.xwork2.Action.SUCCESS;
import gr.ntua.ivml.mint.concurrent.XSLTransform;
import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.PrintWriter;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import org.apache.commons.io.IOUtils;

import org.apache.struts2.convention.annotation.Action;
import org.apache.struts2.interceptor.ServletRequestAware;
import org.apache.struts2.interceptor.ServletResponseAware;

import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.persistent.Transformation;
import gr.ntua.ivml.mint.persistent.DataUpload;
import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;
import javax.xml.validation.Schema;
import javax.xml.validation.ValidatorHandler;

import org.apache.log4j.Logger;
import org.apache.struts2.convention.annotation.Result;
import org.apache.struts2.convention.annotation.Results;
import org.hibernate.Session;
import org.hibernate.StatelessSession;



@Results({
	  @Result(name="input", location="summary.jsp"),
	  @Result(name="error", location="summary.jsp"),
	  @Result(name="success", location="summary.jsp" )
	})


public class AjaxApproval extends GeneralAction implements ServletRequestAware,ServletResponseAware  
{  
	  
	private HttpServletRequest request;
	private HttpServletResponse response;
	private Transformation tr;
	

	@Action(value="AjaxApproval")
    public String execute() throws Exception { 
		
        PrintWriter writer = null;  
		try {
        	writer = response.getWriter();  
           
        } catch (IOException ex) {
            System.out.println(AjaxFileReader.class.getName() + "has thrown an exception: " + ex.getMessage());
        }
		try {
			
			Long dbID=Long.parseLong(request.getParameter("id"));
			DataUpload du = DB.getDataUploadDAO().getById(dbID, false);
			tr = DB.getTransformationDAO().findOneByUpload(du);
			
			// new version of the transformation for this session
			if( tr == null ) {
				
				return ERROR;
			}
			else{
				tr.setIsApproved(Integer.parseInt(request.getParameter("approved")));
				DB.commit();
				String urlParameters = request.getParameter("id")+"/"+request.getParameter("approved")+'/'+user.getDbID();
				
			String request = "http://amsqa.avpreserve.com/mintimport/update_transformed_info/"+urlParameters;
			URL url = new URL(request); 
			HttpURLConnection connection = (HttpURLConnection) url.openConnection();           
			connection.setDoOutput(true);
			connection.setReadTimeout(10000);
			connection.setRequestMethod("GET"); 
			connection.setRequestProperty("charset", "utf-8");
			connection.setUseCaches (false);
			connection.connect();
			BufferedReader rd  = new BufferedReader(new InputStreamReader(connection.getInputStream()));
			StringBuilder sb = new StringBuilder();
        
			String line = "";
          while ((line = rd.readLine()) != null)
          {
              sb.append(line + '\n');
          }
        
          System.out.println(sb.toString());
			connection.disconnect();
				
			}
		} catch( Exception e ) {
			// already handled, but needed to skip readNodes or index if transform or readNodes fails
		} catch( Throwable t ) {
			writer.print( "uhh "+ t );
		}
		
		
		
        response.setStatus(HttpServletResponse.SC_OK);

        writer.flush();
        writer.close();
        return "success";
    }


	public void setServletRequest(HttpServletRequest request){
	    this.request = request;
	  }

	  public HttpServletRequest getServletRequest(){
	    return request;
	  }

	  public void setServletResponse(HttpServletResponse response){
	    this.response = response;
	  }

	  public HttpServletResponse getServletResponse(){
	    return response;
	  }

}
