from flask import Flask
from flask import render_template
from flask import request

import json
import datetime

from access import Access

app = Flask(__name__, static_folder='web/static', static_url_path='')
app.template_folder = "web"
app.debug = True

@app.route('/')
def index():
    return render_template('index.html')

@app.route('/entities.json')
def entities():
    access = Access()
    entities = access.getentities()
    for i in range(0,len(entities)):
        entities[i]['_id'] = str(entities[i]['_id'])
    return json.dumps(entities)

@app.route('/urls.json')
def urls():
    access = Access()
    urls = access.geturls()
    return json.dumps(urls)

@app.route('/runs.json')
def runs():
    access = Access()
    runs = access.getruns()
    return json.dumps(runs)

@app.route('/doc.json', methods=['GET'])
def doc():
    try:
        docurl = request.args['docurl']
        access = Access()
        doc = access.getdoc(docurl)
        if doc == None:
            doc = {}
    except:
        doc = {}
    return json.dumps(doc)

@app.route('/search.json')
def search():

    error = 'None'
    success = True
    results = {}
    try:

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

    except:
        results['error'] = 'General Error'
        results['success'] = False

    return json.dumps(results)

#
# These functions are administrative functiosn
#

@app.route('/addorg.json')
def addorg():

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
