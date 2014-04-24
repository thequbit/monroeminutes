#!/bin/bash

# shutdown the system
python mm_shutdown.py
./killscrapers.sh

# remove previous log files
rm *.log

# launch the archiver
python mm_archiver.py 2>&1 > archiver.log &

# launch the docprocessor
#python mm_docprocessor.py 2>&1 >  docprocessor.log &

# launch the doc converter
#python mm_docconverter 2>%1 > docconverter.log &

# launch 32 scrapers
./launchscrapers.sh

# launch the dispatcher
python mm_dispatcher.py 2>&1 > dispatcher.log &

# list running processes
ps aux | grep python
