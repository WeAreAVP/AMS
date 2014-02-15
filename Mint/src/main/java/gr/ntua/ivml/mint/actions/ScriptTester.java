package gr.ntua.ivml.mint.actions;

import gr.ntua.ivml.mint.persistent.User;
import gr.ntua.ivml.mint.util.Config;
import groovy.lang.Binding;
import groovy.lang.GroovyShell;

import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.PrintStream;
import java.io.PrintWriter;
import java.io.StringWriter;
import java.util.Date;
import java.util.HashMap;
import java.util.Map;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

import javax.servlet.http.HttpServletRequest;

import org.apache.commons.io.FileUtils;
import org.apache.log4j.Logger;
import org.apache.struts2.convention.annotation.Action;
import org.apache.struts2.convention.annotation.Result;
import org.apache.struts2.convention.annotation.Results;
import org.apache.struts2.interceptor.ServletRequestAware;

@Results({
	  @Result(name="input", location="script_test.jsp"),
	  @Result(name="success", location="script_test.jsp"),
	  @Result(name="error", location="Home", type="redirectAction")
	})

public class ScriptTester extends GeneralAction implements ServletRequestAware {
	
	
	public static final Logger log = Logger.getLogger( ScriptTester.class );
	private String script;
	private String result;
	private String stdOut;
	private HttpServletRequest req;
	
	private static Map<String, String> library=null;
	private static Date lastLibRead = null;
	private String scriptlet;
	
	public String getStdOut() {
		return stdOut;
	}

	public void setStdOut(String stdOut) {
		this.stdOut = stdOut;
	}

	public String getResult() {
		return result;
	}

	public void setResult(String result) {
		this.result = result;
	}

	@Action("Script")
	public String execute( ) throws Exception {
		
		// check if we just want a scriptlet
		if( ! "/".equals(getScriptlet())) {
			if( getLib().containsKey(getScriptlet())) {
				File scriptFile = new File( getScriptlet());
				script = FileUtils.readFileToString(scriptFile, "UTF-8" );
			}
			return "success";
		}
		Binding binding = new Binding();
		if( !user.hasRight(User.ALL_RIGHTS)) return "error";
		binding.setVariable("user", user);
		binding.setVariable( "log", log );
		binding.setVariable("request", req);
		
		String head = "import gr.ntua.ivml.mint.db.DB\n";
		PrintStream originalOut = System.out;
		ByteArrayOutputStream buffer = new ByteArrayOutputStream();
		
		GroovyShell shell = new GroovyShell(binding);
		try {
			System.setOut(new PrintStream( buffer));
			Object o = shell.evaluate(head+ script);
			result = (o==null?null:o.toString());
		} catch( Exception e ) {
			log.error( "Groovy exec problem.", e );
			StringWriter sw = new StringWriter();
			PrintWriter pw = new PrintWriter( sw );
			e.printStackTrace( pw );
			result = sw.toString();
		} finally {
			System.setOut(originalOut);
			stdOut= buffer.toString();
		}
		return "success";
	}
	
	public String getScript() {
		return script;
	}
	
	public void setScript( String script ) {
		this.script = script;
	}
	
	@Action("Script_input")
	@Override
	public String input() throws Exception {
		return super.input();
	}
	
	public Map<String, String> getLib() {
		// open dir
		// read each file
		// extract first comment line 
		// add map comment and filename
	
		if( library != null ) {
			if( lastLibRead != null && 
				(System.currentTimeMillis() - lastLibRead.getTime() < 1000l*60*10)) 
				return library;
		}
		try {
			File dir = new File( Config.getRealPath("WEB-INF/script_library"));
			library = new HashMap<String,String>();
			
			for( File script: dir.listFiles()) {
				if( script.isDirectory()) continue;
				String content = FileUtils.readFileToString( script , "UTF-8");
				Matcher m = Pattern.compile("^//[\\s]*(.*)$", Pattern.MULTILINE).matcher(content);
				if( m.find()) {
					library.put( script.getAbsolutePath(), m.group(1));
				}
			}
			lastLibRead = new Date();
		} catch( Exception e ) {
			log.error( "Script library reading failed", e );
		}
		return library;
	}
	
	public String getScriptlet() {
		return scriptlet;
	}
	
	public void setScriptlet( String value ) {
		this.scriptlet = value;
	}

	@Override
	public void setServletRequest(HttpServletRequest request) {
		req = request;
	}
}
