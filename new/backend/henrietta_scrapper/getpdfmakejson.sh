#!/bin/sh

baseurl=$(mysql --skip-column-names -h 192.168.3.111 -u mmuser --password=password123%%% monroeminutes -e "select documentsurl from suborganization where suborganizationid=$1" -B)

echo $baseurl

# use wget to pull down all of the agenda files off the page
wget -r -l1 -nc -erobots=off -A*-*genda-*html $baseurl

#m $pagename

# rename to .pdf
for file in $(find -name *.html); do mv $file $file.pdf; done

# convert all to text from pdf
for file in $(find -name *.pdf); do pdf2txt.py -o $file.txt $file; done

#create tokens and spit out json of freq distrobution of key words by file
python createtokens.py
