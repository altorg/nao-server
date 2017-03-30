#!/bin/sh
#find /opt/nethack/nethack.alt.org/dgldir/userdata -type f -amin +7200 -print0 -name \"*.ttyrec\" | xargs -l1 -0 bzip2 -q
nice find /opt/nethack/nethack.alt.org/dgldir/userdata -type f -amin +7200 -name "*.ttyrec" -print0 | xargs -l1 -0 bzip2 -v
