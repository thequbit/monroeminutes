import MySQLdb as mdb
import _mysql as mysql
import re

class runs:

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

    def sanitize(self,valuein):
        valueout = mysql.escape_string(valuein)
        return valuein

    def add(self,rundt,scrapername,successful,organizationid,suborganizationid):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("INSERT INTO runs(rundt,scrapername,successful,organizationid,suborganizationid) VALUES(%s,%s,%s,%s,%s)",(self.__sanitize(rundt),self.__sanitize(scrapername),self.__sanitize(successful),self.__sanitize(organizationid),self.__sanitize(suborganizationid)))
            cur.close()
            newid = cur.lastrowid
        return newid

    def get(self,runid):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("SELECT * FROM runs WHERE runid = %s",(runid))
            row = cur.fetchone()
            cur.close()

    def getall(self):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("SELECT * FROM runs")
            rows = cur.fetchall()
            cur.close()

        _runs = []
        for row in rows:
            _runs.append(row)

        return _runs

    def delete(self,runid):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("DELETE FROM runs WHERE runid = %s",(runid))
            cur.close()

    def update(self,runid,rundt,scrapername,successful,organizationid,suborganizationid):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("UPDATE runs SET rundt = %s,scrapername = %s,successful = %s,organizationid = %s,suborganizationid = %s WHERE runid = %s",(self.__sanitize(rundt),self.__sanitize(scrapername),self.__sanitize(successful),self.__sanitize(organizationid),self.__sanitize(suborganizationid),self.__sanitize(runid)))
            cur.close()

##### Application Specific Functions #####
