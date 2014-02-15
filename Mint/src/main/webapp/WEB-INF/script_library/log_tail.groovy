import org.apache.commons.io.CopyUtils

// Show the last 1000 log lines
//  of catalina.home/logs/catalina.out

pr = Runtime.getRuntime().exec( "tail -1000 " + System.getProperty( "catalina.home" ) + "/logs/catalina.out " )
CopyUtils.copy( pr.getInputStream(), System.out )
pr.waitFor()
