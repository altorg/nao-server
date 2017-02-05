#!/bin/bash
# 
# (c)2017 Michael Andrew Streib
# Licensed under the MIT License
# https://opensource.org/licenses/MIT

DATESTAMP=`date +%Y%m%d-%H%M%S`
NAO_CHROOT=/opt/nethack/nao-chroot-autogen-$DATESTAMP
CHROOT_DIRS=(lib lib32 lib64)
CHROOT_LIBS=(lib/ld-linux.so.2 lib32/ld-linux.so.2 lib32/libc.so.6 lib32/libncurses.so.5 \
lib32/libtinfo.so.5)

# generate chroot
for i in ${CHROOT_DIRS[@]}; do
	echo "Creating $NAO_CHROOT/$i"
	mkdir --parents $NAO_CHROOT/$i
done
# 
for i in ${CHROOT_LIBS[@]}; do
	if [ -f /$i ]
	then
		echo "Copying /$i from base OS"
		cp /$i $NAO_CHROOT/$i
	else
		echo "ERROR: No source file /$i"
		exit 1
	fi	
done
