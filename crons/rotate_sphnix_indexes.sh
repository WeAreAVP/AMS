#!/bin/bash

#####################################
#                                   #
#  Shell Script for rotating the    #
#  sphnix indexes.                  #
#  This script will prevent         #
#  from running method more         #
#  than one time                    #
#  Developer : Nouman Tayyab        #
#  nouman@avpreserve.com            #
#                                   #
#####################################

source /root/.bash_profile

HOST=`/bin/hostname`
PID_FILE="PIDs/rotate_sphnix_indexes.pid"
OUTPUT_FILE="cronlog/rotate_sphnix_indexes.log"
BASEDIR="/var/www/html/"
DATE=`date`

PHP_PATH=`which php`
PID_FULLPATH=$BASEDIR$PID_FILE
OUTPUT_FILE=$BASEDIR$OUTPUT_FILE

touch $PID_FULLPATH
touch $OUTPUT_FILE

PID=`cat $PID_FULLPATH`
PIDD=/proc/$PID 
if [ -n "$PID" ]  && [ -d $PIDD ]
then
echo "Already running ...."
else
CMD="$PHP_PATH ${BASEDIR}index.php crons rotate_sphnix_indexes > $OUTPUT_FILE 2>&1 & echo $! > $PID_FULLPATH"
MSG="ENV [$ENVIRONMENT]"
echo $MSG >> $OUTPUT_FILE
MSG="Starting cron at $DATE"
echo $MSG >> $OUTPUT_FILE
echo $CMD >> $OUTPUT_FILE

$PHP_PATH ${BASEDIR}index.php crons rotate_sphnix_indexes > $OUTPUT_FILE 2>&1 & echo $! > $PID_FULLPATH
PID=`cat $PID_FULLPATH`
echo "Started Cron [$PID]"
echo "To view logs Use tail -f $OUTPUT_FILE"
fi