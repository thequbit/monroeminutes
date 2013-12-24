#!/bin/sh

# get current datetime
now=`date +"%m_%d_%Y"`

curl -o backup_$now.json -XPOST http://localhost:9200/monroeminutes/_search?pretty=true&q=*:*
