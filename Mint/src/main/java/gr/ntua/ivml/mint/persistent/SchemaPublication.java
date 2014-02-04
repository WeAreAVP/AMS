package gr.ntua.ivml.mint.persistent;


import gr.ntua.ivml.mint.concurrent.Ticker;
import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.util.Config;
import gr.ntua.ivml.mint.xml.PathIterator;


import gr.ntua.ivml.mint.xsd.SchemaValidator;
import gr.ntua.ivml.mint.persistent.Transformation.MyZipOutputStream;


import java.io.ByteArrayInputStream;

import java.io.File;

import java.io.OutputStreamWriter;


import java.util.ArrayList;
import java.util.Collections;
import java.util.Comparator;
import java.util.Date;
import java.util.Iterator;
import java.util.List;

import javax.xml.validation.Schema;
import javax.xml.validation.ValidatorHandler;


import org.apache.commons.io.FileUtils;
import org.xml.sax.Attributes;
import org.xml.sax.ContentHandler;
import org.xml.sax.InputSource;
import org.xml.sax.XMLReader;
import org.xml.sax.helpers.DefaultHandler;

import de.schlichtherle.io.FileOutputStream;
import de.schlichtherle.util.zip.ZipEntry;



/**
 * What is done when publishing to given schema. Set publication xsl in conf file of transformation schema.
 */
public class SchemaPublication extends Publication {

	
	gr.ntua.ivml.mint.xml.transform.XSLTransform transformXSL = new gr.ntua.ivml.mint.xml.transform.XSLTransform();			

	
	
	private static class Counter {
		int count = 0;
		void inc() { count += 1; }
		int get() { return count; }
		void reset() { count = 0; };
	};
	
	

	
	
	public List<Transformation> getTransformations() throws Exception {
		ArrayList<Transformation> al = new ArrayList<Transformation>();
		// input uploads need sorting
		List<DataUpload> l = getInputUploads();
		Collections.sort(l, new Comparator<DataUpload>() {
			public int compare( DataUpload a, DataUpload b ) {
				if( a.getUploadDate().before(b.getUploadDate())) return -1;
				if( a.getUploadDate().after( b.getUploadDate())) return 1;
				return 0;
			}
		});
		
		// make the List of Transformations
		List<Transformation> lt = new ArrayList<Transformation>();
		boolean hasTransformation = false;
		for(DataUpload du: getInputUploads()) {
			for( Transformation tr: du.getTransformations()) {
				al.add( tr );
				hasTransformation = true;
				break;
			}
		}
		
		if( ! hasTransformation ) throw new Exception( "Upload has no suitable Transformation" );
		
		return al;
	}
	
	
	
	public void process() {
		
		File processed = null;
		try {
				processed = postProcess();

				writeBack( processed );
				setLastProcess(new Date());
				setStatusCode(Publication.OK);
				setStatusMessage("Processed and ready for download");
			
		} catch( Exception e ) {
			if( getStatusCode() != ERROR ) {
				setStatusCode(ERROR);
				setStatusMessage("Publication processing failed with: " + e.getMessage());
			}
			// didn't work, remove transformations from upload
			getInputUploads().clear();
			log.error( "processing of Publication failed.", e );
		} finally {
			if( processed != null ) processed.delete();
			DB.commit();
		}
	}
	
	
	/**
	 * Iterate over involved uploads and
	 *  - check if they have a successful transformation
	 *  - and each transformation has xsl
	 *  - collect all items in a zip together xsl and output.
	 */		
	
	public File postProcess() throws Exception {
		Ticker t = new Ticker(30);
		long currentItemNo = 1l;
		tmpFile = File.createTempFile("final_pub_", ".zip" );
		MyZipOutputStream zos = new MyZipOutputStream(new FileOutputStream(tmpFile));
		List<DataUpload> toberemovedUploads = new ArrayList<DataUpload>();
		try {
				//parser = new Builder();
				setStatusCode(Publication.PROCESS);
				DB.commit();
				StringBuilder processReport = new StringBuilder();
				if( report != null ) processReport.append(report);
				long totalItemCount = sumInputItems();
				setStatusMessage("Publishing " +  totalItemCount + " input items.");
				if( tmpFile != null ) {
					tmpFile.delete();
				}
				
				
				
				Schema publicationSchema = SchemaValidator.getSchema( Config.getRealPath(Config.get("publicationSchema")));
				ValidatorHandler vh = publicationSchema.newValidatorHandler();
				
				final Counter publishedCount = new Counter();
				ContentHandler publishedCountHandler = new DefaultHandler() {
					public void startElement( String uri, String localName, String qName, Attributes atts ) {
						if( "record".equals( localName ) || "record".equals( qName )) publishedCount.inc();
					}
				};
				
				vh.setContentHandler(publishedCountHandler);
				
				XMLReader parser = org.xml.sax.helpers.XMLReaderFactory.createXMLReader(); 
				parser.setFeature("http://apache.org/xml/features/nonvalidating/load-external-dtd", false);
				parser.setContentHandler(vh);
		    	
			
			
				log.debug( "Publication: " + getDbID() + " Items:" + currentItemNo + "/" + totalItemCount );
				int publishedItems = 0;
				int transformedItems = 0;
				int failed = 0;
				for( DataUpload du: getInputUploads()) {
					Transformation tr = null;
						for( Transformation tr2: du.getTransformations()) {
						if(( tr2.getStatusCode() == Transformation.OK ) )
							tr = tr2;
					}
					if( tr == null ) 
					{
						processReport.append("Upload " + du.getOriginalFilename() + " does not contain suitable Transformation!\n\n");
						throw new Exception( "Upload " + du.getOriginalFilename() + " does not contain suitable Transformation!");
					}
					String xslfile="";
					XmlSchema xsch;
					if(du.isDirect()){xslfile=du.getDirectSchema().getPublicationXSL();xsch=du.getDirectSchema();}
					else{xslfile=tr.getMapping().getTargetSchema().getPublicationXSL();xsch=tr.getMapping().getTargetSchema();}
					if(xslfile==null){
						throw new Exception( "Can't find a publication target schema for upload " + du.getOriginalFilename() + ".");
					}
					File xslFile = new File(Config.getXSLPath(xslfile));
					
					String xsl = FileUtils.readFileToString( xslFile , "UTF-8");
					Iterator<XMLNode> iter = PathIterator.fromTransform(tr,xsch);
					long errorNodeId = -1l;
					int published_transformed=0;
					int maxerror=100;
					int cur_transformed=0;
					if(du.getItemCount()<100){maxerror=(int)du.getItemCount();}
					while( iter.hasNext()) {
						transformedItems++;
						cur_transformed++;
						OutputStreamWriter writer = new OutputStreamWriter( zos, "UTF8" );
					  try{
						XMLNode node = iter.next();
						errorNodeId=node.getNodeId();
						XMLNode wrappedNode = XMLNode.buildItemWrapTree(node);
						String transformedItem=transformItemEntry(wrappedNode.toXml(),xsl);
						//now parse output to see if correct
						InputSource ins = new InputSource();
						
						ins.setByteStream(new ByteArrayInputStream(transformedItem.getBytes("UTF-8")));

						// check publication is valid
						
						parser.parse( ins );
						publishedItems += publishedCount.get();
						if(publishedCount.get()>0){ 
						   published_transformed++;
						   }
						zos.putNextEntry(new ZipEntry(  "output_"+node.getNodeId()+".xml"));
						writer.write(transformedItem);
						writer.flush();
						zos.closeEntry();
						zos.close();
					  } catch( Exception e ) {
							failed += 1;
							if( processReport.length() < 50000 ) {
								if( errorNodeId != -1l ) {
									processReport.append( "\nItem output_" +errorNodeId+".xml from import '"+du.getOriginalFilename()+"'"  );
									processReport.append( " URL:(PreviewError?transformedNodeId="+errorNodeId+")");
									processReport.append( " had problems: \n" );
									processReport.append( e.getMessage() + "\n");
								} else {
									// not related to a specific node, we are done with an error
									setReport( processReport.toString());
									DB.commit();
									throw e;
								}
							}
							if(( cur_transformed == maxerror ) && (published_transformed == 0 )) {
								processReport.append("\n\nPublication aborted for import '"+du.getOriginalFilename()+"' after "+maxerror+" consecutive failures.\n\n");
								failed=failed+(int)(du.getItemCount()-maxerror);
								transformedItems=transformedItems+((int)du.getItemCount()-maxerror);
								setReport( processReport.toString());
								
								toberemovedUploads.add(du);
								DB.commit();	
								
							}
					  	
						}finally {
							publishedCount.reset();					
						}
						
						if( t.isSet()) {
							t.reset();
							setStatusMessage( "Postprocessed " + transformedItems + " items of " + totalItemCount + " (failed " + failed + ")");
							log.debug( "Postprocessed " + transformedItems + " items of " + totalItemCount + " (failed " + failed + ")");
							DB.commit();
						}
						currentItemNo+=1;
						if(( cur_transformed == maxerror ) && (published_transformed == 0 )) {break;}
					}//end while
				}//end for
				
				if( publishedItems > 0 ) {
					processReport.append( "\nTransformed " + transformedItems + "  records to " + publishedItems + "  records.\n" );
					setItemCount(publishedItems);
					if( failed != 0 ) {
						processReport.append( failed + " items were excluded due to problems.\n" );
					}
					zos.putNextEntry( new ZipEntry( "publication_report.txt" ));
					zos.write( processReport.toString().getBytes("UTF-8"));
					zos.close();
					zos.finished();
					zos = null;
					log.info( "Finished creating " + tmpFile.getAbsolutePath());
					setStatusMessage( "Postprocessed " + transformedItems + " items.");
					setReport( processReport.toString());
					if(toberemovedUploads.size()>0){
						for(DataUpload d:toberemovedUploads){
							this.removeUpload(d);
							
						}
						DB.getPublicationDAO().makePersistent(this);
						
						
					}
					DB.commit();
				} else {
					setReport( processReport.toString());
					throw new Exception( "No item could be transformed!" ); 
				}
				// not sure this is needed
				t.cancel();
				return tmpFile;
			
		} catch( Exception e ) {
			log.error( "Publication: " + getDbID() + "CurrentItemNo:" + currentItemNo + "\nError: " , e );
			
			if( getStatusCode() != Publication.ERROR) {
				setStatusCode(Publication.ERROR);
				
				setStatusMessage( "Publication:"+ getDbID() + " Error:" + e.getMessage() );
				DB.commit();
				
			}
			throw e;
		} finally {
			
			t.cancel();
			if( zos != null ) zos.finished();
		}
	}
	

	
	
	public String transformItemEntry(String item,String xsl) throws Exception {
		String result="";
		result=transformXSL.transform(item, xsl);
		return result;
				
	}
	
	
}
