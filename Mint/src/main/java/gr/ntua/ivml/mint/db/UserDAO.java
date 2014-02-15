package gr.ntua.ivml.mint.db;


import java.util.List;

import gr.ntua.ivml.mint.persistent.User;

import org.hibernate.Session;
import org.hibernate.Transaction;
import org.hibernate.criterion.Example;
import org.hibernate.type.Type;

public class UserDAO extends DAO<User, Long> {
	public User getByLoginPassword( String login, String password ) {
		User u;
		u = new User();
		u.encryptAndSetLoginPassword(login, password);
		Example e = Example.create( u );
		e.setPropertySelector( new Example.PropertySelector() {
			public boolean include( Object value, String name, Type type ) {
				if( name.equals( "login") || name.equals( "md5Password" ))
					return true;
				else
					return false;
			}
		});
		Session s = getSession();
		Transaction t = s.beginTransaction();
		u = (User) s.createCriteria(User.class)
		.add( e )
		.uniqueResult();
		t.commit();
		return u;
	}
	

	
	public User getByLogin( String login) {
		List<User> users = getSession().createQuery( "from User where login=:login")
		.setString( "login", login )
		.setFetchSize(1)
		.list();
		
		if((users == null) || (users.size() == 0))
			return null;
		
		return users.get(0);
	}
	
	public boolean isLoginAvailable( String login ) {
		Long l = (Long) getSession().createQuery( "select count(*) from User where login=:login")
		.setString( "login", login )
		.iterate().next();
		return ( l.longValue() == 0l );
	}
 }
