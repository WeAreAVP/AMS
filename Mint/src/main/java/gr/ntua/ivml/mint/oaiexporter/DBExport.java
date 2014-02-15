package gr.ntua.ivml.mint.oaiexporter;

import java.sql.*;
import java.text.DateFormat;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Iterator;

import gr.ntua.ivml.mint.xml.FullBean;

public class DBExport {

	private static Connection conn = null;
	private static String username = "root";
	private static String password = "raistlin";
	private static String url = "jdbc:mysql://localhost/vaoai";
	private static  PreparedStatement s;

	static{
			try {
				Class.forName ("com.mysql.jdbc.Driver").newInstance();
				conn = DriverManager.getConnection (url, username, password);	
				s = conn.prepareStatement("INSERT INTO athenaoai_records (" +
						"provider, url, enterdate, oai_identifier, oai_set, datestamp, europeanaType," +
						"europeanaisShownAt, europeanaisShownBy, europeanaObject, europeanaProvider, dcCoverage, dcContributor, " +
						"dcDescription, dcCreator, dcDate, dcFormat, dcIdentifier, dcLanguage, dcPublisher, dcRights, dcSource," +
					"dcSubject, dcTitle, dcType, dctermsAlternative, dctermsCreated, dctermsMedium,dctermsExtent) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
			} catch (InstantiationException e) {
				e.printStackTrace();
			} catch (IllegalAccessException e) {
				e.printStackTrace();
			} catch (ClassNotFoundException e) {
			} catch (SQLException e) {
				e.printStackTrace();
			}

	}
	
	public static void addAsset(ArrayList<FullBean> data,String prov){
		 try {
			 	Iterator<FullBean> itr = data.iterator();
			 	FullBean tmp = null;
			 	while(itr.hasNext()){
			 		tmp = itr.next();
			 		String[] tmpVal = null;
			 		String val = "";
			 		
			 		//s = conn.prepareStatement("INSERT INTO athenaoai_records (" +
					//		"provider, url, enterdate, oai_identifier, oai_set, datestamp, europeanaType," +
					//		"europeanaisShownAt, europeanaisShownBy, europeanaObject, europeanaProvider, dcCoverage, dcContributor, " +
					//		"dcDescription, dcCreator, dcDate, dcFormat, dcIdentifier, dcLanguage, dcPublisher, dcRights, dcSource," +
					//		"dcSubject, dcTitle, dcType, dctermsAlternative, dctermsCreated, dctermsMedium,dctermsExtent) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
			 		
			 		s.setString(1, "Athena");
			 		s.setString(2, null);
			 		s.setString(3, null);
			 		s.setString(4, prov+"_"+Long.toString(System.currentTimeMillis()));
			 		String set = prov + ";"+"AugustIngestion";
			 		s.setString(5, set);
			 		
			 		DateFormat dateFormat = new SimpleDateFormat("yyyy/MM/dd HH:mm:ss");
			 		java.util.Date date = new java.util.Date();
			 		s.setString(6,dateFormat.format(date));
			 		//System.out.println(dateFormat.format(date));
			 		//s.setDate(6, dateFormat.parse(dateFormat.format(date)));
			 		
			 		//s.setString(6, null);
			 		s.setString(7, tmp.getEuropeanaType());

			 		tmpVal = tmp.getEuropeanaisShownAt();
			 		for(int i = 0; i < tmpVal.length;i++){
			 			if( (i == 0) && (tmpVal[i] != null)){
			 				val = tmpVal[i];
			 			}else
			 			if(tmpVal[i] != null){
			 				val += ";"+tmpVal[i];
			 			}
			 		}
			 		if(val.length() > 1){
			 			s.setString(8, val);
			 		}else{
			 			s.setString(8, null);
			 		}
			 		tmpVal = null;
			 		val = "";
			 		
			 		tmpVal = tmp.getEuropeanaisShownBy();
			 		for(int i = 0; i < tmpVal.length;i++){
			 			if( (i == 0) && (tmpVal[i] != null)){
			 				val = tmpVal[i];
			 			}else
			 			if(tmpVal[i] != null){
			 				val += ";"+tmpVal[i];
			 			}
			 		}
			 		if(val.length() > 1){
			 			s.setString(9, val);
			 		}else{
			 			s.setString(9, null);
			 		}
			 		tmpVal = null;
			 		val = "";
			 		
			 		//s.setString(10, null);
			 		tmpVal = tmp.getEuropeanaObject();
			 		for(int i = 0; i < tmpVal.length;i++){
			 			if( (i == 0) && (tmpVal[i] != null)){
			 				val = tmpVal[i];
			 			}else
			 			if(tmpVal[i] != null){
			 				val += ";"+tmpVal[i];
			 			}
			 		}
			 		if(val.length() > 1){
			 			s.setString(10, val);
			 		}else{
			 			s.setString(10, null);
			 		}
			 		tmpVal = null;
			 		val = "";
			 		
			 		s.setString(11, "Athena");
			 		
			 		tmpVal = tmp.getDcCoverage();
			 		for(int i = 0; i < tmpVal.length;i++){
			 			if( (i == 0) && (tmpVal[i] != null)){
			 				val = tmpVal[i];
			 			}else
			 			if(tmpVal[i] != null){
			 				val += ";"+tmpVal[i];
			 			}
			 		}
			 		if(val.length() > 1){
			 			s.setString(12, val);
			 		}else{
			 			s.setString(12, null);
			 		}
			 		tmpVal = null;
			 		val = "";
			 		
			 		tmpVal = tmp.getDcContributor();
			 		for(int i = 0; i < tmpVal.length;i++){
			 			if( (i == 0) && (tmpVal[i] != null)){
			 				val = tmpVal[i];
			 			}else
			 			if(tmpVal[i] != null){
			 				val += ";"+tmpVal[i];
			 			}
			 		}
			 		if(val.length() > 1){
			 			s.setString(13, val);
			 		}else{
			 			s.setString(13, null);
			 		}
			 		tmpVal = null;
			 		val = "";
			 		
			 		tmpVal = tmp.getDcDescription();
			 		for(int i = 0; i < tmpVal.length;i++){
			 			if( (i == 0) && (tmpVal[i] != null)){
			 				val = tmpVal[i];
			 			}else
			 			if(tmpVal[i] != null){
			 				val += ";"+tmpVal[i];
			 			}
			 		}
			 		if(val.length() > 1){
			 			s.setString(14, val);
			 		}else{
			 			s.setString(14, null);
			 		}
			 		tmpVal = null;
			 		val = "";
			 		
			 		tmpVal = tmp.getDcCreator();
			 		for(int i = 0; i < tmpVal.length;i++){
			 			if( (i == 0) && (tmpVal[i] != null)){
			 				val = tmpVal[i];
			 			}else
			 			if(tmpVal[i] != null){
			 				val += ";"+tmpVal[i];
			 			}
			 		}
			 		if(val.length() > 1){
			 			s.setString(15, val);
			 		}else{
			 			s.setString(15, null);
			 		}
			 		tmpVal = null;
			 		val = "";
			 		
			 		tmpVal = tmp.getDcDate();
			 		for(int i = 0; i < tmpVal.length;i++){
			 			if( (i == 0) && (tmpVal[i] != null)){
			 				val = tmpVal[i];
			 			}else
			 			if(tmpVal[i] != null){
			 				val += ";"+tmpVal[i];
			 			}
			 		}
			 		if(val.length() > 1){
			 			s.setString(16, val);
			 		}else{
			 			s.setString(16, null);
			 		}
			 		tmpVal = null;
			 		val = "";
			 		
			 		tmpVal = tmp.getDcFormat();
			 		for(int i = 0; i < tmpVal.length;i++){
			 			if( (i == 0) && (tmpVal[i] != null)){
			 				val = tmpVal[i];
			 			}else
			 			if(tmpVal[i] != null){
			 				val += ";"+tmpVal[i];
			 			}
			 		}
			 		if(val.length() > 1){
			 			s.setString(17, val);
			 		}else{
			 			s.setString(17, null);
			 		}
			 		tmpVal = null;
			 		val = "";
			 		
			 		tmpVal = tmp.getDcIdentifier();
			 		for(int i = 0; i < tmpVal.length;i++){
			 			if( (i == 0) && (tmpVal[i] != null)){
			 				val = tmpVal[i];
			 			}else
			 			if(tmpVal[i] != null){
			 				val += ";"+tmpVal[i];
			 			}
			 		}
			 		if(val.length() > 1){
			 			s.setString(18, val);
			 		}else{
			 			s.setString(18, null);
			 		}
			 		tmpVal = null;
			 		val = "";
			 		
			 		tmpVal = tmp.getDcLanguage();
			 		for(int i = 0; i < tmpVal.length;i++){
			 			if( (i == 0) && (tmpVal[i] != null)){
			 				val = tmpVal[i];
			 			}else
			 			if(tmpVal[i] != null){
			 				val += ";"+tmpVal[i];
			 			}
			 		}
			 		if(val.length() > 1){
			 			s.setString(19, val);
			 		}else{
			 			s.setString(19, null);
			 		}
			 		tmpVal = null;
			 		val = "";
			 		
			 		tmpVal = tmp.getDcPublisher();
			 		for(int i = 0; i < tmpVal.length;i++){
			 			if( (i == 0) && (tmpVal[i] != null)){
			 				val = tmpVal[i];
			 			}else
			 			if(tmpVal[i] != null){
			 				val += ";"+tmpVal[i];
			 			}
			 		}
			 		if(val.length() > 1){
			 			s.setString(20, val);
			 		}else{
			 			s.setString(20, null);
			 		}
			 		tmpVal = null;
			 		val = "";
			 		
			 		tmpVal = tmp.getDcRights();
			 		for(int i = 0; i < tmpVal.length;i++){
			 			if( (i == 0) && (tmpVal[i] != null)){
			 				val = tmpVal[i];
			 			}else
			 			if(tmpVal[i] != null){
			 				val += ";"+tmpVal[i];
			 			}
			 		}
			 		if(val.length() > 1){
			 			s.setString(21, val);
			 		}else{
			 			s.setString(21, null);
			 		}
			 		tmpVal = null;
			 		val = "";
			 		
			 		tmpVal = tmp.getDcSource();
			 		for(int i = 0; i < tmpVal.length;i++){
			 			if( (i == 0) && (tmpVal[i] != null)){
			 				val = tmpVal[i];
			 			}else
			 			if(tmpVal[i] != null){
			 				val += ";"+tmpVal[i];
			 			}
			 		}
			 		if(val.length() > 1){
			 			s.setString(22, val);
			 		}else{
			 			s.setString(22, null);
			 		}
			 		tmpVal = null;
			 		val = "";
			 		
			 		tmpVal = tmp.getDcSubject();
			 		for(int i = 0; i < tmpVal.length;i++){
			 			if( (i == 0) && (tmpVal[i] != null)){
			 				val = tmpVal[i];
			 			}else
			 			if(tmpVal[i] != null){
			 				val += ";"+tmpVal[i];
			 			}
			 		}
			 		if(val.length() > 1){
			 			s.setString(23, val);
			 		}else{
			 			s.setString(23, null);
			 		}
			 		//s.execute();
			 		//System.out.println(val);
			 		tmpVal = null;
			 		val = "";
			 		
			 		tmpVal = tmp.getDcTitle();
			 		for(int i = 0; i < tmpVal.length;i++){
			 			if( (i == 0) && (tmpVal[i] != null)){
			 				val = tmpVal[i];
			 			}else
			 			if(tmpVal[i] != null){
			 				val += ";"+tmpVal[i];
			 			}
			 		}
			 		if(val.length() > 1){
			 			s.setString(24, val);
			 		}else{
			 			s.setString(24, null);
			 		}
			 		tmpVal = null;
			 		val = "";
			 		
			 		tmpVal = tmp.getDcType();
			 		for(int i = 0; i < tmpVal.length;i++){
			 			if( (i == 0) && (tmpVal[i] != null)){
			 				val = tmpVal[i];
			 			}else
			 			if(tmpVal[i] != null){
			 				val += ";"+tmpVal[i];
			 			}
			 		}
			 		if(val.length() > 1){
			 			s.setString(25, val);
			 		}else{
			 			s.setString(25, null);
			 		}
			 		tmpVal = null;
			 		val = "";
			 		
			 		tmpVal = tmp.getDctermsAlternative();
			 		for(int i = 0; i < tmpVal.length;i++){
			 			if( (i == 0) && (tmpVal[i] != null)){
			 				val = tmpVal[i];
			 			}else
			 			if(tmpVal[i] != null){
			 				val += ";"+tmpVal[i];
			 			}
			 		}
			 		if(val.length() > 1){
			 			s.setString(26, val);
			 		}else{
			 			s.setString(26, null);
			 		}
			 		tmpVal = null;
			 		val = "";
			 		
			 		tmpVal = tmp.getDctermsCreated();
			 		for(int i = 0; i < tmpVal.length;i++){
			 			if( (i == 0) && (tmpVal[i] != null)){
			 				val = tmpVal[i];
			 			}else
			 			if(tmpVal[i] != null){
			 				val += ";"+tmpVal[i];
			 			}
			 		}
			 		if(val.length() > 1){
			 			s.setString(27, val);
			 		}else{
			 			s.setString(27, null);
			 		}
			 		tmpVal = null;
			 		val = "";
			 		
			 		
			 		tmpVal = tmp.getDctermsMedium();
			 		for(int i = 0; i < tmpVal.length;i++){
			 			if( (i == 0) && (tmpVal[i] != null)){
			 				val = tmpVal[i];
			 			}else
			 			if(tmpVal[i] != null){
			 				val += ";"+tmpVal[i];
			 			}
			 		}
			 		if(val.length() > 1){
			 			s.setString(28, val);
			 		}else{
			 			s.setString(28, null);
			 		}
			 		tmpVal = null;
			 		val = "";
			 		
			 		tmpVal = tmp.getDctermsExtent();
			 		for(int i = 0; i < tmpVal.length;i++){
			 			if( (i == 0) && (tmpVal[i] != null)){
			 				val = tmpVal[i];
			 			}else
			 			if(tmpVal[i] != null){
			 				val += ";"+tmpVal[i];
			 			}
			 		}
			 		if(val.length() > 1){
			 			s.setString(29, val);
			 		}else{
			 			s.setString(29, null);
			 		}
			 		tmpVal = null;
			 		val = "";
			 		
			 		s.execute();
			 	}
			 	
			 	//s.close();
		 
		 } catch (SQLException e) {
			 e.printStackTrace();
		} 

	}
	
	
}
