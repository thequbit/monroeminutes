from pdfminer.pdfinterp import PDFResourceManager, process_pdf
from pdfminer.converter import TextConverter
from pdfminer.layout import LAParams

import hashlib

from suborganizations import suborganizations

def _pdf_to_text(path):

    try:
        rsrcmgr = PDFResourceManager()
        retstr = StringIO()
        codec = 'ascii'
        laparams = LAParams()
        laparams.all_texts = True
        device = TextConverter(rsrcmgr, retstr, codec=codec, laparams=laparams)

        with open(path, 'rb') as fp:
            process_pdf(rsrcmgr, device, fp)
            device.close()

            # fix the non-utf8 string ...
            result = retstr.getvalue()
            txt = result.encode('ascii','ignore')

            retVal = (txt,True)
            retstr.close()

    except:
        #print "\tERROR: PDF is not formatted correctly, aborting."
        retVal = ("", False)
        pass

    return retVal

def _scrub_text(text):
    scrubbedString = text.replace(',','').replace('.','').replace('?','').replace('/',' ').replace(':','').replace(';','').replace('<','').replace('>','').replace('[','').replace(']','').replace('\\',' ').replace('"','').replace("'",'').replace('`','')

    return scrubstr

def decodepdf(path):

    pdftext,success = _pdf_to_text(path)
    pdftext = _scrub_text(pdftext)

    texthash = ""
    if success == True:
        texthash = hashlib.md5(pdftext).hexdigest()
    
    return success,pdftext,texthash
