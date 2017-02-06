#!/bin/bash
# nethack/dgl build
apt-get install autoconf autogen bison gcc libncurses5-dev libncursesw5-dev make
# webserver
apt-get install apache2 mysql-server
# 32 bit compat for old saves
apt-get install lib32ncurses5 libc6-i386
