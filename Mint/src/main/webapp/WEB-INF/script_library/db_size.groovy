import groovy.sql.*

// Print size of tables and database

def prettyNum( num ) {
  def suf = [ "","k", "M", "G", "T", "P", "E"  ];
  def ind = 0;
  while( num > 1000.0 ) {
    ind +=1
    num /= 1000.0
  }
  return sprintf( "%1.3g%S",[num, suf[ind]]);
}

c = DB.getStatelessSession().connection()
md = c.getMetaData();
uname = md.getUserName()

sql = new Sql( c )
uid = sql.firstRow( "select usesysid from pg_user where usename = '$uname'" ).usesysid
tables = sql.rows( "select relname, reltuples, relpages from pg_class where relowner = $uid order by relpages desc" )
tables.each{if( it.relpages > 5 ) {println "Table/Index ${it.relname} - rows ${prettyNum(it.reltuples)} - " + prettyNum( it.relpages*8000.0)}}
pages = 0
tables.each{ pages += it.relpages }

"Total space " + prettyNum(pages*8000.0)


