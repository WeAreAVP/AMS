import gr.ntua.ivml.athena.persistent.Transformation
import gr.ntua.ivml.athena.concurrent.Queues;
// print some internal queue info

dbe = Queues.queues["db"]
dbq = dbe.getQueue()
nete = Queues.queues["net"]
netq = nete.getQueue()

printf( "%10s%10s%10s\n",["Name","pending","running"] )
printf( "%10s%10d%10d\n",["db",dbq.size(), dbe.getActiveCount() ] )
printf( "%10s%10d%10d\n",["net",netq.size(), nete.getActiveCount() ] )
