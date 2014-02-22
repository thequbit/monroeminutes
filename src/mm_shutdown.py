from BarkingOwl.tools.globalshutdown import GlobalShutdown

if __name__ == '__main__':

    print "Sending Global Shutdown message to bus ..."

    gs = GlobalShutdown(exchange='monroeminutes')
    gs.shutdown()

    print "Done."
