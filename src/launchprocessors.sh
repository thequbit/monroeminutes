#!/bin/bash

# launch 32 processors
for i in {1..4}
do

    # launch the processor
    python mm_docprocessor.py &

done

