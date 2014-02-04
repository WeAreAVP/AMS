package gr.ntua.ivml.mint.persistent;

import java.lang.reflect.Method;
import java.lang.reflect.Modifier;

import org.apache.log4j.Logger;

/**
 * Current implementation is based on specific actions are allowed
 * not allowed for users on objects.
 * 
 * Other implementations could go and check against the database 
 * or access tokens on the User etc.
 * 
 * Write one function per action.
 * Spaces in the actions are replaced with _
 * @author Arne Stabenau
 *
 */
public class AccessAuthenticationLogic {
	static final Logger log = Logger.getLogger(AccessAuthenticationLogic.class );
	
	/**
	 * Super users can do everything, otherwise the request for authentication is 
	 * delegated to the action method.
	 * @param u
	 * @param se
	 * @param action
	 * @return
	 */
	public static boolean can( User u, SecurityEnabled se, String action ) {
		boolean result = false;
		if( u.getRights() == User.SUPER_USER) return true;
		if( se == null ) {
			log.info( "Authentication " +action+ " is missing argument" );
			return false;
		}
		result = dispatch(u, se, action.replace(" ", "_"));
		log.debug( action + " " + (result?"yes":"no" ));
		return result;
	}
	
	
	// a function for every action that needs it, maybe some actions are caught in 
	// the beginning
	

	/**
	 * Only super users can make super users. Everybody else
	 * gets a false from this function.
	 * @param u
	 * @param se
	 * @return
	 */
	private static boolean action_make_super_user( User u, SecurityEnabled se ) {
		return false;
	}
	
	private static boolean action_server_file_access( User u, SecurityEnabled se  ) {
		return false;
	}
	
	private static boolean action_download( User u, SecurityEnabled se ) {
		try {
			DataUpload du = (DataUpload) se;
			if( du.getUploader().getDbID() == u.getDbID()) return true;
			Organization o = du.getOrganization();
			return ( belongs( u, o) && 
					(((u.getRights()&(User.ADMIN|User.PUBLISH|User.MODIFY_DATA))!=0)));
		} catch( Exception e ) {
			log.info( "download needs DataUplaod as argument", e );
		}
		return false;
	}
	

	
	/**
	 * se is Organization data belongs to
	 * @param u
	 * @param se
	 * @return
	 */
	private static boolean action_change_data( User u, SecurityEnabled se ) {
		try {
			Organization o = (Organization) se;
			return ( belongs( u, o) && ( u.hasRight(User.MODIFY_DATA) || ( u.hasRight( User.ADMIN))));
		} catch( Exception e ) {
			log.info( "change data needs Organization as argument" ,e );
		}
		return false;
	}

	/**
	 * se is Organization data belongs to
	 * @param u
	 * @param se
	 * @return
	 */
	private static boolean action_view_data( User u, SecurityEnabled se ) {
		try {
			Organization o = (Organization) se;
			return ( belongs( u, o) && (u.getRights()>0));
		} catch( Exception e ) {
			log.info( "change data needs Organization as argument" ,e );
		}
		return false;
	}

	
	/**
	 * se is organization to be modified
	 * @param u
	 * @param se
	 * @return
	 */
	private static boolean action_modify_organization( User u, SecurityEnabled se ) {
		try {
			Organization o = (Organization) se;
			return ( belongs( u, o) && u.hasRight(User.ADMIN));
		} catch( Exception e ) {
			log.info( "modify organization needs Organization as argument" ,e );
		}
		return false;
	}
	/**
	 * se is organization the data belongs to
	 * @param u
	 * @param se
	 * @return
	 */
	private static boolean action_publish_data( User u, SecurityEnabled se ) {
		try {
			Organization o = (Organization) se;
			return ( belongs( u, o) && u.hasRight(User.PUBLISH));
		} catch( Exception e ) {
			log.info( "publish data needs Organization as argument" ,e );
		}
		return false;
	}
	
	/**
	 * se is organization the data belongs to
	 * @param u
	 * @param se
	 * @return
	 */
	private static boolean action_view_unpublished( User u, SecurityEnabled se ) {
		try {
			Organization o = (Organization) se;
			return belongs( u, o);
		} catch( Exception e ) {
			log.info( "view unpublished needs Organization as argument" ,e );
		}
		return false;
	}
	
	/**
	 * se is user to be modified
	 * @param u
	 * @param se
	 * @return
	 */
	private static boolean action_modify_user( User u, SecurityEnabled se ) {
		try {
			User u2 = (User) se;
			if( u2.getDbID() == u.getDbID()) return true;
			return ( belongs( u, u2.getOrganization()) && u.hasRight(User.ADMIN));
		} catch( Exception e ) {
			log.info( "modify user needs User as argument" ,e );
		}
		return false;
	}

	
	/**
	 * se is user to be modified. Call for stuff the user is not supposed to do on himself
	 * @param u
	 * @param se
	 * @return
	 */
	private static boolean action_admin_user( User u, SecurityEnabled se ) {
		try {
			User u2 = (User) se;
			return ( belongs( u, u2.getOrganization()) && u.hasRight(User.ADMIN));
		} catch( Exception e ) {
			log.info( "change rights needs User as argument" ,e );
		}
		return false;
	}

	
	private static boolean belongs( User u, Organization o ) {
		if( o == null ) return false;
		do {
			if( u.getOrganization().getDbID() == o.getDbID())
				return true;
		} while(( o=o.getParentalOrganization()) != null );			
		return false;
	}
	

	private static boolean dispatch( User u, SecurityEnabled se, String action ) {
		try {
			Method[] methods = AccessAuthenticationLogic.class.getDeclaredMethods();
			for( Method m: methods ) {
				if( Modifier.isStatic( m.getModifiers()) ) {
					if( m.getName().startsWith("action_")) {
						if(m.getName().endsWith(action)) {
							// call it
							Boolean b = (Boolean) m.invoke(null, u, se );
							log.debug( "Invoked " + action);
							return b.booleanValue();
						}
					}
				}
			}
			log.warn( "No such action " +action);
			return false;
		} catch( Exception e ) {
			log.debug( e );
		}
		return false;
	}
}
