import elasticsearch
import json
import sys

def main(argv):

    if len(argv) !=2:
        print "Usage:\n\tpython estest.py <search_phrase>"
        return

    phrase = argv[1]

    es = elasticsearch.Elasticsearch()

    results = es.search(index="monroeminutes", body={"query": {"match": {"pdftext": phrase}}})

    print "{0} Results Found.".format(len(results['hits']['hits']))
    for hit in results['hits']['hits']:
        #print hit
        url = hit['_source']['docurl']
        print "URL: {0}".format(url)

    #f = open("output.json","w")
    #f.write(json.dumps(results))
    #f.flush()
    #f.close()

if __name__ == '__main__': sys.exit(main(sys.argv))
