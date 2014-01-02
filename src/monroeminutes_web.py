from flask import Flask
from flask import render_template
from flask import request

import elasticsearch

import json

import datetime

from db.models import *

from searchapi import Search

##
##

ADMIN = True

##
##

#app = Flask(__name__)
app = Flask(__name__, static_folder='web/static', static_url_path='')
app.template_folder = "web"
#app.static_folder = "web/static"
app.debug = True

searchapi = Search()

#es = elasticsearch.Elasticsearch()

#
# TODO: make this pull from a static location
#
#@app.route('/main.css')
#def jquery():
#    return render_template('main.css')
#
#@app.route('/white_wall.png')
#def background():
#    return render_template('white_wall.png')
#

@app.route('/admin')
def admin():
    if ADMIN:
        return render_template('admin.html')
    else:
        return 'Admin access currently disabled.'

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

@app.route('/geturls')
def geturls():
    urls = Urls()

    allurls = urls.getall()

    retval = {}
    retval['urls'] = []
    for url in allurls:
        urlid,targeturl,title,description,maxlinklevel,creationdatetime,doctype,frequency,bodyid = url
        
        u = {}
        u['urlid'] = urlid
        u['targeturl'] = targeturl
        u['title'] = title
        u['description'] = description
        u['maxlinklevel'] = maxlinklevel
        u['creationdatetime'] = str(creationdatetime)
        u['doctype'] = doctype
        u['frequency'] = frequency
        u['bodyid'] = bodyid
        retval['urls'].append(u)

    return json.dumps(retval)

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

@app.route('/addorg')
def addorg():
    if not ADMIN:
        return 'Admin access currently disabled.'

    success = True
    error = ""
    #try:
    if True:
        name = request.args['name']
        description = request.args['description']
        creationdatetime = str(datetime.datetime.now())
        matchtext = request.args['matchtext']
        urlid = request.args['urlid']
        bodyid = request.args['bodyid']

        orgs = Orgs()
        orgid = orgs.add(name,description,creationdatetime,matchtext,urlid,bodyid)

    #except:
    #    success = False
    #    error = "An error occurred while parsing arguments.  Did you include name, bodyid, matchtext, orgi, urlid, and description in the url?"
    #    orgid = -1

    retval = {}
    retval['success'] = success
    retval['error'] = error
    retval['orgid'] = orgid

    return json.dumps(retval)

@app.route('/delurl')
def delurl():
    if not ADMIN:
        return 'Admin access currently disabled.'

    success = True
    error = ""
    #try
    if True:
        urlid = request.args['urlid']
        
        urls = Urls()
        urls.delete(urlid)
    #except:
    #    success = False
    # error = "An error occured while parsing arguments.  Did you include urlid in the url?"
    
    retval = {}
    retval['success'] = success
    retval['error'] = error

    return json.dumps(retval)

@app.route('/addurl')
def addurl():
    if not ADMIN:
        return 'Admin access currently disabled.'

    success = True
    error = ""
    try:
        targeturl = request.args['targeturl']
        title = request.args['title']
        description = request.args['description']
        maxlinklevel = request.args['maxlinklevel']
        doctype = request.args['doctype']
        frequency = request.args['frequency']
        bodyid = request.args['bodyid']
        creationdatetime = str(datetime.datetime.now())

        urls = Urls()
        urlid = urls.add(targeturl,title,description,maxlinklevel,creationdatetime,doctype,frequency,bodyid)

    except:
        success = False
        error = "An error occurred while parsing arguments.  Did you include targeturl, title, description, maxlinklevel, doctype, frequency, and bodyid in the url?"
        urlid = -1

    retval = {}
    retval['success'] = success
    retval['error'] = error
    retval['urlid'] = urlid

    return json.dumps(retval)

@app.route('/addbody')
def addbody():
    if not ADMIN:
        return 'Admin access currently disabled.'

    success = True
    error = ""
    try:
        name = request.args['name']
        description = request.args['description']
        creationdatetime = str(datetime.datetime.now())

        bodies = Bodies()
        bodyid = bodies.add(name,description,creationdatetime)
    except:
        success = False
        error = "An error occurred while parsing arguments.  Did you include name and description in the url?"
        bodyid = -1

    retval = {}
    retval['success'] = success
    retval['error'] = error
    retval['bodyid'] = bodyid

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
