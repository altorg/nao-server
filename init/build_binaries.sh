#!/bin/bash
cd /home/build/dgamelaunch
./autogen.sh --with-config-file=/opt/nethack/dgamelaunch.conf --enable-shmem --enable-sqlite
make
cd dat
make
cd ..
make dlb
