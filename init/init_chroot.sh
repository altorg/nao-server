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
NHSUBDIR="/nh343/"
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

echo "Copying $DGL_CONFIG"
cd etc
cp "$DGL_CONFIG" .
echo "*** Edit $DGL_CONFIG to suit your needs."
[ -f /etc/localtime ] && cp /etc/localtime .
cd ..

echo "Copying text editors 'ee' and 'virus'"
cd bin
cp "$NETHACK_GIT/src/ee" .
cp "$NETHACK_GIT/src/virus" .
cd ..

echo "Copying DGL examples"
cp "$DGL_GIT/examples/dgl_menu_main_anon.txt" .
cp "$DGL_GIT/examples/dgl_menu_main_user.txt" .
cp "$DGL_GIT/examples/dgl_menu_watchmenu_help.txt" .
cp "$DGL_GIT/examples/dgl-banner" .
cp "$DGL_GIT/dgl-default-rcfile" "dgl-default-rcfile.nh343"
chmod go+r dgl_menu_main_anon.txt dgl_menu_main_user.txt dgl-banner dgl-default-rcfile.nh343

NHSUBDIR="`echo ${NHSUBDIR%/}`"
NHSUBDIR="`echo ${NHSUBDIR#/}`"

mkdir "$CHROOT/$NHSUBDIR"

if [ -n "$NETHACKBIN" -a ! -e "$NETHACKBIN" ]; then
  errorexit "Cannot find NetHack binary $NETHACKBIN"
fi

if [ -n "$NETHACKBIN" -a -e "$NETHACKBIN" ]; then
  echo "Copying $NETHACKBIN"
  cd "$NHSUBDIR"
  NHBINFILE="`basename $NETHACKBIN`.`date +%Y%m%d`"
  cp "$NETHACKBIN" "$NHBINFILE"
  ln -s "$NHBINFILE" nethack
  LIBS="$LIBS `findlibs $NETHACKBIN`"
  cd "$CHROOT"
fi


NH_PLAYGROUND_FIXED="`echo ${NH_PLAYGROUND_FIXED%/}`"

if [ -n "$NH_PLAYGROUND_FIXED" -a -d "$NH_PLAYGROUND_FIXED" ]; then
  echo "Copying NetHack playground stuff."
  NHFILES="*.lev *.dat cmdhelp data dungeon help hh history license opthelp options oracles recover rumors wizhelp"
  for fil in $NHFILES; do
    cp $NH_PLAYGROUND_FIXED/$fil "$CHROOT/$NHSUBDIR/"
  done
fi


NH_VAR_PLAYGROUND="`echo ${NH_VAR_PLAYGROUND%/}`"
NH_VAR_PLAYGROUND="`echo ${NH_VAR_PLAYGROUND#/}`"

echo "Creating NetHack variable dir stuff."
if [ -n "$NH_VAR_PLAYGROUND" ]; then
  mkdir -p "$CHROOT/$NH_VAR_PLAYGROUND"
  chown -R "$USRGRP" "$CHROOT/$NH_VAR_PLAYGROUND"
fi
mkdir -p "$CHROOT/$NH_VAR_PLAYGROUND/save"
chown -R "$USRGRP" "$CHROOT/$NH_VAR_PLAYGROUND/save"
touch "$CHROOT/$NH_VAR_PLAYGROUND/logfile"
touch "$CHROOT/$NH_VAR_PLAYGROUND/perm"
touch "$CHROOT/$NH_VAR_PLAYGROUND/record"
touch "$CHROOT/$NH_VAR_PLAYGROUND/xlogfile"

chown -R "$USRGRP" "$CHROOT/$NHSUBDIR"
chown -R "$USRGRP" "$CHROOT/$NH_VAR_PLAYGROUND"



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


