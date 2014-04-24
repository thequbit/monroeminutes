#!/bin/bash

# launch 32 scrapers
for i in {1..31}
do

    # launch the scraper
    python mm_scraper.py 2>&1 > scraper_$i.log &

done
