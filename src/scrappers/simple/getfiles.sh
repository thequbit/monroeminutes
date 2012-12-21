#!/bin/bash

#put us into our virtual env
workon mmenv

# execute our python script in our virt env
python getpdfs.py

# take us out of our virtual env
deactivate
