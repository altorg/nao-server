#!/bin/bash
# nethack/dgl build
apt-get install autoconf autogen bison flex gcc libncurses5-dev libncursesw5-dev libsqlite3-dev make sqlite3
# os running
apt-get install telnetd-ssl xinetd
# webserver
apt-get install apache2 mysql-server
# 32 bit compat for old saves
apt-get install lib32ncurses5 lib32ncurses5-dev libc6-i386 libc6-dev-i386
