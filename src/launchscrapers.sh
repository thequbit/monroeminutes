#!/bin/bash

for i in {1..32}
do

    # launch the scraper
    python mm_scraper.py &

done
