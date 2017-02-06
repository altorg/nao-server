#!/bin/bash
# nethack/dgl build
apt-get install autoconf autogen bison gcc
# webserver
apt-get install apache2 mysql-server
# 32 bit compat for old saves
apt-get install lib32ncurses6 libc6-i386
