#!/bin/bash
# 
# Ideas and some parts from the original dgl-create-chroot (by joshk@triplehelix.org, modifications by jilles@stack.nl)
# More by <paxed@alt.org>
# More by Michael Andrew Streib <dtype@dtype.org>
# Licensed under the MIT License
# https://opensource.org/licenses/MIT

# autonamed chroot directory. Can rename.
DATESTAMP=`date +%Y%m%d-%H%M%S`
NAO_CHROOT=/opt/nethack/nao-chroot-autogen-$DATESTAMP
# config outside of chroot
DGL_CONFIG="/opt/nethack/dgamelaunch.conf"
# already compiled versions of dgl and nethack
DGL_GIT="/home/build/dgamelaunch"
NETHACK_GIT="/home/build/NetHack"
# the user & group from dgamelaunch config file.
USRGRP="games:games"
# COMPRESS from include/config.h; the compression binary to copy. leave blank to skip.
COMPRESSBIN="/bin/gzip"
# fixed data to copy (leave blank to skip)
NH_GIT="/home/build/NetHack"
NH_BRANCH="3.4.3"
# HACKDIR from include/config.h; aka nethack subdir inside chroot
NHSUBDIR="nh343"
# VAR_PLAYGROUND from include/unixconf.h
NH_VAR_PLAYGROUND="/nh343/var/"
# only define this if dgl was configured with --enable-sqlite
SQLITE_DBFILE="/dgldir/dgamelaunch.db"
# END OF CONFIG
##############################################################################

errorexit()
{
    echo "Error: $@" >&2
    exit 1
}

findlibs()
{
  for i in "$@"; do
      if [ -z "`ldd "$i" | grep 'not a dynamic executable'`" ]; then
         echo $(ldd "$i" | awk '{ print $3 }' | egrep -v ^'\(' | grep lib)
         echo $(ldd "$i" | grep 'ld-linux' | awk '{ print $1 }')
      fi
  done
}

set -e

# termdata
if [ -z "$TERMDATA" ]; then
    SEARCHTERMDATA="/etc/terminfo /usr/share/lib/terminfo /usr/share/terminfo /lib/terminfo"
    for dir in $SEARCHTERMDATA; do
	if [ -e "$dir/x/xterm" ]; then
	    TERMDATA="$TERMDATA $dir"
	fi
    done
    if [ -z "$TERMDATA" ]; then
	errorexit "Couldn't find terminfo definitions. Please specify in 'TERMDATA' variable."
    fi
fi

echo "Adding LIBS for $DGL_GIT/dgamelaunch"
LIBS="`findlibs $DGL_GIT/dgamelaunch`"

###################
# generate chroot
umask 022

echo "Creating $NAO_CHROOT"
mkdir --parents "$NAO_CHROOT" || errorexit "Cannot create chroot"
cd "$NAO_CHROOT"

echo "Creating top level directories"
mkdir dgldir etc lib mail usr bin
chown "$USRGRP" dgldir mail

DGLFILE="dgamelaunch.$DATESTAMP"
echo "Copying $DGL_GIT/dgamelaunch to $DGLFILE and symlinking to dgamelaunch"
cp "$DGL_GIT/dgamelaunch" "$DGLFILE"
ln -s "$DGLFILE" dgamelaunch

echo "Creating inprogress and userdata directories"
mkdir -p "$NAO_CHROOT/dgldir/inprogress-nh343"
mkdir -p "$NAO_CHROOT/dgldir/userdata"
chown "$USRGRP" "$NAO_CHROOT/dgldir/inprogress-nh343"
chown "$USRGRP" "$NAO_CHROOT/dgldir/userdata"

echo "Creating SQLite database at $SQLITE_DBFILE"
if [ -n "$SQLITE_DBFILE" ]; then
  if [ "x`which sqlite3`" = "x" ]; then
      errorexit "No sqlite3 found."
  else
      SQLITE_DBFILE="`echo ${SQLITE_DBFILE%/}`"
      SQLITE_DBFILE="`echo ${SQLITE_DBFILE#/}`"
      sqlite3 "$NAO_CHROOT/$SQLITE_DBFILE" "create table dglusers (id integer primary key, username text, email text, env text, password text, flags integer);"
      chown "$USRGRP" "$NAO_CHROOT/$SQLITE_DBFILE"
  fi
fi

if [ -n "$COMPRESSBIN" -a -e "`which $COMPRESSBIN`" ]; then
  COMPRESSDIR="`dirname $COMPRESSBIN`"
  COMPRESSDIR="`echo ${COMPRESSDIR%/}`"
  COMPRESSDIR="`echo ${COMPRESSDIR#/}`"
  echo "Copying $COMPRESSBIN to $COMPRESSDIR"
  mkdir -p "$COMPRESSDIR"
  cp "`which $COMPRESSBIN`" "$COMPRESSDIR/"
  echo "Adding LIBS for $COMPRESSBIN"
  LIBS="$LIBS `findlibs $COMPRESSBIN`"
fi

echo "Creating dev/urandom"
mkdir -p dev
cd dev
mknod urandom c 1 9
cd ..

if [ -f $DGL_CONFIG ]; then
  echo "$DGL_CONFIG exists!"
else
  echo "Copying $DGL_GIT/examples/dgamelaunch.conf"
  cp "$DGL_GIT/examples/dgamelaunch.conf" $DGL_CONFIG
  echo "*** Edit $DGL_CONFIG to suit your needs."
fi

echo "Creating etc/localtime"
[ -f /etc/localtime ] && cp /etc/localtime etc

echo "Copying text editors 'ee' and 'virus'"
cd bin
cp "$DGL_GIT/ee" .
cp "$DGL_GIT/virus" .
cd ..

echo "Copying DGL examples"
cp "$DGL_GIT/examples/dgl_menu_main_anon.txt" .
cp "$DGL_GIT/examples/dgl_menu_main_user.txt" .
cp "$DGL_GIT/examples/dgl_menu_watchmenu_help.txt" .
cp "$DGL_GIT/examples/dgl-banner" .
cp "$DGL_GIT/dgl-default-rcfile" "dgl-default-rcfile.nh343"
chmod go+r dgl_menu_main_anon.txt dgl_menu_main_user.txt dgl-banner dgl-default-rcfile.nh343

echo "Making $NAO_CHROOT/$NHSUBDIR"
mkdir "$NAO_CHROOT/$NHSUBDIR"

NETHACKBIN="$NETHACK_GIT/src/nethack"
if [ -n "$NETHACKBIN" -a ! -e "$NETHACKBIN" ]; then
  errorexit "Cannot find NetHack binary $NETHACKBIN"
fi

if [ -n "$NETHACKBIN" -a -e "$NETHACKBIN" ]; then
  echo "Copying $NETHACKBIN"
  cd "$NHSUBDIR"
  NHBINFILE="`basename $NETHACKBIN`"."-".$DATESTAMP
  cp "$NETHACKBIN" "$NHBINFILE"
  ln -s "$NHBINFILE" nethack
  LIBS="$LIBS `findlibs $NETHACKBIN`"
  cd "$NAO_CHROOT"
fi

echo "Copying NetHack playground stuff"
cp "$NAO_GIT/dat/nhdat" "$NAO_CHROOT/$NHSUBDIR"

echo "Creating NetHack variable dir stuff."
mkdir -p "$NAO_CHROOT/$NHSUBDIR/var"
chown -R "$USRGRP" "$NAO_CHROOT/$NHSUBDIR/var"
mkdir -p "$NAO_CHROOT/$NHSUBDIR/var/save"
chown -R "$USRGRP" "$NAO_CHROOT/$NHSUBDIR/var/save"

touch "$NAO_CHROOT/$NHSUBDIR/var/logfile"
touch "$NAO_CHROOT/$NHSUBDIR/var/perm"
touch "$NAO_CHROOT/$NHSUBDIR/var/record"
touch "$NAO_CHROOT/$NHSUBDIR/var/xlogfile"

# Curses junk
if [ -n "$TERMDATA" ]; then
    echo "Copying termdata files from $TERMDATA"
    for termdat in $TERMDATA; do
	mkdir -p "$CHROOT`dirname $termdat`"
	if [ -d $termdat/. ]; then
		cp -LR $termdat/. $CHROOT$termdat
	else
		cp $termdat $CHROOT`dirname $termdat`
	fi
    done
fi

LIBS=`for lib in $LIBS; do echo $lib; done | sort | uniq`
echo "Copying libraries:" $LIBS
for lib in $LIBS; do
        mkdir -p "$CHROOT`dirname $lib`"
        cp $lib "$CHROOT$lib"
done

echo "Finished."


