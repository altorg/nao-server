#!/bin/sh
set -e
#set -o pipefail
search_dir=/opt/nethack/nethack.alt.org/dgldir/userdata/

cd $search_dir

for userdir in */*
do
  if [ -d "$userdir" ];then
    # echo "userdir: $userdir"
    username=`echo "$userdir" | cut -c3-`
    # echo "username: $username"
    for dumplogfile in "$userdir"/dumplog/*.txt
    do
      [ -e "$dumplogfile" ] && : || break
      filename=`echo "$dumplogfile" | cut -d/ -f4`
      s3filename=s3://altorg/dumplog/"$username"/"$filename"
      # echo "MOVING $dumplogfile TO $s3filename"
      aws --region us-east-2 s3 mv "$dumplogfile" "$s3filename"
    done
  fi
done
