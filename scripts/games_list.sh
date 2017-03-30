#!/bin/bash

if [ ! -z "$1" ]; then
    DIR="$( cd "$( dirname "$0" )" && pwd )";
    $DIR/plr_files.sh "$1"
    exit;
fi

cd /opt/nethack/nethack.alt.org/nh343/var
ls -1 *.0 | sed -e 's/^5//g' -e 's/.0$//g'

