#!/bin/bash

if [ "x$1" == "x" ]; then
        echo "User name required."
        exit
fi


DIR="/opt/nethack/nethack.alt.org/nh360/var"
XUID="5"

if [ ! -f "$DIR/$1" ]; then
        if [ ! -f "$DIR/$XUID$1.0" ]; then
		echo "Cannot find $DIR/$XUID$1.0"
                echo "$1 does not have anything to recover."
                exit
        else
                FNAME="$XUID$1"
        fi
else
        FNAME="$1"
fi

#echo $DIR
#echo $XUID
#echo $FNAME
#exit

cd /opt/nethack/nethack.alt.org && \
sudo ./nh360/recover -d "$DIR" "$FNAME" && \
sudo chown games.games "nh360/var/save/$FNAME" && \
sudo chmod ug+rw "nh360/var/save/$FNAME" && \
sudo gzip "nh360/var/save/$FNAME"


#sudo ../nh343/recover -d /opt/nethack/nethack.alt.org/nh343/var 5$1
