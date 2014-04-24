#/bin/sh

pkill -9 -f mm_scraper.py
pkill -9 -f mm_dispatcher.py
pkill -9 -f mm_archiver.py
pkill -9 -f mm_docprocessor.py

ps aux | grep python
