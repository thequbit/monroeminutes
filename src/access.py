import json

from time import strftime
from pymongo import MongoClient

class Access(object):

    def __init__(self,uri='mongodb://localhost:27017/',db='monroeminutesdb',collection='documents'):

        self.dbclient = MongoClient(uri)
        self.db = self.dbclient[db]
        self.collection = self.db[collection] 

    def adddoc(self,docurl,linktext,docfilename,scrapedatetime,urldata):

        # see if we already have the doc in the database
        result = self.collection.find_one({'docurl': docurl})

        if result == None:
            doc = {
                'docurl': docurl,
                'linktext': linktext,
                'docfilename': docfilename,
                'scrapedatetime': scrapedatetime,
                'being_converted': False,
                'converted': False,
                'being_processed': False,
                'processed': False,
                'urldata': urldata,
                'pdftext': '',
                'pdfhash': '',
            }
            docid = self.collection.insert(doc)
        else:
            # doc already in db
            docid = None
            pass

        return docid

    def getdoc(self,docurl):

        # get the entry based on the docurl
        result = self.collection.find_one( {'docurl': docurl} )
        return result

    def _getall(self):

        # return all docs in the database
        results = self.collection.find()
        docs = []
        for result in results:
            docs.append(result)
        return docs

    def _clearall(self):

        # blow away the entire database
        self.collection.remove()

        return True

    def getunprocessed(self):

        # get the doc, and mark the 'being_processed' flag
        doc = self.collection.find_and_modify(query={'being_processed': False, 'processed': False},
                                              update={ '$set': {'being_processed': True} },
                                              full_response=False,
                                              multi=False,
        )
        return doc

    def setprocessed(self,docurl):

        # get the doc, and mark the 'being_processed' flag
        doc = self.collection.find_and_modify(query={ 'docurl':docurl },
                                              update={ '$set': {'being_processed': False, 'processed': True} },
                                              full_response=False,
                                              multi=False,
        )
        return doc

    def getunconverted(self):
    
        # get the doc, and mark the 'being_processed' flag
        doc = self.collection.find_and_modify(query={ 'being_converted': False, 'converted': False},
                                              update={ '$set': {'being_converted': True} },
                                              full_response=False,
                                              multi=False,
        )
        return doc

    def setconverted(self,docurl):

        # get the doc, and mark the 'being_processed' flag
        doc = self.collection.find_and_modify(query={ 'docurl':docurl },
                                              update={ '$set': {'being_converted': False, 'converted': True} },
                                              full_response=False,
                                              multi=False,
        )
        return doc

    def setpdfdata(self,docurl,pdftext,pdfhash):

        # update the doc with the pdftext, and return the new doc
        doc = self.collection.find_and_modify(query={'docurl': docurl},
                                              update={ '$set': {'pdftext': pdftext, 'converted': True} },
                                              full_response=False,
                                              multi=False,
        )
        return doc

if __name__ == '__main__':

    docurl='http://timduffy.me/Resume-TimDuffy-20130813.pdf'
    linktext='resume'
    filename='Resume-TimDuffy-20130813.pdf_341236213461.download'
    scrapedatetime=str(strftime("%Y-%m-%d %H:%M:%S"))
    urldata = {
        'maxlinklevel': 1, 
        'doctype': 'application/pdf', 
        'targeturl': 'http://timduffy.me',
    }

    access = Access(db='testdb',collection='collection')

    access.collection.remove()

    doc = access.adddoc(docurl,linktext,filename,scrapedatetime,urldata)

    result = access.getunconverted()

    result = access.getdoc(docurl)

    result = access.setconverted(docurl)

    result = access.getunprocessed()

    result = access.getdoc(docurl)

    result = access.setprocessed(docurl)

    result = access.setpdfdata(docurl=docurl,pdftext="some pdf text",pdfhash='0000')

    result = access.getdoc(result['docurl'])

    print 'done'
