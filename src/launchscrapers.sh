#!/bin/bash

# launch 32 scrapers
for i in {1..31}
do

    # launch the scraper
    python mm_scraper.py &

done
