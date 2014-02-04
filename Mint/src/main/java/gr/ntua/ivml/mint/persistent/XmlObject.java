package gr.ntua.ivml.mint.persistent;

import gr.ntua.ivml.mint.db.DB;
import gr.ntua.ivml.mint.xml.Statistics;

import java.util.ArrayList;
import java.util.Collection;
import java.util.HashSet;
import java.util.List;
import java.util.Map;
import java.util.Set;

public class XmlObject {
	Long dbID;

	public Long getDbID() {
		return dbID;
	}

	public void setDbID(Long dbID) {
		this.dbID = dbID;
	}
	
	public XpathHolder getRoot() {
		return DB.getXpathHolderDAO().getRoot(this);
	}
	
	public XpathHolder getByPathWithPrefix( String path, boolean global ) {
		String nonPrefix = path.replaceAll("/[^:]+:", "/");
		List<XpathHolder> lx = DB.getXpathHolderDAO().getByPath(this, nonPrefix);
		if( lx.size() == 0 ) return null;
		if( lx.size() == 1 ) return lx.get(0);
		
		for( XpathHolder xp: lx ) {
			if( path.equals( xp.getXpathWithPrefix(global)))
				return xp;
		}
		
		return null;
	}
	
	public Statistics getStats() {
		return new Statistics( this );
	}
	
	/**
	 * list all the namespaces and prefixes that were used in the xml.
	 * The empty prefix may appear twice, once for the 'default' namespace
	 * if one is used and once for <no namespace>
	 * @return
	 */
	public List<String[]> listUriAndPrefix() {
		List<Object[]> l = DB.getXpathHolderDAO().listNamespaces(this);
		List<String[]> result = new ArrayList<String[]>( l.size());
		for( Object[] oa: l ) {
			String[] s2 = new String[2];
			s2[0] = (oa[0]==null?"":oa[0].toString().trim());
			s2[1] = (oa[1]==null?"":oa[1].toString().trim());
			result.add( s2 );
		}
		return result;
	}
	
	public Collection<String> listNamespaces() {
		Set<String> uris = new HashSet<String>();
		List<Object[]> l = DB.getXpathHolderDAO().listNamespaces(this);
		for( Object[] oa: l ) {
			String uri = (oa[1]==null?"":oa[1].toString().trim());
			uris.add( uri );
		}
		return uris;
	}
	
	public List<XpathHolder> getByNamespace( String uri ) {
		return DB.getXpathHolderDAO().getByUri(this, uri);
	}
	
	/**
	 * Key is xpath dbID value is avg length and count distinct
	 * @return
	 */
	public Map<Long, Object[]> getAllStats() {
		Map<Long, Object[]> stats = DB.getXMLNodeDAO().getStatsForXpaths(this);
		return stats;
	}

}
