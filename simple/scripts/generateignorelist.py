import sys
import os
from random import randint
from bs4 import BeautifulSoup
import urllib
import urllib2
import datetime
import magic

from scrapeurls import scrapeurls
from ignoreurls import ignoreurls

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

def get_url_list():
    surls = scrapeurls()
    urls = surls.getall()
    return urls

def get_ignore_list():
    iurls = ignoreurls()
    urls = iurls.getall()
    retval = []
    for url in urls:
        ignoreurlid,ignoreurl,ignoredt,scrapeurlid = url
        retval.append(ignoreurl)
    return urls

def get_page_links(url):
    html = urllib2.urlopen(url)
    soup = BeautifulSoup(html)
    atags = soup.find_all('a', href=True)
    return atags

def decode_link_url(tag,siteurl,baseurl):
    # make sure the URL is formatted correctly
    if tag['href'][0:7].lower() == "http://":
        linkurl = tag['href']
    else:
        if tag['href'][0:1] == "/":
            # absolute link
            linkurl = siteurl + tag['href']
        else:
            # relative link
            linkurl = baseurl + tag['href']
    return linkurl

def download_link(url):
    # get the filename off of the url, then set the local file to that + a random number .pdf, and download it
    urlfile = url[url.rfind("/")+1:]
    _filename = "./downloads/{0}_{1}.download".format(urlfile,randint(0,1000000))
    filename,headers = urllib.urlretrieve(url,_filename)
    return filename

def add_to_ignore_list(link,scrapeurlid):
    dt = datetime.datetime.now().date().isoformat()
    iurls = ignoreurls()
    iurls.add(link,dt,scrapeurlid)

def check_if_pdf(filename):
    result = magic.from_file(filename,mime=True)
    report("info","File Type = {0}".format(result))
    return (result == 'application/pdf')

def delete_file(filename):
    os.remove(filename)

def main(argv):

    print "\nStarting Application ...\n"

    urls = get_url_list();

    report("info","Processing {0} scraper URL's".format(len(urls)))

    for _url in urls:
        scrapeurlid,url,name,organizationid,enabled = _url
        
        if enabled == False:
            continue

        # decode the base url and site url
        baseurl = url[:url.rfind("/")+1]
        siteurl = url[:url.find("/",7)]

        # get all of the links from the page
        links = get_page_links(url)
        report("info","{0} page links found on {1}".format(len(links),url))

        # iterate through the list of links and deturmine which ones are not PDFs
        for _link in links:
            ignorelist = get_ignore_list()
           
            link = decode_link_url(_link,siteurl,baseurl)
 
            # make sure that it isn't already in the list
            if not (link in ignorelist):
                filename = download_link(link)
                success = check_if_pdf(filename)
                delete_file(filename)
                
                if success == True:
                    report("info","PDF Found at `{0}`.".format(link))
                else:
                    add_to_ignore_list(link,scrapeurlid)
                    report("info","Added `{0}` to ignore list.".format(link))
            
    print "\nExiting Application ...\n"

if __name__ == '__main__': sys.exit(main(sys.argv))
