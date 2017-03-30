#!/bin/bash
# admin_msg.sh message [username]

ADMIN="$2"
MSG="$1"

MSGFILE="/opt/nethack/nethack.alt.org/nh343/admin_msg"

if [ "x$ADMIN" = "x" ]; then
  ADMIN=`whoami`
fi

if [ "x$MSG" == "x" ]; then
    echo "admin_msg.sh message [username]"
    echo "  Sends an in-game message to everyone playing nethack on the server."
else
    echo "$ADMIN:$MSG" > $MSGFILE
    chmod a+r $MSGFILE
fi
