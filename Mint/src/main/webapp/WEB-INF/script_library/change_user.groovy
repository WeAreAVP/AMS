import gr.ntua.ivml.athena.util.TokenRegistry;
import gr.ntua.ivml.athena.util.AnnotatorProxy;

// login as given user
// change the "admina" in getByLogin("admina") to
// the user you want to login as, to test what he can
// see and do
  
session = request.getSession();
user = DB.getUserDAO().getByLogin( "admina" );
if( user != null ) {
  session.setAttribute( "user", user );
}