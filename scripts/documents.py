import MySQLdb as mdb
import _mysql as mysql
import re

class documents:

    __settings = {}
    __con = False

    def __init__(self):
        configfile = "sqlcreds.txt"
        f = open(configfile)
        for line in f:
            # skip comment lines
            m = re.search('^\s*#', line)
            if m:
                continue

            # parse key=value lines
            m = re.search('^(\w+)\s*=\s*(\S.*)$', line)
            if m is None:
                continue

            self.__settings[m.group(1)] = m.group(2)
        f.close()

        # create connection
        self.__con = mdb.connect(host=self.__settings['host'], user=self.__settings['username'], passwd=self.__settings['password'], db=self.__settings['database'])

    def __sanitize(self,valuein):
        if type(valuein) == 'str':
            valueout = mysql.escape_string(valuein)
        else:
            valueout = valuein
        return valuein

    def add(self,suborganizationid,organizationid,sourceurl,documentdate,scrapedate,name,dochash,orphaned):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("INSERT INTO documents(suborganizationid,organizationid,sourceurl,documentdate,scrapedate,name,dochash,orphaned) VALUES(%s,%s,%s,%s,%s,%s,%s,%s)",(self.__sanitize(suborganizationid),self.__sanitize(organizationid),self.__sanitize(sourceurl),self.__sanitize(documentdate),self.__sanitize(scrapedate),self.__sanitize(name),self.__sanitize(dochash),self.__sanitize(orphaned)))
            cur.close()
            newid = cur.lastrowid
        return newid

    def get(self,documentid):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("SELECT * FROM documents WHERE documentid = %s",(documentid))
            row = cur.fetchone()
            cur.close()

    def getall(self):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("SELECT * FROM documents")
            rows = cur.fetchall()
            cur.close()

        _documents = []
        for row in rows:
            _documents.append(row)

        return _documents

    def delete(self,documentid):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("DELETE FROM documents WHERE documentid = %s",(documentid))
            cur.close()

    def update(self,documentid,suborganizationid,organizationid,sourceurl,documentdate,scrapedate,name,dochash,orphaned):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("UPDATE documents SET suborganizationid = %s,organizationid = %s,sourceurl = %s,documentdate = %s,scrapedate = %s,name = %s,dochash = %s,orphaned = %s WHERE documentid = %s",(self.__sanitize(suborganizationid),self.__sanitize(organizationid),self.__sanitize(sourceurl),self.__sanitize(documentdate),self.__sanitize(scrapedate),self.__sanitize(name),self.__sanitize(dochash),self.__sanitize(orphaned),self.__sanitize(documentid)))
            cur.close()

##### Application Specific Functions #####

    def urlexists(self,url):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("SELECT count(documentid) as count FROM documents WHERE sourceurl = %s",(url))
            row = cur.fetchone()
            cur.close()
        
        _exists = False
        if int(row['count'] != 0):
            _exists = True

        return _exists


    def hashexists(self,url):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("SELECT count(documentid) as count FROM documents WHERE dochash = %s",(url))
            row = cur.fetchone()
            cur.close()

        _exists = False
        if int(row['count'] != 0):
            _exists = True

        return _exists
