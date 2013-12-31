import elasticsearch
import json
import re

class Search():

    def __init__(self):

        self.es = elasticsearch.Elasticsearch()

    def search(self,phrase,orgid=0,bodyid=0,page=0):

        # create our response
        response = {}
        response['success'] = False
        response['error'] = ""
        response['count'] = 0
        response['phrase'] = ""
        response['results'] = []

        if orgid !=0 and bodyid != 0:
            response['error'] = "orgid and bodyid both specified, only one can be used at a time."
            return response

        # build the search query body
        body = self.buildbody(phrase,page,orgid,bodyid)

        # perform the search
        results = self.es.search(index="monroeminutes",
                                 body=body
        )

        # create our return object to send back
        response['success'] = True
        response['count'] = len(results['hits']['hits'])
        response['phrase'] = phrase
        response['results'] = []
        for hit in results['hits']['hits']:
            
            # build preview text
            pdftext = hit['_source']['pdftext']
            previewtext = self.buildpreviewtext(phrase,pdftext)
            
            # build result response
            response['results'].append({
                'score': hit['_score'],
                'docid': hit['_id'],
                'docurl': hit['_source']['docurl'],
                'docname': hit['_source']['docname'],
                'scrapedatetime': hit['_source']['scrapedatetime'],
                'linktext': hit['_source']['linktext'],
                'targeturl': hit['_source']['targeturl'],
                'previewtext': previewtext,
                #'pdftext': pdftext
            })

        return response

    def buildpreviewtext(self,phrase,pdftext):

        BEFORE_LEN = 64
        AFTER_LEN = 64

        regexstr = "( +)?"
        for i in range(0,len(phrase)):
            if phrase[i] != ' ':
                regexstr += "%s( +)?" % phrase[i]

        count = 0
        indexes = [(m.start(0)) for m in re.finditer(regexstr.lower(), pdftext.lower())]

        #print "Found %i incidents of phrase" % len(indexes)

        if len(indexes) == 0:
            return ""

        #print "pdftext length: {0}".format(len(pdftext))

        text = ""
        for index in indexes:
            #print "index = %i" % index
            if index < BEFORE_LEN:
                beforeindex = 0
            else:
                beforeindex = index - BEFORE_LEN
            #print "before index: {0}, len: {1}".format(beforeindex,AFTER_LEN)
            preview = pdftext[beforeindex:(beforeindex+BEFORE_LEN+AFTER_LEN)]
            preview = " ".join(preview.split(' ')[1:-1])
            preview = preview.replace('\t','').replace('\n','').replace('\f','')
            #print "preview text: {0}".format(preview)

            text += "... {0}".format(preview)

        text += " ..."
        return text

    def buildbody(self,phrase,page,orgid,bodyid):
        
        if orgid == 0 and bodyid == 0:
            #create the search query
            body = {"size": 10,
                    "from": page*10,
                    "query": {
                        "match": {
                            "pdftext": phrase
                   }}}
        else:
            # create the query
            body = {
                'size': 10,
                'from': page*10,
                'query': {
                    'filtered': {
                        'query': {
                            'query_string': {
                                'query': phrase,
                                'use_dis_max': True,
                                'default_operator':'AND',
                                'fields': [
                                    'pdftext',
                                ]
                            }
                        },
                        'filter': {
                            'query': {
                                'field': {
                                    #'orgid': orgid,
                                }
                            }
                        }
                    }
                }
            }

            # add the 'where clauses'
            if orgid != 0:
                body['query']['filtered']['filter']['query']['field']['orgid'] = orgid
            if bodyid != 0:
                body['query']['filtered']['filter']['query']['field']['bodyid'] = bodyid

        return body 

if __name__ == '__main__':

    search = Search()

    phrase = "hello"
    pdftext = "I don't know how many times I need to tell her that I prefere hello over a simple hi."

    response = search.buildpreviewtext(phrase,pdftext)

    #response = search.search('today',orgid=1,bodyid=0)

    print response
