import elasticsearch
import json

class Search():

    def __init__(self):

        self.es = elasticsearch.Elasticsearch()

    def search(self,phrase,orgid=0,page=0):

        # create our response
        response = {}
        response['success'] = False
        response['count'] = 0
        response['results'] = []

        if orgid == 0:
            #create the search query
            body = {"size": 10,
                    "from": page*10,
                    "query": {
                        "match": {
                            "pdftext": phrase
                   }}}
        else:

            #
            # TODO: figure out how to only return items with matching orgid 
            #

            # create the search query that is orgid specifc
            #body = {
            #    "size": 10,
            #    "from": page*10,
            #    "query": {
            #        "match": {
            #            "pdftext": phrase,
            #        },    
            #        "field": {
            #            "orgid": orgid,
            #        }
            #    }
            #}

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
                                    'orgid': orgid,
                                }
                            }
                        }
                    }
                }
            }

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

    response = search.search('scottsville',orgid=1)

    print response
