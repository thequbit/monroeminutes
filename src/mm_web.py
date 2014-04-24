from flask import Flask
from flask import render_template
from flask import request

import json
import datetime

from access import Access

app = Flask(__name__, static_folder='web/static', static_url_path='')
app.template_folder = "web"
app.debug = True

ADMIN = False

@app.route('/')
def index():
    return render_template('index.html')

@app.route('/entities')
def entities():
    return render_template('entities.html')

@app.route('/status')
def status():
    return render_template('status.html')

@app.route('/developers')
def developers():
    return render_template('developers.html')

@app.route('/about')
def about():
    return render_template('about.html')

@app.route('/entities.json')
def getentities():
    access = Access()
    entities = access.getentities()
    for i in range(0,len(entities)):
        entities[i]['_id'] = str(entities[i]['_id'])
    return json.dumps(entities)

@app.route('/orgs.json')
def getorgs():
    access = Access()
    orgs = access.getorgs()
    for i in range(0,len(orgs)):
        orgs[i]['_id'] = str(orgs[i]['_id'])
    return json.dumps(orgs)

@app.route('/urls.json')
def geturls():
    access = Access()
    urls = access.geturls()
    return json.dumps(urls)

@app.route('/runs.json')
def getruns():
    access = Access()
    runs = access.getruns()
    return json.dumps(runs)

@app.route('/doc.json', methods=['GET'])
def getdoc():
    try:
        docurl = request.args['docurl']
        access = Access()
        doc = access.getdoc(docurl)
        if doc == None:
            doc = {}
    except:
        doc = {}
    return json.dumps(doc)

@app.route('/docs.json')
def getdocs():

    """
    doc = {
        "scrapedatetime": "2014-02-22 03:25:49",
        "docfilename": "/home/administrator/dev/monroeminutes/downloads//4028_1393057549.41.download",
        "being_processed": false,
        "being_converted": true,
        "pdfhash": "",
        "docurl": "http://www.townofbrighton.org/DocumentCenter/View/4028",
        "linktext": "View here",
        "converted": false,
        "pdftext": "",
        "_id": "53085f0ea70f9e0e63aeb15a",
        "urldata": {
            "maxlinklevel": 4,
            "status": "running",
            "runs": [],
            "description": "Brighton, NY",
            "title": "Town of Brighton",
            "scraperid": "2aeaa63f-bef9-4362-a7b3-e8c6c6a92913",
            "doctype": "application/pdf",
            "frequency": 604800,
            "startdatetime": "2014-02-22 03:25:31",
            "targeturl": "http://www.townofbrighton.org/",
            "finishdatetime": "",
            "creationdatetime": "2014-02-22 03:25:09"
        },
        "processed": false
    }
    """

    try:
        access = Access()

        try:
            entityid = request.args['entityid']
        except:
            entityid = ''

        if entityid != '':
            docs = access.getdocsbyentityid(entityid)
            for i in range(0,len(docs)):
                docs[i]['_id'] = str(docs[i]['_id'])
                docs[i]['created'] = str(docs[i]['created'])
        else:
            docs = []
    except:
        docs = []

    return json.dumps(docs)

@app.route('/search.json')
def search():

    """
    return {
        "count": 0, 
        "phrase": "test", 
        "results": [
            {},
        ], 
        "success": true, 
        "error": ""
    }
    """

    error = 'None'
    success = True
    results = {}
    if True:

        # grab the phrase that we are looking for
        try:
            phrase = request.args['phrase']
        except:
            phrase = ''

        # try and get the entity id.  We need at least this to do a search
        try:
            entityid = request.args['entityid']
        except:
            entityid = ''

        # try and get the org id.  We don't need this to do the search.
        try:
            orgid = request.args['orgid']
        except:
            orgid = ''

        # get page number
        try:
            page = request.args['page']
        except:
            page = 0

        if phrase != '' and entityid != '':
            access = Access()

            results = access.search(
                phrase=phrase,
                orgid=orgid,
                entityid=entityid,
                pagesize=25,
                page=page
            )

            #for i in range(0,len(results)):
            #    try:
            #        results[i]['_id'] = str(results[i]['_id'])
            #    except:
            #        # result didn't include this field ...
            #        pass

        else:
            results['error'] = 'Invalid Input'
            results['success'] = False

    #except:
    #    results['error'] = 'General Error'
    #    results['success'] = False

    return json.dumps(results)

@app.route('/statuses.json')
def statuses():
    try:
        access = Access()
        statuses = access.getstatuses()
        for i in range(0,len(statuses)):
            statuses[i]['_id'] = str(statuses[i]['_id'])
    except:
        statuses = []
    return json.dumps(statuses)


#
# These functions are administrative functiosn
#

@app.route('/login')
def login():
    if not ADMIN:
        return ''
    return render_template('login.html')

@app.route('/logout')
def logout():
    if not ADMIN:
        return ''
    return render_template('logout.html')

@app.route('/admin')
def admin():
    if not ADMIN:
        return ''
    return render_template('admin.html')

@app.route('/displaydocs')
def displaydocs():
    if not ADMIN:
        return ''
    return render_template('displaydocs.html')

@app.route('/addorg.json')
def addorg():

    if not ADMIN:
        return ''

    """
    org = {
        'name': 'Brighton Town Board',
        'description': 'Brighton, NY Town Board',
        'matchs': [
            'town board',
            'brighton',
        ]
        'entityid': ObjectID(' ... '),
        'creationdatetime': '2014-03-14 22:01:30',
    }
    """

    success = True
    try:

        try:
            org = json.loads(request.args['org'])
        except:
            org = None

        if org != None:
            access = Access()
            access.addorg(org)
        else:
            success = False

    except:
        success = False

    return json.dumps({'success':success})

@app.route('/addentity.json')
def addentity():

    if not ADMIN:
        return ''

    """
    entity = {
        'name':'Brighton, NY',
        'description':'Town of Brighton, NY',
        'website':'http://www.townofbrighton.org/',
        'creationdatetime':'2014-02-23 21:24:26',
    }

    """

    success = True
    try:
        
        try:
            entity = json.loads(request.args['entity'])
        except:
            entity = None

        if entity != None:
            access = Access()
            access.addentity(entity)
        else:
            success = False

    except:
        success = False

    return json.dumps({'success':success})

#@app.route('/')
#def s

if __name__ == "__main__":
    print "Monroe Minutes Web Application Starting ..."

    host = '0.0.0.0'
    port = 8080

    app.run(host=host, port=port)
