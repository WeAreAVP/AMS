package gr.ntua.ivml.mint.persistent;

import java.sql.Blob;

/**
 * Helper class. The Blob in an object with updates was just too difficult
 * to manage with hibernate.
 * @author arne
 *
 */
public class BlobWrap {
	Blob data;
	Long dbID;
	
	public Long getDbID() {
		return dbID;
	}

	public void setDbID(Long dbID) {
		this.dbID = dbID;
	}

	public Blob getData() {
		return data;
	}

	public void setData(Blob data) {
		this.data = data;
	}
	
}
