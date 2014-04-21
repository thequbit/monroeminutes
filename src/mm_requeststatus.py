import json
import pika
import uuid
import time

class Status(object):
    def __init__(self,address='localhost',exchange='monroeminutes',uuid=str(uuid.uuid4()),DEBUG=False):

        # create our uuid
        self.uid = uuid

        self.address = address
        self.exchange = exchange
        self.DEBUG = DEBUG

        #setup message bus
        self.reqcon = pika.BlockingConnection(pika.ConnectionParameters(host=address))
        self.reqchan = self.reqcon.channel()
        self.reqchan.exchange_declare(exchange=exchange,type='fanout')
        result = self.reqchan.queue_declare(exclusive=True)
        queue_name = result.method.queue
        self.reqchan.queue_bind(exchange=exchange,queue=queue_name)
        self.reqchan.basic_consume(self._reqcallback,queue=queue_name,no_ack=True)

        self.respcon = pika.BlockingConnection(pika.ConnectionParameters(host=self.address))
        self.respchan = self.respcon.channel()
        self.respchan.exchange_declare(exchange=self.exchange,type='fanout')

        

    def run(self):
        self.running = True
        while self.running:
            self._sendrequest()
            time.sleep(5)

    def _sendrequest(self):
        payload = {
            'command': 'get_status_simple',
            'sourceid': self.uid,
            'destinationid': 'broadcast',
            'message': {}
        }
        jbody = jbody = json.dumps(payload)
        self.respchan.basic_publish(exchange=self.exchange,routing_key='',body=jbody)

    def _reqcallback(self,ch,method,properties,body):
        response = json.loads(body)

        if response['command'] == 'scraper_status_simple':
            print response['message']

if __name__ == '__main__':

    status = Status()

    status.run()
