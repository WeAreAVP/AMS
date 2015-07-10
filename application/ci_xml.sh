#!/bin/bash

# takes one argument - the ams.assets.asset_id - and returns an xml string for use by the AMS php application
# exits when no arg is given or when fails to authenticate with sony or when media item is not found
# requires data in ./config/ci.yml
# requires write access to /tmp/

if [ "$#" -ne 1 ];
	then echo; #exit;
fi;

config_file_path=/var/www/html/application/config/ci.yml;

#media_item_id='4bcdbbae70f14a5c822be7380e2ca26a'; ## REMOVE AFTER TESTING
# media_item_id=`echo "$1" | awk '{print $1}'`; ## THIS WAS USED WHEN ARG IN WAS THE SONY ID STRING
media_item_id=`mysql ams -u amsread --skip-column-names -B -e "select identifier from identifiers where identifier_source = 'Sony Ci' and assets_id=$1 LIMIT 1"`;

credString=`grep '^cred_string:' "$config_file_path"  | awk '{print $2}'`;
client_id=`grep '^client_id:' "$config_file_path"  | awk '{print $2}'`;
#client_id='badteststring';

client_secret=`grep '^client_secret:' "$config_file_path"  | awk '{print $2}'`;
workspace_id=`grep '^workspace_id:' "$config_file_path"  | awk '{print $2}'`;

access_token_filepath="/tmp/$workspace_id";
touch "$access_token_filepath";
access_token=`cat "$access_token_filepath"`;
refresh_token='';
media_getString='';
media_getResponseCode='';


function initAuth 
{
	curl -s -S -XPOST -i "https://api.cimediacloud.com/oauth2/token" -H "Authorization: Basic $credString" -H "Content-Type: application/x-www-form-urlencoded" -d "grant_type=password&client_id=$client_id&client_secret=$client_secret";
}


function renewAuth
{
	curl -s -S -XPOST -i "https://api.cimediacloud.com/oauth2/token" \
    -H "Content-Type: application/x-www-form-urlencoded" \
    -d "grant_type=refresh_token&refresh_token=$refresh_token&client_id=$client_id&client_secret=$client_secret"
}

function getKeyedValue
{
#	 arg1 is bigJSON string, arg2 is keyName string
	foo=`echo "$1" | sed -e 's#^.*{##1' -e 's#}.*$##1' -e 's#{.*}##g'  -e "s#\"$2\"*:#&\
#1" | grep -A1 "\"$2\"*:" | tail -1 | sed -e "s#\"$2\"*:##1" | cut -f1 -d,`;

	fooLength=`echo -en "$foo" | wc -c | awk '{print $1}'`;
	fooFirst=`echo -en "$foo" | cut -c1`;
	fooLast=`echo -en "$foo" | cut -c"$fooLength"`;
	if [ "$fooFirst" == '"' -a "$fooLast" == '"' -a $(echo -en "$foo" | tr -d '"' | wc -c | awk '{print $1}') -eq $(expr "$fooLength" - 2) ];
	then
		echo "$foo" | tr -d '"' ;
	else
		echo "$foo" ;
	fi;
}


function getResponseCode
{
	echo "$1" | head -1 | awk '{print $2}'
}

function new_access_token
{
	echo -en > "$access_token_filepath";
	authString=`initAuth`;
	authResponseCode=`getResponseCode "$authString"`;

#echo `getKeyedValue '{"error":"invalid_client","error_description":"Invalid client id and client secret combination."}' 'error_description'`;

	if [ "$authResponseCode" -ne 200 ];
	then 
		errString=`echo "$authString" | tail -1`;
		echo '<?xml version="1.0" encoding="UTF-8" ?>';
		echo '<error>'`echo $(getKeyedValue "$errString" 'error_description') | tr '[[:punct:]]' ' '`'</error>';
		exit 1;
	fi;
	access_token=`getKeyedValue "$authString" 'access_token'`;
	refresh_token=`getKeyedValue "$authString" 'refresh_token'`;
	echo "$access_token" > "$access_token_filepath"; # store it for persistent re-use
}

function get_media_data
{
	media_getString=`curl -s -S -XGET -i "https://api.cimediacloud.com/assets/$media_item_id/download" \
    -H "Authorization: Bearer $access_token"`
	media_getResponseCode=`getResponseCode "$media_getString"`;
}


if [ -z "$access_token" ];
then 
	new_access_token;
fi;


# NOW GO GET THAT MEDIA ITEM
get_media_data;

if [ "$media_getResponseCode" -ne 200 ];
then
	new_access_token;
	get_media_data;
fi;

if [ "$media_getResponseCode" -ne 200 ];
then 
	errString=`echo "$authString" | tail -1`;
	echo '<?xml version="1.0" encoding="UTF-8" ?>';
	echo '<error>'`echo $(getKeyedValue "$errString" 'error_description') | tr '[[:punct:]]' ' '`'</error>';
	exit 1;
fi;

media_URL=`getKeyedValue "$media_getString" 'location' | sed -e 's#&#&amp;#g'`;



#echo "media url is $media_URL";
#media url is https://ci-buckets-assets-1umcaf2mqwhhg.s3.amazonaws.com/cifiles/4bcdbbae70f14a5c822be7380e2ca26a/cpb-aacip-15-000000002m__barcode48704_.h264.mp4?AWSAccessKeyId=AKIAIIRADF3LJIY2O5IA&Expires=1428158705&response-content-disposition=attachment%3B%20filename%3Dcpb-aacip-15-000000002m__barcode48704_.h264.mp4&response-content-type=application%2Foctet-stream&Signature=zsJeRWOvrgUG1b2XhXrtIiZF5vU%3D&u=ae06218b8980440e9eb7c737e90dcf6b&a=4bcdbbae70f14a5c822be7380e2ca26a&ct=42cff4f6dbd74474808dee3c9ab49092

media_format=`echo "$media_URL" | tr '?' '\n' | head -1 | tr '.' '\n' | tail -1`;

#echo "media_format is $media_format"
#media_format is mp4

echo '<?xml version="1.0" encoding="UTF-8" ?>';
echo "<data>";
echo "   <format>$media_format</format>";
echo "   <mediaurl>$media_URL</mediaurl>";
echo "</data>"; 




# echo "$authString";
# 
# HTTP/1.1 200 OK
# 
# Cache-Control: no-cache
# 
# Content-Type: application/json; charset=utf-8
# 
# Date: Fri, 03 Apr 2015 19:29:22 GMT
# 
# Expires: -1
# 
# Pragma: no-cache
# 
# X-Frame-Options: deny
# 
# Content-Length: 143
# 
# Connection: keep-alive
# 
# 
# 
# {"access_token":"1d802e8f2146481983c07f9959b8f101","expires_in":86400,"token_type":"bearer","refresh_token":"59a2ffc7ff19403ba170bdbb1ec7c928"}

#echo "$authString";
#echo "$authResponseCode";










