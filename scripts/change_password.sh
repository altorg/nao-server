#!/bin/bash
# change_password username password

PLRNAME="$1"
PASS="$2"
SALT=${PASS:0:2}
#PASSWD=`mkpasswd "$PASS" "$SALT"`
PASSWD="$PASS"

NUMNICKS=`sqlite3 /opt/nethack/nethack.alt.org/dgldir/dgamelaunch.db "select count(*) from dglusers where username ='$PLRNAME'"`

if [ "x$NUMNICKS" == "x1" ]; then
    sqlite3 /opt/nethack/nethack.alt.org/dgldir/dgamelaunch.db "update dglusers set password='$PASSWD' where username = '$PLRNAME'"
else
    echo "Sorry, no such user in the db, or the nick is ambiguous!"
    exit
fi


