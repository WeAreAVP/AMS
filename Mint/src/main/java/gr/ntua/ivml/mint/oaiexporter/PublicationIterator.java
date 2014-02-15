package gr.ntua.ivml.mint.oaiexporter;

import java.io.BufferedReader;
import java.io.File;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.Enumeration;
import java.util.HashMap;
import java.util.Iterator;

import de.schlichtherle.util.zip.BasicZipFile;


import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.db.PublicationDAO;
import gr.ntua.ivml.mint.oaiexporter.DBExport;
import gr.ntua.ivml.mint.persistent.Publication;
import gr.ntua.ivml.mint.xml.ESEToFullBean;
import gr.ntua.ivml.mint.xml.FullBean;

public class PublicationIterator {
	
	static{}
	
	
	public static void iteratePublications() throws IOException{
		
		PublicationDAO pubD = DB.getPublicationDAO();
		ArrayList<Publication> pubs = (ArrayList<Publication>) pubD.findAll();
		FullBean bean = null;
		Iterator<Publication> itr = pubs.iterator();
		//int counter = 0;
		HashMap<String, String> logz = new HashMap<String, String>();
		String name = "";
		boolean add = false;
		while(itr.hasNext()){
			int counter = 0;
			Publication pubTmp = itr.next();
			Date dat = pubTmp.getLastProcess();
			String DATE_FORMAT = "yyyy-MM-dd";
			java.text.SimpleDateFormat sdf =
				new java.text.SimpleDateFormat(DATE_FORMAT);
			Calendar c1 = Calendar.getInstance();
			c1.set(2010, 06, 10);
			Calendar c2 = Calendar.getInstance();
			c2.set(2010, dat.getMonth(), dat.getDay()+1);
			if(c2.after(c1)){
				//System.out.println("Meta");
				add = true;
			}
			if(c2.before(c1)){
				//System.out.println("Prin");
				add = false;
			}
			name = pubTmp.getPublishingOrganization().getEnglishName();
			/*pubTmp.unloadToTmpFile();
			
			//File file = pubTmp.getTmpFile();
			BasicZipFile zip = new BasicZipFile(file);
			Enumeration en = zip.entries();
			de.schlichtherle.util.zip.ZipEntry ze = null;
			while(en.hasMoreElements()){
				if(!add){
					break;
				}
				//Object la = en.nextElement();
				ze = (de.schlichtherle.util.zip.ZipEntry) en.nextElement();
				InputStream zis = zip.getInputStream(ze);
				if( (!ze.isDirectory()) && (ze.getName().endsWith(".xml"))){
					 StringBuilder sb = new StringBuilder();
			            String line;
		            try {
		                BufferedReader reader = new BufferedReader(new InputStreamReader(zis, "UTF-8"));
		                while ((line = reader.readLine()) != null) {
		                    sb.append(line).append("\n");
		                }
		                ArrayList<FullBean> beanz = ESEToFullBean.getFullBeans(sb.toString());
		                DBExport.addAsset(beanz, name);
		                counter+= beanz.size();
		            } finally {
		                zis.close();
		            }
				}
			}
			String ti = ""+ counter;
			logz.put(name, ti);
			pubTmp.cleanup();*/
			System.out.println("Org name:" + name + " number of items:"+counter);
		}
		int total = 0;
		Iterator<String> itr2 = logz.keySet().iterator();
		while(itr2.hasNext()){
			String orgName = itr2.next();
			int No = Integer.parseInt(logz.get(orgName));
			total += No;
			System.out.println("Imported for Org with name " + orgName + " " + No + " items.");
		}
		System.out.println("Total Number of items imported to OAI repository:" + total);
	}
}
