import elasticsearch
import json
import re
import uuid

class Search():

    def __init__(self):

        self.es = elasticsearch.Elasticsearch()

    def search(self,phrase,orgid=0,entityid=0,pagesize=20,page=0):

        # create our response
        response = {}
        response['success'] = False
        response['error'] = ""
        response['count'] = 0
        response['phrase'] = ""
        response['results'] = []

        # build the search query body
        body = self._buildbody(phrase,orgid,entityid,pagesize,page)

        # perform the search
        results = self.es.search(
            index="monroeminutes",
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
            previewtext = self._buildpreviewtext(phrase,pdftext)
            
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

    def _buildpreviewtext(self,phrase,pdftext,beforelen=64,afterlen=64):

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
            if index < beforelen:
                beforeindex = 0
            else:
                beforeindex = index - BEFORE_LEN
            #print "before index: {0}, len: {1}".format(beforeindex,AFTER_LEN)
            preview = pdftext[beforeindex:(beforeindex+beforelen+afterlen)]
            preview = " ".join(preview.split(' ')[1:-1])
            preview = preview.replace('\t','').replace('\n','').replace('\f','')
            #print "preview text: {0}".format(preview)

            text += "... {0}".format(preview)

        text += " ..."
        return text

    def _buildbody(self,phrase,orgid,entityid,pagesize=25,page=0):
        
        if orgid == '' and entityid == '':
            #create the search query
            body = {"size": int(pagesize),
                    "from": int(page)*int(pagesize),
                    "query": {
                        "match": {
                            "pdftext": phrase
                   }}}
        else:
            # create the query
            body = {
                'size': int(pagesize),
                'from': int(page)*10,
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
            if orgid != '':
                body['query']['filtered']['filter']['query']['field']['orgid'] = orgid
            if entityid != '':
                body['query']['filtered']['filter']['query']['field']['bodyid'] = entityid

        return body 

    def _checkexists(self,pdfhash):

        # handle misfit case
        if pdfhash == "":
            return False

        body = {
            "query": {
                "match": {
                    "pdfhash": pdfhash
                }
            }
        }
        try:
            results = self.es.search(index="monroeminutes",
                                     body=body
            )
        except:
            # if we get here, the index is probably empty
            return False
        exists = False
        if len(results['hits']['hits']) > 0:
            exists = True

        return exists

    def indexdoc(self,body):

        # if not in the index already, pass document to elastic search to be indexed
        success = False
        if not self._checkexists(body['pdfhash']):
            self.es.index(
                index="monroeminutes",
                doc_type="pdfdoc",
                id=uuid.uuid4(),
                body=body,
            )
            success = True
        else:
            pass

        return success

if __name__ == '__main__':

    search = Search()

    phrase = "hello"
    pdftext = "I don't know how many times I need to tell her that I prefere hello over a simple hi."

    response = search.buildpreviewtext(phrase,pdftext)

    #response = search.search('today',orgid=1,bodyid=0)

    print response
