import sys
import datetime
import urllib
import urllib2
from random import randint
from bs4 import BeautifulSoup

from decodepdf import decodepdf

from scrapeurls import scrapeurls
from ignoreurls import ignoreurls
from suborganizations import suborganizations
from documents import documents
from documenttexts import documenttexts

def report(type,text):
    if type == "info":
        type = "INFO   "
    elif type == "warning":
        type = "WARNING"
    elif type == "error":
        type = "ERROR  "
    else:
        type = "OTHER  "
    print "[{0}] {1}".format(type,text)

def getorgurls(orgid):
    surls = scrapeurls()
    urls = surls.geturls(orgid)
    return urls

def getlinks(urlid,url,baseurl,siteurl):
    iurls = ignoreurls()
    ignorelinks = iurls.getallbyscrapeurlid(urlid)

    _pagelinks = _get_page_links(url)

    pagelinks = []
    for pagelink in _pagelinks:
        link = decodelink(pagelink,baseurl,siteurl)
        pagelinks.append(link)

    # diff the lists, dropping links from the ignore list
    links = [x for x in pagelinks if x not in ignorelinks]

    return links

def _get_page_links(url):
    html = urllib2.urlopen(url)
    soup = BeautifulSoup(html)
    atags = soup.find_all('a', href=True)
    return atags

def decodelink(tag,siteurl,baseurl):
    #try:
        # make sure the URL is formatted correctly
        print type(tag)
        if len(tag['href']) >= 7 and tag['href'][0:7].lower() == "http://":
                linkurl = tag['href']
        else:
            if len(tag['href']) >= 1 and tag['href'][0:1] == "/":
                # absolute link
                linkurl = siteurl + tag['href'][1:]
            else:
                # relative link
                linkurl = baseurl + tag['href']
    #except:
    #    linkurl = ""
        return linkurl

def downloadlink(url):
    # get the filename off of the url, then set the local file to that + a random number .pdf, and download it
    urlfile = url[url.rfind("/")+1:]
    _filename = "./downloads/{0}_{1}.download".format(urlfile,randint(0,1000000))
    filename,headers = urllib.urlretrieve(url,_filename)
    return filename

def getsuborg(pdfheader):
    sorgs = suborganizations()
    suborgs = sorgs.getall()
    success = False
    for suborg in suborgs:
        suborganizationid,organizationid,name,parsename,websiteurl,documentsurl,scriptname,dbpopulated = suborg
        if parsename in pdfheader:
            success = True
            break
    return success,suborganizationid

def getdocdate(pdfheader):
    # TODO: parse the header to get the date
    return "1970-1-1"

def getdocname(pdfheader):
    # TODO: parse the header to get the name
    return "document"

def savedoc(suborgid,orgid,sourceurl,documentdate,name,dochash,pdftext,tokens):
    scrapedt = datetime.datetime.now().isoformat()
    doc = documents()
    docid = doc.add(suborgid,orgid,sourceurl,documentdate,scrapedt,name,dochash)
    doct = documenttexts()
    doct.add(docid,pdftext)
    return docid

def main(argv):
    print "\nApplication Started ...\n"

    # edit this to change the size of the pdf to look at as the header
    HEADERLENGTH = 2048

    if len(argv) != 2:
        print "Usage:\n\tpython simplescraper.py <orgid>"
        return
    
    orgid = argv[1]

    urls = getorgurls(orgid)

    report("info","Organization has {0} URLS.".format(len(urls)))

    for _url in urls:
        urlid,url = _url
        
        # decode the base url and site url
        baseurl = url[:url.rfind("/")+1]
        siteurl = url[:url.find("/",7)]

        links = getlinks(urlid,url,baseurl,siteurl)

        report("info","Working on {0} Links from URL ...".format(len(links)))

        for link in links:
            report("info","Working on URL `{0}`".format(link))
            filename = downloadlink(link)
            success,pdftext,texthash = decodepdf(filename)
            if success == True:
                success,suborgid = getsuborg(pdftext[:HEADERLENGTH])
                if success == True:
                    docdate = getdocdate(pdftext[:HEADERLENGTH])
                    docname = getdocname(pdftext[:HEADERLENGTH])
                    _tokens = nltk.word_tokenize(pdfTextScrubbed)
                    tokens = nltk.FreqDist(word.lower() for word in _tokens)
                    docid = savedoc(suborgid,orgid,link,docdate,docname,dochash,pdftext,tokens)
                    report("info","Successfully Parsed And Added Document #{0}".format(docid))
                else:
                    report("error","Unable to Decode Suborganization from PDF document.")
            else:
                report("error","Unable to Parse PDF Into Text.")

    print "\nApplication Exiting ...\n"

if __name__ == '__main__': sys.exit(main(sys.argv))
