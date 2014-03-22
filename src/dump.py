import json

from access import Access

if __name__ == '__main__':

    print "Downloading MonroeMinutes data ..."

    a = Access()

    runs = a.getruns()
    docs = a.getdocs()
    entities = a.getentities()
    orgs = a.getorgs()

    runsdata = []
    for run in runs:
        run['_id'] = str(run['_id'])
        runsdata.append(run)

    docsdata = []
    for doc in docs:
        doc['_id'] = str(doc['_id'])
        doc['created'] = str(doc['created'])
        docsdata.append(doc)

    entitiesdata = []
    for entity in entities:
        entity['_id'] = str(entity['_id'])
        entitiesdata.append(entity)

    orgsdata = []
    for org in orgs:
        org['_id'] = str(org['_id'])
        orgsdata.append(org)

    with open('backup/runs.json','w') as f:
        f.write(json.dumps(runsdata))

    with open('backup/docs.json','w') as f:
        f.write(json.dumps(docsdata))

    with open('backup/entities.json','w') as f:
        f.write(json.dumps(entitiesdata))

    with open('backup/orgs.json','w') as f:
       f.write(json.dumps(orgsdata))


    print "Done."

