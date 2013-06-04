import MySQLdb as mdb
import _mysql as mysql
import re

class documenttexts:

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

    def add(self,documentid,documenttext):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("INSERT INTO documenttexts(documentid,documenttext) VALUES(%s,%s)",(self.__sanitize(documentid),self.__sanitize(documenttext)))
            cur.close()
            newid = cur.lastrowid
        return newid

    def get(self,documenttextid):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("SELECT * FROM documenttexts WHERE documenttextid = %s",(documenttextid))
            row = cur.fetchone()
            cur.close()

    def getall(self):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("SELECT * FROM documenttexts")
            rows = cur.fetchall()
            cur.close()

        _documenttexts = []
        for row in rows:
            _documenttexts.append(row)

        return _documenttexts

    def delete(self,documenttextid):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("DELETE FROM documenttexts WHERE documenttextid = %s",(documenttextid))
            cur.close()

    def update(self,documenttextid,documentid,documenttext):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("UPDATE documenttexts SET documentid = %s,documenttext = %s WHERE documenttextid = %s",(self.__sanitize(documentid),self.__sanitize(documenttext),self.__sanitize(documenttextid)))
            cur.close()

##### Application Specific Functions #####
