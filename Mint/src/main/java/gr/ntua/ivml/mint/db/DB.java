package gr.ntua.ivml.mint.db;

import gr.ntua.ivml.mint.persistent.BlobWrap;
import gr.ntua.ivml.mint.persistent.Crosswalk;
import gr.ntua.ivml.mint.persistent.DataUpload;
import gr.ntua.ivml.mint.persistent.Lock;
import gr.ntua.ivml.mint.persistent.Mapping;
import gr.ntua.ivml.mint.persistent.Organization;
import gr.ntua.ivml.mint.persistent.Publication;
import gr.ntua.ivml.mint.persistent.SchemaPublication;
import gr.ntua.ivml.mint.persistent.Thesaurus;
import gr.ntua.ivml.mint.persistent.ThesaurusAssignment;
import gr.ntua.ivml.mint.persistent.Transformation;
import gr.ntua.ivml.mint.persistent.User;
import gr.ntua.ivml.mint.persistent.XMLNode;
import gr.ntua.ivml.mint.persistent.XmlSchema;
import gr.ntua.ivml.mint.util.Config;

import java.io.BufferedReader;
import java.io.IOError;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.lang.reflect.Method;
import java.nio.charset.Charset;
import java.sql.Connection;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.Arrays;
import java.util.HashSet;
import java.util.Hashtable;
import java.util.List;
import java.util.Set;

import org.apache.log4j.Logger;
import org.hibernate.Session;
import org.hibernate.SessionFactory;
import org.hibernate.StatelessSession;
import org.hibernate.Transaction;
import org.hibernate.cfg.AnnotationConfiguration;
import org.hibernate.impl.SessionFactoryImpl;

public class DB {
	private static SessionFactory sessionFactory;
	private static TestSetup testSetup;
	private static boolean schemaSetup;
	static final Logger log = Logger.getLogger( DB.class );
	
	//threadlocal, but I rig it myself ..
	private static Hashtable<Long, Session> sessions = new Hashtable<Long,Session>();

	private static ThreadLocal<StatelessSession> statelessSessions = new ThreadLocal<StatelessSession>();
	

	static {
		initSession();
	}

	private static void initSession() {
		try {
			// Create the SessionFactory from hibernate.cfg.xml
			// or, like here, from hibernate.properties
			Class<?>[] classes = { User.class, Organization.class, 
							DataUpload.class, BlobWrap.class,
							XMLNode.class, Lock.class,
							Mapping.class, Transformation.class,
							Publication.class, XmlSchema.class,
							Crosswalk.class, Thesaurus.class,
							ThesaurusAssignment.class };

			Set<Class<?>> classSet = new HashSet<Class<?>>();
			classSet.addAll( Arrays.asList( classes ));
			
			if(Config.has("publish") && Config.get("publish").equalsIgnoreCase("newschema")){
				classSet.remove( Publication.class );
				classSet.add( SchemaPublication.class );
				log.debug( "Replaced Pulication with SchemaPublication!");}
		
			AnnotationConfiguration ac = new AnnotationConfiguration();

			// is there custom db stuff ?
			try {
				Class<?> customDB = Class.forName("gr.ntua.ivml.mint.db.CustomDB");
				Method m = customDB.getMethod("init", AnnotationConfiguration.class, Set.class );
				m.invoke(null, ac, classSet );
			} catch( Exception e ) {
				log.debug( "No CustomDB found" );
			}

			for( Class<?> c: classSet ) {
				ac.addClass(c);
			}

			sessionFactory = ac.buildSessionFactory( );
			
		} catch (Throwable ex) {
			// Make sure you log the exception, as it might be swallowed
			log.error("Initial SessionFactory creation failed." , ex);
			throw new ExceptionInInitializerError(ex);
		}
		log.info( "SessionFactory instatiated" );
		
		// load the classes, they do some cleanup
		getLockManager();
		getDataUploadDAO();
		getTransformationDAO();
		new GlobalPrefixStore();
	}

	/*
	 * Should try and open session when request comes in
	public static Session getSession() {
		return sessionFactory.openSession();
	}	
	 */
	public static void setSession( Session s ) {
		long threadId = Thread.currentThread().getId();
		sessions.put( threadId, s );		
	}

	public static void removeSession() {
		long threadId = Thread.currentThread().getId();
		sessions.remove( threadId );
	}
	
	public static Session getSession() {
		long threadId = Thread.currentThread().getId();
		Session s;
		s = sessions.get( threadId );
		if((s == null ) || ( !s.isOpen())) {
			s = sessionFactory.openSession();
			log.debug( "Session created!");
			sessions.put( threadId, s );
		}
		return s;
	}	
	
	public static StatelessSession getStatelessSession() {
		StatelessSession ss = statelessSessions.get();
		if( ss == null ) {
			try {
			Connection c  = ((SessionFactoryImpl)sessionFactory).getConnectionProvider().getConnection();
			ss = sessionFactory.openStatelessSession(c);
			log.debug( "StatelessSession created!");
			statelessSessions.set( ss );
			} catch( SQLException se ) {
				log.error( "No stateless Session", se );
			}
		}
		return ss;
	}
	
	public static void closeStatelessSession() {
		StatelessSession ss = statelessSessions.get();
		if( ss != null ) {
			try {
				ss.connection().close();
			} catch( SQLException e ) {
				log.error( e );
			}
			ss.close();
			statelessSessions.set( null );
		}
	}
	
	public static Session newSession() {
		closeSession();
		return getSession();
	}
	
	
	public static void closeSession() {
		long threadId = Thread.currentThread().getId();
		Session s = sessions.get( threadId );
		if( s != null ) {
			s.close();
			sessions.remove(threadId );
		}
	}
	
	public static void logPid() {
		Session s = getSession();
		Connection c = s.connection();
		logPid(c );
	}
	
	public static void logPid( Connection c ) {
		try {
		Statement st = c.createStatement();
		st.execute("select pg_backend_pid()");
		ResultSet rs = st.getResultSet();
		rs.next();
		log.debug( "Thread: " + Thread.currentThread().getName() + " pid = " + rs.getInt(1));
		} catch( Exception e ) {
			log.debug( "Cant log transaction id " + e.getMessage());
		}
	}
	
	

	
	// test to write out current transaction (and create new one)
	public static void commit() {
		getSession().flush();
		getSession().getTransaction().commit();
		getSession().beginTransaction();
	}
	
	public static LockManager getLockManager() {
		return new LockManager();
	}
	
	public static CrosswalkDAO getCrosswalkDAO() {
		return (CrosswalkDAO) instantiateDAO( CrosswalkDAO.class );
	}

	public static XmlSchemaDAO getXmlSchemaDAO() {
		return (XmlSchemaDAO) instantiateDAO( XmlSchemaDAO.class );
	}

	public static UserDAO getUserDAO() {
		return (UserDAO) instantiateDAO( UserDAO.class );
	}

	public static TransformationDAO getTransformationDAO() {
		return (TransformationDAO) instantiateDAO( TransformationDAO.class );
	}

	public static XMLNodeDAO getXMLNodeDAO() {
		return (XMLNodeDAO) instantiateDAO( XMLNodeDAO.class );
	}

	public static XpathHolderDAO getXpathHolderDAO() {
		return (XpathHolderDAO) instantiateDAO( XpathHolderDAO.class );
	}

	public static OrganizationDAO getOrganizationDAO() {
		return (OrganizationDAO) instantiateDAO( OrganizationDAO.class );
	}
	
	public static XmlObjectDAO getXmlObjectDAO() {
		return (XmlObjectDAO) instantiateDAO( XmlObjectDAO.class );
	}
	
	public static DataUploadDAO getDataUploadDAO() {
		return (DataUploadDAO) instantiateDAO( DataUploadDAO.class );
	}

	public static MappingDAO getMappingDAO() {
		return (MappingDAO) instantiateDAO( MappingDAO.class );
	}

	public static PublicationDAO getPublicationDAO() {
		return (PublicationDAO) instantiateDAO( PublicationDAO.class );
	}	

	public static ThesaurusDAO getThesaurusDAO() {
		return (ThesaurusDAO) instantiateDAO( ThesaurusDAO.class );
	}
	
	public static ThesaurusAssignmentDAO getThesaurusAssignmentDAO() {
		return (ThesaurusAssignmentDAO) instantiateDAO( ThesaurusAssignmentDAO.class );
	}

	

	private static DAO instantiateDAO(Class<? extends DAO> daoClass) {
        try {
            DAO dao = (DAO)daoClass.newInstance();
            return dao;
        } catch (Exception ex) {
            throw new RuntimeException("Can not instantiate DAO: " + daoClass, ex);
        }
    }

	public static void testSetup() {
		if( testSetup != null ) return;
		testSetup = new TestSetup();
	}
	
	private static StringBuffer readFile( String file ) throws IOException  {
		StringBuffer sb = new StringBuffer();
		InputStream is = DB.class.getClassLoader().getResourceAsStream(file);
		BufferedReader br = new BufferedReader( new InputStreamReader( is, Charset.forName( "UTF-8" )));
		String buffer;
		while((buffer = br.readLine()) !=  null) { 
			sb.append( buffer );
			sb.append( "\n" );
		}
		
		return sb;
	}
	
	public static void doSQL( String filename ) {
		try {
			StringBuffer sb = readFile( filename );
			Transaction t = DB.getSession().beginTransaction();
			DB.getSession().createSQLQuery(sb.toString()).executeUpdate();
			t.commit();
		} catch( Exception e ) {
			throw new IOError( e );
		}
	}

	public static void flush() {
		getSession().flush();
	}
	
	public static void initSchema() {
		doSQL( "createSchema.sql" );
	}
}


