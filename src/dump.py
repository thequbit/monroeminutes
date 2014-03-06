import json

from access import Access

if __name__ == '__main__':

    print "Downloading MonroeMinutes data ..."

    a = Access()

    runs = a._getruns()
    docs = a._getall()

    runsdata = []
    for run in runs:
        run['_id'] = str(run['_id'])
        runsdata.append(run)

    docsdata = []
    for doc in docs:
        doc['_id'] = str(doc['_id'])
        docsdata.append(doc)

    with open('runs.json','w') as f:
        f.write(json.dumps(runsdata))

    with open('docs.json','w') as f:
        f.write(json.dumps(docsdata))

    print "Done."

