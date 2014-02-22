from time import strftime
from BarkingOwl.dispatcher.barkingowl_dispatcher import Dispatcher

from db.models import Urls
from db.models import Orgs

def geturls():
    urls = Urls()
    urllist = urls.getall()
    return urllist

def packageurl(url):
    urlid,targeturl,title,description,maxlinklevel,creationdatetime,doctype,frequency,bodyid = url  
 
    # we need to package all of the organizations that associated with the URL
    # into the payload so the archiver has enough information to classify the 
    # documents after converting them.
    orgs = Orgs()
    orglist = orgs.getall()
    urlorgs = []
    for org in orglist:
        org_orgid,org_name,org_description,org_creationdatetime,org_matchtext,org_urlid,org_bodyid = org
        if org_urlid == urlid:
            urlorgs.append({'orgid': org_orgid,
                            'name': org_name,
                            'description': org_description,
                            'creationdatetime': str(org_creationdatetime),
                            'matchtext': org_matchtext,
                            'urlid': org_urlid,
                            'bodyid': org_bodyid,
                           })

    # create the URL payload
    pkg = {
        'targeturl': targeturl,                    # required
        'title': title,                            # meta
        'description': description,                # meta
        'maxlinklevel': maxlinklevel,              # required
        'creationdatetime': str(creationdatetime), # meta
        'doctype': doctype,                        # required
        'frequency': frequency,                    # required
        'urlid': urlid,                            # meta
        'bodyid': bodyid,
        'orgs': urlorgs,                           # meta
    }

    return pkg

if __name__ == '__main__':
    
    print "Monroe Minutes Dispatcher Starting ..."

    # create our BarkingOwl dispatcher
    dispatcher = Dispatcher(address='localhost',exchange='monroeminutes')
    
    # get the list of URLs to dispatch
    urls = []
    urllist = geturls()
    for url in urllist:
        urls.append(packageurl(url))

    if len(urls) == 0:
        print "WARNING: URL list is empty."

    # start the dispatcher with the URL list
    dispatcher.seturls(urls)
    dispatcher.start()

    print "Monre Minutes Dispatcher Exiting ..."
