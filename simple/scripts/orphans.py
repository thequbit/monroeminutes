import MySQLdb as mdb
import _mysql as mysql
import re

class orphans:

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

    def add(self,orphanid,url,orphandt,scrapeurlid,organizationid):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("INSERT INTO orphans(orphanid,url,orphandt,scrapeurlid,organizationid) VALUES(%s,%s,%s,%s,%s)",(orphanid,url,orphandt,scrapeurlid,organizationid))
            cur.close()
            newid = cur.lastrowid
        return newid

    def get(self,):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("SELECT * FROM orphans WHERE  = %s",())
            row = cur.fetchone()
            cur.close()

    def getall(self):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("SELECT * FROM orphans")
            rows = cur.fetchall()
            cur.close()

        _orphans = []
        for row in rows:
            _orphans.append(row)

        return _orphans

    def delete(self,):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("DELETE FROM orphans WHERE  = %s",())
            cur.close()

    def update(self,orphanid,url,orphandt,scrapeurlid,organizationid):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("UPDATE orphans SET orphanid = %s,url = %s,orphandt = %s,scrapeurlid = %s,organizationid = %s WHERE  = %s",(orphanid,url,orphandt,scrapeurlid,organizationid,))
            cur.close()




