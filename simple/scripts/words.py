import MySQLdb as mdb
import _mysql as mysql
import re

class words:

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

    def add(self,documentid,suborganizationid,organizationid,word,frequency):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("INSERT INTO words(documentid,suborganizationid,organizationid,word,frequency) VALUES(%s,%s,%s,%s,%s)",(documentid,suborganizationid,organizationid,word,frequency))
            cur.close()

    def get(self,wordid):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("SELECT * FROM words WHERE wordid = %s",(wordid))
            row = cur.fetchone()
            cur.close()

    def getall(self):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("SELECT * FROM words")
            rows = cur.fetchall()
            cur.close()

        _words = []
        for row in rows:
            _words.append(row)

        return _words

    def delete(self,wordid):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("DELETE FROM words WHERE wordid = %s",(wordid))
            cur.close()

    def update(self,wordid,documentid,suborganizationid,organizationid,word,frequency):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("UPDATE words SET documentid = %s,suborganizationid = %s,organizationid = %s,word = %s,frequency = %s WHERE wordid = %s",(documentid,suborganizationid,organizationid,word,frequency,wordid))
            cur.close()




