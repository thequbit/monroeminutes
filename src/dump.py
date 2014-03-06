import json

from access import Access

if __name__ == '__main__':

    print "Downloading MonroeMinutes data ..."

    a = Access()

    runs = a.getruns()
    docs = a.getdocs()
    entities = a.getentities()

    runsdata = []
    for run in runs:
        run['_id'] = str(run['_id'])
        runsdata.append(run)

    docsdata = []
    for doc in docs:
        doc['_id'] = str(doc['_id'])
        docsdata.append(doc)

    entitiesdata = []
    for entity in entities:
        entity['_id'] = str(entity['_id'])
        entitiesdata.append(entity)

    with open('runs.json','w') as f:
        f.write(json.dumps(runsdata))

    with open('docs.json','w') as f:
        f.write(json.dumps(docsdata))

    with open('entities.json','w') as f:
        f.write(json.dumps(entitiesdata))


    print "Done."

