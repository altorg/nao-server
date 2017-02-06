#!/bin/bash
# nethack/dgl build
apt-get install gcc autogen
# webserver
apt-get install apache2 mysql-server
# 32 bit compat for old saves
apt-get install libc6-i386 lib32ncurses5
