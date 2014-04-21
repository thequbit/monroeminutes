#!/bin/bash

# launch the archiver
python mm_archiver.py &

# launch the docprocessor
python mm_docprocessor &

# launch the doc converter
#python mm_docconverter &

# launch the scraper
python mm_scraper.py &

# launch the dispatcher
python mm_dispatcher &

