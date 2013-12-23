from flask import Flask
from flask import render_template
from flask import request

import elasticsearch

import json

from db.models import *

app = Flask(__name__)
app.template_folder = "web"
#app.static_folder = "web/static"
app.debug = True

es = elasticsearch.Elasticsearch()

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

@app.route('/search.json', methods=['GET'])
def search():
    
    # decode the phrase being searched for
    try:
        phrase = request.args['phrase']
    except:
        phrase = ""

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

        try:
            # perform the search
            results = es.search(index="monroeminutes",
                                body={
                                    "size": 10,
                                    "from": page*10,
                                    "query": {
                                        "match": {
                                            "pdftext": phrase
                               }}})
        
            # create our return object to send back
            response['success'] = True
            response['count'] = len(results['hits']['hits'])
            response['results'] = []
            for hit in results['hits']['hits']:
                response['results'].append({
                    'score': hit['_score'],
                    'docid': hit['_id'],
                    'docurl': hit['_source']['docurl'],
                    'scrapedatetime': hit['_source']['scrapedatetime'],
                    'linktext': hit['_source']['linktext'],
                    'targeturl': hit['_source']['targeturl']
                })
        
        except:
            # if unsuccessfull, then we will return success = False
            pass

    # respond with the response serilized object
    return json.dumps(response)

if __name__ == "__main__":
    print "Monroe Minutes Web Application Starting ..."
    
    host = '0.0.0.0'
    port = 8080
    
    app.run(host=host, port=port)
