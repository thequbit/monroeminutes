from flask import Flask
from flask import render_template
from flask import request

import elasticsearch

import json

from db.models import *

from searchapi import Search

app = Flask(__name__)
app.template_folder = "web"
#app.static_folder = "web/static"
app.debug = True

searchapi = Search()

#es = elasticsearch.Elasticsearch()

#
# TODO: make this pull from a static location
#
@app.route('/main.css')
def jquery():
    return render_template('main.css')

@app.route('/')
def index():
    return render_template('index.html')

@app.route('/organizations')
def organizations():
    return render_template('organizations.html')

@app.route('/developers')
def developers():
    return render_template('developers.html')

@app.route('/about')
def about():
    return render_template('about.html')

@app.route('/getorgs')
def getorgs():
    bodies = Bodies()
    orgs = Orgs()

    allorgs = orgs.getall()
    allbodies = bodies.getall()

    retval = {}
    retval['bodies'] = []
    for body in allbodies:
        bodyid,bodyname,bodydescription,bodycreationdatetime = body
        b = {}
        b['bodyid'] = bodyid
        b['name'] = bodyname
        b['description'] = bodydescription
        b['creationdatetime'] = str(bodycreationdatetime)
        b['orgs'] = []
        for org in allorgs:
            orgid,orgname,orgdescription,orgcreationdatetime,orgmatchtext,orgurlid,orgbodyid = org
            if orgbodyid == bodyid:
                b['orgs'].append({'orgid': orgid,
                                  'name': orgname,
                                  'description': orgdescription,
                                  'creationdatetime': str(orgcreationdatetime),
                                  'matchtext': orgmatchtext,
                                  'urlid': orgurlid,
                                  'bodyid': orgbodyid})
        retval['bodies'].append(b)
    return json.dumps(retval)

@app.route('addorg')
def addorg():
    success = True
    error = ""
    try:
        name = request.args['name']
        bodyid = request.args['bodyid']
        matchtext = request.args['matchtext']
        orgid = request.args['orgid']
        urlid = request.args['urlid']
        #creationdatetime = str(datetime.datetime.now())
        descriptoin = request.args['description']
    except:
        success = False
        error = "An error occurred while parsing arguments.  Did you include name, bodyid, matchtext, orgi, urlid, and description in the url?"

    retval = {}
    retval['success'] = success
    retval['error'] = error

    return json.dumps(retval)

@app.route('/search.json', methods=['GET'])
def search():
    
    # decode the phrase being searched for
    try:
        phrase = request.args['phrase']
    except:
        phrase = ""

    # which organization are we searching within
    try:
        orgid = request.args['orgid']
    except:
        orgid = 0

    # which body are we searching within
    try:
        bodyid = request.args['bodyid']
    except:
        bodyid = 0

    # which page of the search results to display
    try:
        page = int(request.args['page'])
    except:
        page = 0

    # create our response
    response = {}
    response['success'] = False
    response['count'] = 0
    response['results'] = []
    
    # Make sure we are actually searching for something, and if so then
    # perform the search
    if not phrase == "":
        response = searchapi.search(phrase=phrase,
                                    orgid=orgid,
                                    bodyid=bodyid,
                                    page=page)

    # respond with the response serilized object
    return json.dumps(response)

if __name__ == "__main__":
    print "Monroe Minutes Web Application Starting ..."
    
    host = '0.0.0.0'
    port = 8080
    
    app.run(host=host, port=port)
