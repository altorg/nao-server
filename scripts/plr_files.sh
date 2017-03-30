#!/bin/bash

if [ -z "$1" ]; then
   DIR="$( cd "$( dirname "$0" )" && pwd )";
   $DIR/games_list.sh
   exit;
fi

PLR=$1

cd /opt/nethack/nethack.alt.org/


FNAME="nh343/var/5$1.0";
if [ -e "$FNAME" ]; then
  echo -n "- Temporary file: ";
  ls -al "$FNAME";
else
  echo    "- Temporary file: none";
fi

FNAME="nh343/var/save/5$1.gz";
if [ -e "$FNAME" ]; then
  echo -n "- Save file:      ";
  ls -al "$FNAME";
else
  echo    "- Save file:      none";
fi

FNAME="nh343/var/save/backups/5$1.gz.bak";
if [ -e "$FNAME" ]; then
  echo -n "- Backup save:    ";
  ls -al "$FNAME";
else
  echo    "- Backup save:    none";
fi
