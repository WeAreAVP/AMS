package gr.ntua.ivml.mint.db;

import java.io.Serializable;
import java.lang.reflect.Field;
import java.lang.reflect.ParameterizedType;
import java.lang.reflect.Type;
import java.util.HashSet;
import java.util.List;
import java.util.Set;

import org.apache.log4j.Logger;
import org.hibernate.Criteria;
import org.hibernate.LockMode;
import org.hibernate.NonUniqueObjectException;
import org.hibernate.ScrollMode;
import org.hibernate.ScrollableResults;
import org.hibernate.Session;
import org.hibernate.Transaction;
import org.hibernate.criterion.Criterion;
import org.hibernate.criterion.Example;
import org.hibernate.exception.ConstraintViolationException;

public class DAO<T, ID extends Serializable> {

    protected Class<T> persistentClass;
    static Logger daoLog = Logger.getLogger( DAO.class );
    
    Logger log;
    public DAO() {
        this.persistentClass = (Class<T>) ((ParameterizedType) getClass()
                                .getGenericSuperclass()).getActualTypeArguments()[0];
        log = Logger.getLogger( this.getClass() );
     }


    public Transaction beginTransaction() {
    	return getSession().beginTransaction();
    }
    
    public Session getSession() {
    	return DB.getSession();
    }

    public Class<T> getPersistentClass() {
        return persistentClass;
    }

    public void deleteAll() {
  	  boolean commit = false;
	  Transaction t = getSession().getTransaction();
	  if( !t.isActive()) {
		  t=getSession().beginTransaction();
		  commit = true;
	  }
	  getSession().createQuery(
			    "delete from " + getPersistentClass().getCanonicalName())
			    .executeUpdate();
	  if( commit )
		  t.commit();
    }
    
    /**
     * This method doesn't check if the object is in the database.  
     * @param id
     * @param lock
     * @return
     */
    @SuppressWarnings("unchecked")
    public T findById(ID id, boolean lock) {
        T entity;
        if (lock)
            entity = (T) getSession().load(getPersistentClass(), id, LockMode.UPGRADE);
        else
            entity = (T) getSession().load(getPersistentClass(), id);

        return entity;
    }

    /**
     * This method goes to the DB and checks if the object is actually there.
     * @param id
     * @param lock
     * @return
     */
    @SuppressWarnings("unchecked")
    public T getById(ID id, boolean lock) {
        T entity;
        if (lock)
            entity = (T) getSession().get(getPersistentClass(), id, LockMode.UPGRADE);
        else
            entity = (T) getSession().get(getPersistentClass(), id);

        return entity;
    }
    

    @SuppressWarnings("unchecked")
    public List<T> findAll() {
        return findByCriteria();
    }

    public ScrollableResults scrollAll() {
        Criteria crit = getSession().createCriteria(getPersistentClass());
        return crit.scroll(ScrollMode.FORWARD_ONLY);
    }
    
    @SuppressWarnings("unchecked")
    public List<T> findByExample(T exampleInstance, String[] excludeProperty) {
        Criteria crit = getSession().createCriteria(getPersistentClass());
        Example example =  Example.create(exampleInstance);
        for (String exclude : excludeProperty) {
            example.excludeProperty(exclude);
        }
        crit.add(example);
        return crit.list();
    }

    /**
     * Will return null if things didn't turn out well.
     * @param entity
     * @return
     */
    @SuppressWarnings("unchecked")
    public T makePersistent(T entity) {
    	boolean commit = false;

    	Transaction t = getSession().getTransaction();
    	if( !t.isActive()) {
    		t=getSession().beginTransaction();
    		commit = true;
    	}
    	try {
    		getSession().saveOrUpdate(entity);
    		getSession().flush();
        	if( commit )
        		t.commit();
    	} catch( ConstraintViolationException he ) {
    		t.rollback();
    		log.warn( "Session closed!");
    		getSession().close();
    		DB.removeSession();
    		DB.setSession( DB.newSession());
    		if( !commit )
    			getSession().beginTransaction();
    		commit = false;
    		entity = null;
    	} catch( NonUniqueObjectException nve ) {
    		// a different object with the same id was in session
    		log.warn( "A merge was issued, try to avoid creating objects from scratch!");
    		T newEntity = (T) getSession().merge(entity);
    		getSession().flush();
    		if( commit )
    			t.commit();
    		entity = newEntity;
    		// maybe we need to catch stuff here too
    	}
    	return entity;
    }

    
    /**
     * tried to catch the constraint violation exception and 
     * make a new session and transaction.
     * 
     * When this returns false you should not use any of the objects 
     * you retrieved during the running request.
     * 
     *  You have a new transaction running. All previously fetched objects 
     *  don't have "magic" any more (lazy loading etc)
     *  
     * @param entity
     * @return
     */
    public boolean makeTransient(T entity ) {
  	  boolean result = true;
	  boolean commit = false;
	  
	  // if the DAO is used in an environment where there is no transaction running
	  // It creates one automatically. 
	  Transaction t = getSession().getTransaction();
	  if( !t.isActive()) {
		  t=getSession().beginTransaction();
		  commit = true;
	  }

	  try {
		  getSession().delete(entity);
		  getSession().flush();
		  if( commit )
			  t.commit();
	  } catch(ConstraintViolationException he ) {
		  result = false;
		  t.rollback();
		  getSession().close();
		  DB.removeSession();
		  DB.setSession( DB.newSession());
		  if( !commit )
			  getSession().beginTransaction();
		  commit = false;
	  } finally {
		  // don't know, probably nothing
	  }
	  return result;
    }


    public long count( String simpleCondition ) {
    	Long count = (Long) getSession()
    	.createQuery("select count(*) from "+ getPersistentClass().getName() + 
    			(simpleCondition==null?"":(" where " + simpleCondition )))
    	.iterate().next();
    	return count.longValue();
    }
    
    public long count() {
    	return count( null );
    }
   
    @SuppressWarnings("unchecked")
    public T simpleGet( String condition ) {
    	List<T> l = getSession().createQuery(" from " + getPersistentClass().getName() +
    			(condition==null?"":" where " + condition ))
    			.list();
    	if( l.size()> 0 ) {
    		return (T)l.get(0);
    	} else {
    		return null;
    	}    	
    }
    
    
    @SuppressWarnings("unchecked")
    public List<T> simpleList( String condition) {
    	List<T> l = getSession().createQuery(" from " + getPersistentClass().getName() +
    			(condition==null?"":" where " + condition ))
    			.list();
    	return l;
    }
    
     public void flush() {
        getSession().flush();
    }

    public void clear() {
        getSession().clear();
    }

    /**
     * Use this inside subclasses as a convenience method.
     */
    @SuppressWarnings("unchecked")
    protected List<T> findByCriteria(Criterion... criterion) {
        Criteria crit = getSession().createCriteria(getPersistentClass());
        for (Criterion c : criterion) {
            crit.add(c);
        }
        return crit.list();
   }
    
	public static void  generateFieldnames( int level, String prefix, Class clazz, Set<String> result ) {
		if( level == 0 ) return;
		for( Field f:clazz.getDeclaredFields()) {
			if( f.getAnnotation( org.hibernate.search.annotations.Field.class ) != null ) {
				if( f.getAnnotation( org.hibernate.search.annotations.FieldBridge.class ) != null ) {
					result.add( prefix+f.getName()+"_el" );
					result.add( prefix+f.getName()+"_en" );
					result.add( prefix+f.getName()+"_xx" );
				} else 
					result.add( prefix+f.getName() );
			}
			if( f.getAnnotation( org.hibernate.search.annotations.IndexedEmbedded.class ) != null ) {
				Type t = f.getGenericType();
				if( t instanceof ParameterizedType ) {
					// this assumes lists, doesnt make sense with other paramterized types
					// and doesn't work with Asset<VideoFileDescriptor> eg
					t = ((ParameterizedType)t).getActualTypeArguments()[0];
					if( t instanceof Class )
						generateFieldnames(level-1, prefix+f.getName()+".", (Class) t, result);
				} else 
					generateFieldnames(level-1, prefix+f.getName()+".", f.getType(), result);
			}
		}
	}

    protected Set<String> classFields( String prefix ) {
    	HashSet<String> fields = new HashSet<String>();
    	if( prefix == null ) prefix = "";
    	for( Field f:persistentClass.getDeclaredFields()) {
    			fields.add( prefix+f.getName());
    	}
    	return fields;
    }
} 