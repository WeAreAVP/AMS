
package gr.ntua.ivml.mint.actions;

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




public class AjaxFileReader extends GeneralAction implements ServletRequestAware,ServletResponseAware  
{  
	  
	private HttpServletRequest request;
	private HttpServletResponse response;
	
	

	@Action(value="AjaxFileReader")
    public String execute() throws Exception {  
        PrintWriter writer = null;  
        InputStream is = null;
        FileOutputStream fos = null;

        try {
        	writer = response.getWriter();  
           
        } catch (IOException ex) {
            System.out.println(AjaxFileReader.class.getName() + "has thrown an exception: " + ex.getMessage());
        }

        try {
        	
            is = request.getInputStream();
            File newTmpFile = File.createTempFile("UploadMap", "cpy");
            String fname=newTmpFile.getName();
            System.out.println(fname);
            fos = new FileOutputStream( newTmpFile );
            IOUtils.copy(is, fos);
            response.setStatus(HttpServletResponse.SC_OK);
            writer.print("{success: true, fname: '"+fname+"'}");
         } catch (FileNotFoundException ex) {
            response.setStatus(HttpServletResponse.SC_INTERNAL_SERVER_ERROR);
            writer.print("{success: false}");
            System.out.println(AjaxFileReader.class.getName() + "has thrown an exception: " + ex.getMessage());
        } catch (IOException ex) {
        	response.setStatus(HttpServletResponse.SC_INTERNAL_SERVER_ERROR);
            writer.print("{success: false}");
            System.out.println(AjaxFileReader.class.getName() + "has thrown an exception: " + ex.getMessage());
        } finally {
            try {
                fos.close();
                is.close();
            } catch (IOException ignored) {
            }
        }

        writer.flush();
        writer.close();
        return NONE;
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
