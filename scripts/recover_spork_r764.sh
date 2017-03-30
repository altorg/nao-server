#!/bin/bash

if [ "x$1" == "x" ]; then
        echo "User name required."
        exit
fi


DIR="/opt/nethack/nethack.alt.org/sporkhack.r764/var"
XUID="5"

if [ ! -f "$DIR/$1" ]; then
        if [ ! -f "$DIR/$XUID$1.0" ]; then
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
sudo ./sporkhack.r764/recover -d "$DIR" "$FNAME" && \
sudo chown games.games "sporkhack.r764/var/save/$FNAME" && \
sudo chmod ug+rw "sporkhack.r764/var/save/$FNAME" && \
sudo gzip "sporkhack.r764/var/save/$FNAME"

