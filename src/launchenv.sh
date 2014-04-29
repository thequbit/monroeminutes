#!/bin/bash

# shutdown the system
python mm_shutdown.py
./killscrapers.sh

# remove previous log files
rm *.log

# launch the archiver
python mm_archiver.py &

# launch the docprocessor
#python mm_docprocessor.py 2>&1 >  docprocessor.log &

# launch the doc converter
#python mm_docconverter 2>%1 > docconverter.log &

# launch 4 converters
./launchconverters.sh

# launch 4 processors
/launchprocessors

# launch 32 scrapers
./launchscrapers.sh

# launch the dispatcher
python mm_dispatcher.py &

# list running processes
ps aux | grep python
