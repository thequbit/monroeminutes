from access import Access

if __name__ == '__main__':
    
    print "Resetting database flags ..."

    a = Access()

    a._resetstates()

    print "Done."
