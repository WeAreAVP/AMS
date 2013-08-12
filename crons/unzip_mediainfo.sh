#!/bin/bash

#####################################
#                                   #
#  Shell Script for unzipping       #
#  crawford media info files.	    #
#  This script will prevent         #
#  from running method more         #
#  than one time                    #
#  Developer : Nouman Tayyab        #
#  nouman@avpreserve.com            #
#                                   #
#####################################

for zip in  /home/crawford/audio_metadata_bag/*.zip ; do
     unzip -u -o  "$zip" -d  /var/www/html/assets/mediainfo/
done

for zip in  /home/crawford/video_metadata_bag/*.zip ; do
     unzip -u -o "$zip" -d /var/www/html/assets/mediainfo/
done