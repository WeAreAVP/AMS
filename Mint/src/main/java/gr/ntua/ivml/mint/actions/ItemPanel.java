
package gr.ntua.ivml.mint.actions;

import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.persistent.DataUpload;
import gr.ntua.ivml.mint.persistent.Organization;
import gr.ntua.ivml.mint.persistent.User;
import gr.ntua.ivml.mint.persistent.XMLNode;
import gr.ntua.ivml.mint.util.StringUtils;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Collections;
import java.util.List;

import org.apache.log4j.Logger;
import org.apache.struts2.convention.annotation.Action;
import org.apache.struts2.convention.annotation.Result;
import org.apache.struts2.convention.annotation.Results;

@Results({
	@Result(name="error", location="itemPanel.jsp"),
	@Result(name="success", location="itemPanel.jsp")
})

public class ItemPanel extends GeneralAction{

	protected final Logger log = Logger.getLogger(getClass());

	public class Item {
		String name;
		long nodeId;
		long uploadId;
		String itemdate;
		String importname;
		boolean transformed;
		boolean direct;
		boolean truncated;
		
		public String getDate() {
			return itemdate;
		}
		
		public void setTransformed(boolean transformed) {
			this.transformed=transformed;
		}
		
		public boolean getTransformed(){
			return(this.transformed);
		}
		
		public boolean isDirect(){
			return direct;
		}
		
		public boolean isTruncated(){
			return truncated;
		}
		
		public String getName() {
			return name;
		}
		public long getNodeId() {
			return nodeId;
		}
		public long getUploadId() {
			return uploadId;
		}
		
		public String getImportname() {
			return StringUtils.shorten( importname,15,"..", 0 );
		}
		
	}

	private int startItem, maxItems;
	private int endItem;
	private long organizationId;
	private Organization o;
	private String action="";
	private String actionmessage="";
	private long uploadId=-1;
	private long userId=-1;
	private User u=null;
	private ArrayList<String> itemCheck=new ArrayList();
	public boolean transformed=false;
	public List<Item> resultItemList;
	
	@Action(value="ItemPanel")
	public String execute() throws Exception {
		log.debug("ItemPanel controller");
		if(this.action.equalsIgnoreCase("delete")){
			 boolean del=false;
			
			 for(int i=0;i<itemCheck.size();i++)
			 {  //code to delete nodes
				  log.debug("looking for node to delete:"+itemCheck.get(i));
				   del=true;
				 
			 }
			 if(del){
				 //DB.commit();
				 setActionmessage("Items successfully deleted");
			 }
			
		 }
		if(startItem>this.getItemCount()){
			 setActionmessage("Page does not exist.");
			 startItem=0;}
		return SUCCESS;
	}
	
	public List<DataUpload> getImports() {
		
		
		Organization org = DB.getOrganizationDAO().findById(this.organizationId, false);
		
		List<DataUpload> du = new ArrayList<DataUpload>();
		if(this.userId>-1){
			User u = DB.getUserDAO().findById(userId, false);
			 du=DB.getDataUploadDAO().findByOrganizationUser(org, u);
			 //check transform status and set boolean here
			 return du;
		}
		du= DB.getDataUploadDAO().findByOrganization(org);
		
		if( du == null ) return Collections.emptyList();
		
		
		//log.debug("startImport:"+startImport+"  maxImports:"+maxImports);
		
		return du;
	} 
	

	public void setItemCheck(String itemCheck){
		this.itemCheck=new ArrayList();
		if(itemCheck.trim().length()>0){
			String[] chstr=itemCheck.split(",");
			java.util.Collection<String> c=java.util.Arrays.asList(chstr);
		    this.itemCheck.addAll(c);
		}
	}

	public void setTransformed(boolean transformed){
		this.transformed=transformed;
	}
	
	public boolean getTransformed(){
		return transformed;
	}
	
    public String getActionmessage(){
		  return(actionmessage);
	}
    
    public void setAction(String action){
		this.action=action;
	}
		  
	public void setActionmessage(String message){
		  this.actionmessage=message;
	}
	  
	public int getStartItem() {
		return startItem;
	}

	public void setStartItem( int startItem ) {
		this.startItem = startItem;
	}

	public int getEndItem() {
		return endItem;
	}
	public int getMaxItems() {
		return maxItems;
	}

	public void setMaxItems(int maxItems) {
		this.maxItems = maxItems;
	}


	public long getOrganizationId() {
		return organizationId;
	}

	public void setOrganizationId(long organizationId) {
		this.organizationId = organizationId;this.o=DB.getOrganizationDAO().findById(organizationId, false);
	}

	public Organization getO(){
		return this.o;
	}

	public long getUploadId() {
		return uploadId;
	}

	public void setUploadId(long uploadId) {
		this.uploadId = uploadId;
	}

	public long getUserId() {
		return userId;
	}

	public void setUserId(long userId) {
		this.userId = userId;
		this.u=DB.getUserDAO().findById(userId, false);
		
	}
	
	public User getU(){
		return this.u;
	}
	
	/**
	 * @return
	 */
	public List<Item> getItems() {

		List<DataUpload> ld = findUploads();
		return itemsByUploads(ld);
	}

	/**
	 * Use the same upload as getItems to find the overall count of items.
	 * @return
	 */
	public int getItemCount() {
		int result=0;

		List<DataUpload> ld = findUploads();
		for( DataUpload du: ld ) {
			if( du.getItemXpath() != null ) 
				result += (int) du.getItemXpath().getCount();
		}
		return result;
	}
	
	/**
	 * Select which uploads are involved in this item Panel call
	 * Use it to retrieve items or item count !!
	 * 3 cases:
	 *  - For a specific upload: the uploadID > 1
	 *  - For an organization all items (the "normal" case) organizationId > -1 userId == -1
	 *  - For a specific user in an org: organizationId and userId > -1 
	 * @return
	 */
	private List<DataUpload> findUploads() {
		List<DataUpload> result = Collections.emptyList();
		if( getUploadId() > -1 ) {
			log.debug( "Items by Upload " + uploadId );
			result = new ArrayList<DataUpload>();
			DataUpload du = DB.getDataUploadDAO().getById(getUploadId(), false);
			result.add( du );
		} else {
			Organization org = DB.getOrganizationDAO().findById(organizationId, false);
			if( org != null ) {
				if( getUserId() > -1 ) {
					log.debug( "Items by User " + userId + " and Org " + organizationId );
					User user = DB.getUserDAO().findById(this.userId,false);
					result = DB.getDataUploadDAO().getByUserOrg( user, org );
				} else {
					log.debug( "Items by Org " + organizationId );
					result = org.getDataUploads();
				}
			}
		}
		return result;
	}
	


	/**
	 * All items for given list of uploads. Respect startItem and maxItem
	 * @param uploads
	 * @return
	 */
	private List<Item> itemsByUploads( List<DataUpload> uploads ) {

		if( resultItemList != null ) return resultItemList;
		resultItemList = new ArrayList<Item>();
		long currentStart = 0l;
 		int itemsRead = 0;
		boolean transform=false;
		for( DataUpload du: uploads ) {
			transform=false;
			if( du.getItemXpath() != null ) {
				if(DB.getTransformationDAO().findByUpload(du).size()>0){
					if(DB.getTransformationDAO().findByUpload(du).get(0).getStatusCode()==0)
					transform=true;
				}
					if( currentStart + du.getItemXpath().getCount() < startItem ) {
					currentStart += du.getItemXpath().getCount();
					continue;
				}
				List<XMLNode> l = du.getItemXpath().getNodes(startItem+itemsRead-currentStart, maxItems);
				for( XMLNode x: l ) {
					log.debug( "get tree .. " );
					XMLNode tree = DB.getXMLNodeDAO().getDOMTree(x);
					boolean trunc=false;
					if(tree.getSize()>20000){
						trunc=true;
					}
				
					log.debug( " . done");
					if( itemsRead < maxItems ) {
						List<? extends XMLNode> labelList = tree.getChildrenByXpath(du.getItemLabelXpath());
						if( labelList.size() != 1 ) log.warn( "Label not unique");
						
							XMLNode label;
							Item i = new Item();
							if( labelList.size() == 1 ) {
								label = labelList.get(0);
								i.name = label.getContent();
							} else if( labelList.size() == 0 ) {
								i.name = "<no label>";
							} else {
								i.name = "<"+labelList.size()+" labels>";
							}
							i.truncated=trunc;
							i.uploadId = du.getDbID();
							i.direct=du.isDirect();
							i.nodeId = x.getNodeId();
							i.transformed=transform;
							if( du.getUploadDate() == null ) i.itemdate="";
							else
								i.itemdate=new SimpleDateFormat("dd/MM/yyyy HH:mm").format(du.getUploadDate());
							i.importname=du.getOriginalFilename();
							/*if( du.getOriginalFilename().length()<15 )
							i.importname=du.getOriginalFilename();
						else
							i.importname=".."+du.getOriginalFilename()
							.substring(du.getOriginalFilename().length()-12);
							 */
							resultItemList.add( i );
							itemsRead += 1;
							log.debug("adding item:"+itemsRead+" with date:"+i.getDate());
					}
				}
				if( itemsRead == maxItems ) break;
				currentStart += du.getItemXpath().getCount();
			}
		}
		endItem = startItem+resultItemList.size();
		return resultItemList;
	}
	
	public String getPreviousPage() {
		if( startItem > 9 ) return (startItem-10)+", 10, " + this.organizationId+","+this.uploadId+","+this.userId;
		else return "0,10,"+ this.organizationId+","+this.uploadId+","+this.userId;
	}

	
	
}