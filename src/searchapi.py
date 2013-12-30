import elasticsearch
import json

class Search():

    def __init__(self):

        self.es = elasticsearch.Elasticsearch()

    def search(self,phrase,orgid=0,bodyid=0,page=0):

        # create our response
        response = {}
        response['success'] = False
        response['error'] = ""
        response['count'] = 0
        response['results'] = []

        if orgid !=0 and bodyid != 0:
            response['error'] = "orgid and bodyid both specified, only one can be used at a time."
            return response

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

        #try:
        if True:
            # perform the search
            results = self.es.search(index="monroeminutes",
                                body=body
            )

            # create our return object to send back
            response['success'] = True
            response['count'] = len(results['hits']['hits'])
            response['results'] = []
            for hit in results['hits']['hits']:
                response['results'].append({
                    'score': hit['_score'],
                    'docid': hit['_id'],
                    'docurl': hit['_source']['docurl'],
                    'docname': hit['_source']['docname'],
                    'scrapedatetime': hit['_source']['scrapedatetime'],
                    'linktext': hit['_source']['linktext'],
                    'targeturl': hit['_source']['targeturl']
                })

        #except:
            # if unsuccessfull, then we will return success = False
        #    pass

        return response

if __name__ == '__main__':

    search = Search()

    response = search.search('scottsville',orgid=0,bodyid=1)

    print response
