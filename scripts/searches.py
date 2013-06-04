import MySQLdb as mdb
import _mysql as mysql
import re

class searches:

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

    def add(self,searchterm,searchdt,organizationid):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("INSERT INTO searches(searchterm,searchdt,organizationid) VALUES(%s,%s,%s)",(self.__sanitize(searchterm),self.__sanitize(searchdt),self.__sanitize(organizationid)))
            cur.close()
            newid = cur.lastrowid
        return newid

    def get(self,searchid):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("SELECT * FROM searches WHERE searchid = %s",(searchid))
            row = cur.fetchone()
            cur.close()

    def getall(self):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("SELECT * FROM searches")
            rows = cur.fetchall()
            cur.close()

        _searches = []
        for row in rows:
            _searches.append(row)

        return _searches

    def delete(self,searchid):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("DELETE FROM searches WHERE searchid = %s",(searchid))
            cur.close()

    def update(self,searchid,searchterm,searchdt,organizationid):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("UPDATE searches SET searchterm = %s,searchdt = %s,organizationid = %s WHERE searchid = %s",(self.__sanitize(searchterm),self.__sanitize(searchdt),self.__sanitize(organizationid),self.__sanitize(searchid)))
            cur.close()

##### Application Specific Functions #####
