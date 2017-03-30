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
    for ttyrecfile in "$userdir"/ttyrec/*.ttyrec.bz2
    do
      [ -e "$ttyrecfile" ] && : || break
      filename=`echo "$ttyrecfile" | cut -d/ -f4`
      s3filename=s3://altorg/ttyrec/"$username"/"$filename"
      # echo "MOVING $ttyrecfile TO $s3filename"
      aws --region us-east-2 s3 mv "$ttyrecfile" "$s3filename"
    done
  fi
done
