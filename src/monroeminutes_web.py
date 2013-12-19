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
def index():
    return render_template('organizations.html')

@app.route('/developers')
def index():
    return render_template('developers.html')

@app.route('/about')
def index():
    return render_template('about.html')



@app.route('/search.json', methods=['GET'])
def search():
    phrase = request.args['phrase']

    results = es.search(index="monroeminutes", 
                        body={
                            "query": {
                                "match": {
                                    "pdftext": phrase
                       }}})
    response = {}
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
    return json.dumps(response)

if __name__ == "__main__":
    print "Monroe Minutes Web Application Starting ..."
    
    host = '0.0.0.0'
    port = 8080
    
    app.run(host=host, port=port)
