unpdfer
=======

takes in a PDF document and returns the text from within it and additional information about the doc

#####About#####

This tool will take in a PDF filename and produce a blog of the text within it, the MD5 hash of that blog,
the Natural Language Tool Kit (NLTK) tokens (word frequency histogram) for the blob.  This can be used to quickly
pull the text from a PDF document, as well as deturmine if the PDF has changed, and what words exist within the
document.

#####Usage#####

    >>> from unpdfer import UnPDFer
    >>>
    >>> unpdfer = UnPDFer()
    >>> filename = "input.pdf"
    >>> (pdftext,pdfhash,_tokens,success) = unpdfer.unpdf(filename,SCRUB=True)
    >>> tokens = []
    >>> for token,frequency in tokens.items():
    >>>    if len(token) > 3:
    >>>        tokens.append((token,frequency))
    >>> 
    >>> print success,pdftext,pdfhash,tokens
    
