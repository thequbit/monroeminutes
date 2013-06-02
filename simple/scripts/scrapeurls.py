import MySQLdb as mdb
import _mysql as mysql
import re

class scrapeurls:

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

    def add(self,url,name,organizationid,enabled):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("INSERT INTO scrapeurls(url,name,organizationid,enabled) VALUES(%s,%s,%s,%s)",(self.__sanitize(url),self.__sanitize(name),self.__sanitize(organizationid),self.__sanitize(enabled)))
            cur.close()
            newid = cur.lastrowid
        return newid

    def get(self,scrapeurlid):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("SELECT * FROM scrapeurls WHERE scrapeurlid = %s",(scrapeurlid))
            row = cur.fetchone()
            cur.close()

    def getall(self):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("SELECT * FROM scrapeurls")
            rows = cur.fetchall()
            cur.close()

        _scrapeurls = []
        for row in rows:
            _scrapeurls.append(row)

        return _scrapeurls

    def delete(self,scrapeurlid):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("DELETE FROM scrapeurls WHERE scrapeurlid = %s",(scrapeurlid))
            cur.close()

    def update(self,scrapeurlid,url,name,organizationid,enabled):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("UPDATE scrapeurls SET url = %s,name = %s,organizationid = %s,enabled = %s WHERE scrapeurlid = %s",(self.__sanitize(url),self.__sanitize(name),self.__sanitize(organizationid),self.__sanitize(enabled),self.__sanitize(scrapeurlid)))
            cur.close()

##### Application Specific Functions #####

    def geturls(self,organizationid):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("SELECT scrapeurlid,url FROM scrapeurls WHERE organizationid = %s",(organizationid))
            rows = cur.fetchall()

        _scrapeurls = []
        for row in rows:
            _scrapeurls.append(row)

        return _scrapeurls
