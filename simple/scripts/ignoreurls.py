import MySQLdb as mdb
import _mysql as mysql
import re

class ignoreurls:

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

    def add(self,url,ignoredt,scrapeurlid):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("INSERT INTO ignoreurls(url,ignoredt,scrapeurlid) VALUES(%s,%s,%s)",(url,ignoredt,scrapeurlid))
            cur.close()

    def get(self,ignoreurlid):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("SELECT * FROM ignoreurls WHERE ignoreurlid = %s",(ignoreurlid))
            row = cur.fetchone()
            cur.close()

    def getall(self):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("SELECT * FROM ignoreurls")
            rows = cur.fetchall()
            cur.close()

        _ignoreurls = []
        for row in rows:
            _ignoreurls.append(row)

        return _ignoreurls

    def delete(self,ignoreurlid):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("DELETE FROM ignoreurls WHERE ignoreurlid = %s",(ignoreurlid))
            cur.close()

    def update(self,ignoreurlid,url,ignoredt,scrapeurlid):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("UPDATE ignoreurls SET url = %s,ignoredt = %s,scrapeurlid = %s WHERE ignoreurlid = %s",(url,ignoredt,scrapeurlid,ignoreurlid))
            cur.close()

##### Application Specific Functions

    def getallbyscrapeurlid(self,scraperurlid):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("SELECT url FROM ignoreurls WHERE scrapeurlid = %s",(scraperurlid))
            rows = cur.fetchall()
            cur.close()
        
        _urls = []
        for row in rows:
            _urls.append(row[0])

        return _urls


