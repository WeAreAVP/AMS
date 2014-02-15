package gr.ntua.ivml.mint.db;

import gr.ntua.ivml.mint.persistent.Lock;
import gr.ntua.ivml.mint.persistent.Lockable;
import gr.ntua.ivml.mint.persistent.User;

import java.io.PrintWriter;
import java.io.StringWriter;
import java.util.List;

import org.apache.log4j.Logger;
import org.hibernate.Hibernate;
import org.hibernate.HibernateException;
import org.hibernate.StatelessSession;
import org.hibernate.Transaction;
import org.hibernate.criterion.Example;

public class LockManager {
	static final Logger log = Logger.getLogger( LockManager.class );
	
	static {
		// on load remove all logs
		long count = -1;
		StatelessSession s = DB.getStatelessSession();
		Transaction tx = s.beginTransaction();
		try {
			count = s.createQuery( "delete Lock")
			.executeUpdate();
			tx.commit();
			log.debug( "Removed " + count + " stale locks." );
		} catch( HibernateException he ) {
			log.error( "locks not released" );
			tx.rollback();	
		} finally {
			DB.closeStatelessSession();
		}
	}
	
	public StatelessSession getSession() {
		return DB.getStatelessSession();
	}
	
	/**
	 * Like aquire lock only returns the lock, practical if you want to unlock after delete.
	 * @param u
	 * @param sessionId
	 * @param item
	 * @return
	 */
	public Lock directLock( User u, String sessionId, Lockable item ) {
		Lock result = null;
		StatelessSession s = getSession();
		Transaction tx = s.beginTransaction();
		if(( item==null ) || ( item.getDbID() == null)) 
			throw new Error( "Unlockable item");
		try {
			Lock lock = createLock(item);
			lock.setUserLogin(u.getLogin());
			lock.setHttpSessionId(sessionId);
			log.debug( "Aquire Lock\n" + lock.toString());
			 List<Lock> l = s.createQuery("from Lock where objectId = :id and objectType = :type")
				 .setLong("id", lock.getObjectId())
				 .setString("type", lock.getObjectType())
				 .list();
			 if( l.size() == 0 ) {
				 s.insert(lock);
				 tx.commit();
				 result = lock;
			 } else {
				 Lock lock2 = l.get(0);
				 if( lock2.getUserLogin().equals(lock.getUserLogin()) &&
						 lock2.getHttpSessionId().equals(lock.getHttpSessionId()) ) 
					 result = l.get( 0 );
				 else  
					 result = null;
				 tx.commit();
			 }
		} catch( HibernateException he ) {
			log.debug( "lock aquire failed" );
			StringWriter sw = new StringWriter();
			he.printStackTrace( new PrintWriter( sw ));

			log.error( sw.toString()); 
			tx.rollback();	
		} 
		return result;

	}
	
	/**
	 * Aquire lock for given item or check that you still have it.
	 * This should be done before database objects are updated, not just
	 * before they are made persistent. It should not be done 
	 * (Cannot be done) with objects that don't have a dbID yet.
	 * @param u
	 * @param sessionId
	 * @param item
	 * @return
	 */
	public boolean aquireLock( User u, String sessionId, Lockable item ) {
		boolean result = false;
		StatelessSession s = getSession();
		Transaction tx = s.beginTransaction();
		if(( item==null ) || ( item.getDbID() == null)) 
			throw new Error( "Unlockable item");
		try {
			Lock lock = createLock(item);
			lock.setUserLogin(u.getLogin());
			lock.setHttpSessionId(sessionId);
			log.debug( "Aquire Lock\n" + lock.toString());
			 List<Lock> l = s.createQuery("from Lock where objectId = :id and objectType = :type")
			 .setLong("id", lock.getObjectId())
			 .setString("type", lock.getObjectType())
			 .list();
			 if( l.size() == 0 ) {
				 s.insert(lock);
				 tx.commit();
				 result = true;
			 } else {
				 Lock lock2 = l.get(0);
				 if( lock2.getUserLogin().equals(lock.getUserLogin()) &&
						 lock2.getHttpSessionId().equals(lock.getHttpSessionId()) ) 
					 result = true;
				 else  
					 result = false;
				 tx.commit();
			 }
		} catch( HibernateException he ) {
			log.debug( "lock aquire failed" );
			StringWriter sw = new StringWriter();
			he.printStackTrace( new PrintWriter( sw ));

			log.error( sw.toString()); 
			tx.rollback();	
		} 
		return result;
	}
	
	/**
	 * Releases the lock on the item (only for given user and session).
	 * Returns false if the lock didn't exist (or was owned by somebody else)
	 * @param u
	 * @param sessionId
	 * @param item
	 * @return
	 */
	public boolean releaseLock( User u, String sessionId, Lockable item ) {
		boolean result = false;
		StatelessSession s = getSession();
		Transaction tx = s.beginTransaction();
		try {
			Lock example = createLock( item );
			example.setHttpSessionId(sessionId);
			example.setUserLogin(u.getLogin());

			Lock lock = (Lock) s.createCriteria(Lock.class)
			.add( Example.create( example ).excludeProperty("name"))
			.uniqueResult();
			
			s.delete(lock);
			tx.commit();
			result = true;
		} catch( HibernateException he ) {
			log.debug( "lock not released" );
			tx.rollback();	
		} 
		return result;
	}
	
	/**
	 * Releases the lock.
	 * That should always work if the lock exists.
	 * @param u
	 * @param sessionId
	 * @param item
	 * @return
	 */
	public boolean releaseLock( Lock lock ) {
		boolean result = false;
		StatelessSession s = getSession();
		Transaction tx = s.beginTransaction();
		try {
			s.delete(lock);
			tx.commit();
			result = true;
		} catch( HibernateException he ) {
			log.debug( "lock not released" );
			tx.rollback();	
		} 
		return result;
	}
	
	/**
	 * Release all locks for given session.
	 * Give back how many.
	 * @param sessionId
	 */
	public int releaseLocks( String sessionId ){
		int count = -1;
		StatelessSession s = getSession();
		Transaction tx = s.beginTransaction();
		try {
			count = s.createQuery( "delete Lock where httpSessionId=:session")
			.setString( "session", sessionId)
			.executeUpdate();
			tx.commit();
		} catch( HibernateException he ) {
			log.debug( "locks not released" );
			tx.rollback();	
		} 
		return count;
	}
	
	/**
	 * Need to find a specific lock.
	 * @param dbID
	 * @return
	 */
	public Lock getByDbID( Long dbID ) {
		Lock result = null;
		StatelessSession s = getSession();
		Transaction tx = s.beginTransaction();
		try {
			 result = (Lock) s.createQuery("from Lock where dbID = :id")
			 .setLong("id", dbID)
			 .uniqueResult();
		} catch( Exception e ) {
			log.error( "Cant retreive lock.", e );
			tx.rollback();	
		} 
		return result;
	}
	
	
	/**
	 * Check if the item is locked. Return the Lock to check since when, what User etc.
	 * Return null if there is no lock.  
	 * @param item
	 * @return
	 */
	public Lock isLocked( Lockable item ) {
		Lock result = null;
		StatelessSession s = getSession();
		Transaction tx = s.beginTransaction();
		try {
			Lock example = createLock( item );
			Lock lock = (Lock) s.createCriteria(Lock.class)
			.add( Example.create( example ).excludeProperty("httpSessionId")
						.excludeProperty("userLogin" )
						.excludeProperty("name"))
			.uniqueResult();
			tx.commit();
			if( lock != null ) 
				result = lock;
		} catch( HibernateException he ) {
			log.debug( "lock not released" );
			tx.rollback();	
		} 
		catch( Exception e ) {
			//this throws more than hibernate exception
			log.debug( "lock not released" );
			tx.rollback();	
		}
		
		return result;
	}
	
	
	/**
	 * Check if the item is accessible for the given User and session.
	 * It is if there is no lock or the user owns the lock.  
	 * @param item
	 * @return
	 */
	public boolean canAccess( User u, String sessionId, Lockable item ) {
		boolean result = true;
		StatelessSession s = getSession();
		Transaction tx = s.beginTransaction();
		try {
			Lock example = createLock( item );
			Lock lock = (Lock) s.createCriteria(Lock.class)
			.add( Example.create( example ).excludeProperty("httpSessionId")
						.excludeProperty("userLogin" )
						.excludeProperty("name"))
			.uniqueResult();
			tx.commit();
			if( lock != null ) 
			{
				if(( lock.getUserLogin().equals( u.getLogin())) &&
					( lock.getHttpSessionId().equals( sessionId)))
					result = true;
				else
					result = false;
			}
		} catch( HibernateException he ) {
			log.debug( "Lock problem in canAccess" );
			tx.rollback();	
		} 
		return result;
	}
	/**
	 * All the locks in the system. For the admin to play ...
	 * @return
	 */
	public List<Lock> findAll() {
		StatelessSession s = getSession();
		List<Lock> l = s.createQuery( "from Lock" ).list();
		return l;
	}
	
	public List<Lock> findByUser( User u ) {
		StatelessSession s = getSession();
		List<Lock> l = s.createQuery( "from Lock where userLogin = :login" )
		.setString("login", u.getLogin())
		.list();
		return l;
	}
	
	public List<Lock> findBySession(String sessionId ) {
		StatelessSession s = getSession();
		List<Lock> l = s.createQuery( "from Lock where httpSessionId = :sessionId" )
		.setString("sessionId", sessionId )
		.list();
		return l;
	}

	private Lock createLock( Lockable l ) {
		Lock lock = new Lock();
		String className = l.getClass().getCanonicalName();
		try {
			className = Hibernate.getClass( l ).getCanonicalName();
		} catch( HibernateException h ) {
			log.error( "Hibernate getClass() ", h );
		}
		//log.debug( "Example log: "+l.getLockname()+" "+l.getClass().getCanonicalName()+"->"+className );
		lock.setObjectId(l.getDbID());
		lock.setObjectType( className );
		lock.setName(l.getLockname());
		return lock;
	}
}
