#!/bin/sh

mongodump --host localhost:27017 -d monroeminutesdb

tar -zcvf dump.tar.gz dump
