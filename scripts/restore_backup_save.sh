#!/bin/bash

if [ -z "$1" ]; then
   echo "Need user name.";
   exit;
fi

PLR=$1

cd /opt/nethack/nethack.alt.org/

cd nh343/var/save/

SAVEFNAME="5$1.gz"
if [ -e "$SAVEFNAME" ]; then
    echo "$PLR already has a save file.";
    exit;
fi

FNAME="5$1.gz.bak"
if [ ! -e "backups/$FNAME" ]; then
    echo "$PLR has not backup save.";
    exit;
fi

cp "backups/$FNAME" "$SAVEFNAME"
chown games.games "$SAVEFNAME"

echo "Restored $PLR backup save."