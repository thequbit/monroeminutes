import pika
import simplejson

import threading

import elasticsearch

from dler.dler import DLer
from unpdfer.unpdfer import UnPDFer

#from models import *
from monroeminutes_docprocessor import DocProcessor

class Archiver(object):

    def __init__(self,address='localhost',exchange='barkingowl',downloaddir="./downloads",DEBUG=False):

        self.exchange = exchange
        self.downloaddir = downloaddir
        self.DEBUG = DEBUG
        
    
        #setup message bus
        self.reqcon = pika.BlockingConnection(pika.ConnectionParameters(host=address))
        self.reqchan = self.reqcon.channel()
        self.reqchan.exchange_declare(exchange=self.exchange,type='fanout')
        result = self.reqchan.queue_declare(exclusive=True)
        queue_name = result.method.queue
        self.reqchan.queue_bind(exchange=self.exchange,queue=queue_name)
        self.reqchan.basic_consume(self.reqcallback,queue=queue_name,no_ack=True)

    def start(self):
        print "Listening for new documents ..."
        self.reqchan.start_consuming()

    ####
    #
    # Message Bus handler
    #
    ####

    def reqcallback(self,ch,method,properties,body):
        response = simplejson.loads(body)

        if self.DEBUG:
            print "Archiver: Message Recieved ('{0}')".format(response['command'])

        #
        # This means that a pdf document has been found and 
        # broadcasted to the bus
        #
        if response['command'] == 'found_doc':
            
            if self.DEBUG:
                print "Processing new document ..."

            docurl = response['message']['docurl']
            linktext = response['message']['linktext']
            urldata = response['message']['urldata']
            scrapedatetime = response['message']['scrapedatetime']

            #
            # TODO: start thread to process the doc
            #

            processor = DocProcessor(downloaddir=self.downloaddir,DEBUG=self.DEBUG)
            processor.start()
            processor.processdoc(docurl,linktext,urldata,scrapedatetime)

            if self.DEBUG:
                print "Done with processing new document."

        elif response['command'] == 'global_shutdown':
            print "Global Shutdown Command Seen"
            raise Exception("Archiver Exiting.")

if __name__ == '__main__':

    print "Archiver Starting ..."

    downloaddir="/home/administrator/dev/monroeminutes/downloads/"

    standalone = False
    if standalone:
        docurl = "http://timduffy.me/poster.pdf"
        linktext = "resume"
        urldata = {
            'urlid': 4,                       # meta
            'targeturl': 'http://timduffy.me/',               # required
            'title': 'TimDuffy.Me',                       # required
            'description': "Tim Duffy's Personal Website",           # required
            'maxlinklevel': 2,         # required
            'creationdatetime': '2013-12-18 00:00:00 -0500', # required
            'doctype': 'application/pdf',                   # required
            'frequency': 1,               # required
           'organizationid': 3      # meta
        }
        scrapedatetime = "2013-01-01 00:00:00 -0500"    

        processor = DocProcessor(downloaddir=downloaddir,DEBUG=True)
        processor.start()
        processor.processdoc(docurl,linktext,urldata,scrapedatetime)
    else:

        archiver = Archiver(address='localhost',exchange='monroeminutes',downloaddir=downloaddir,DEBUG=True)
        #try:
        archiver.start()
        #except:
        #    print "Archiver Exiting."

