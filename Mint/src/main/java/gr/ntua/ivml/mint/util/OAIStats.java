package gr.ntua.ivml.mint.util;

import java.sql.*;
import java.text.DateFormat;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Iterator;

import gr.ntua.ivml.mint.xml.FullBean;

public class OAIStats {

	private Connection conn = null;
	private static String username = "videoactive";
	private static String password = "videoactive";
	private static String url = "jdbc:mysql://147.102.11.37/vaoai";
	private   PreparedStatement s;

	public OAIStats(){
			try {
				Class.forName ("com.mysql.jdbc.Driver").newInstance();
				conn = DriverManager.getConnection (url, username, password);	
			} catch (InstantiationException e) {
				e.printStackTrace();
			} catch (IllegalAccessException e) {
				e.printStackTrace();
			} catch (ClassNotFoundException e) {
			} catch (SQLException e) {
				e.printStackTrace();
			}

	}
	
	public int findEseByOrg(String orgname){
		int esenum=0;
		ResultSet rs = null;
	  try{
		s = conn.prepareStatement("SELECT count(*) FROM athenaoai_records where oai_set=?");
		s.setString(1, orgname);
		rs=s.executeQuery();
		 while (rs.next()) {
		        esenum += rs.getInt(1);
		       }
		s.close();
	  } 
	  catch (SQLException e) {
			 e.printStackTrace();
	  } 
	  return esenum; 
	}
	
}
