import elasticsearch
import json
import sys

def main(argv):

    if len(argv) != 4:
        print "Usage:\n\tpython estest.py <search_phrase> <start> <end>"
        return

    phrase = argv[1]
    start = argv[2]
    size = argv[3]
    #targeturl = argv[2]

    if phrase == "*":
        query = {"match_all": {}}
    else:
        query = {"match": {"pdftext": phrase}}

    es = elasticsearch.Elasticsearch()

    results = es.search(index="monroeminutes",body={"from":start,"size":size,"query": query}) 

    print "{0} Results Found.".format(len(results['hits']['hits']))
    for hit in results['hits']['hits']:
        #print hit
        url = hit['_source']['docurl']
        print "URL: {0}".format(url)
        #return

    #f = open("output.json","w")
    #f.write(json.dumps(results))
    #f.flush()
    #f.close()

if __name__ == '__main__': sys.exit(main(sys.argv))
