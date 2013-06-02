import MySQLdb as mdb
import _mysql as mysql
import re

class suborganizations:

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

    def add(self,organizationid,name,parsename,websiteurl,documentsurl,scriptname,dbpopulated):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("INSERT INTO suborganizations(organizationid,name,parsename,websiteurl,documentsurl,scriptname,dbpopulated) VALUES(%s,%s,%s,%s,%s,%s,%s)",(self.__sanitize(organizationid),self.__sanitize(name),self.__sanitize(parsename),self.__sanitize(websiteurl),self.__sanitize(documentsurl),self.__sanitize(scriptname),self.__sanitize(dbpopulated)))
            cur.close()
            newid = cur.lastrowid
        return newid

    def get(self,suborganizationid):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("SELECT * FROM suborganizations WHERE suborganizationid = %s",(suborganizationid))
            row = cur.fetchone()
            cur.close()

    def getall(self):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("SELECT * FROM suborganizations")
            rows = cur.fetchall()
            cur.close()

        _suborganizations = []
        for row in rows:
            _suborganizations.append(row)

        return _suborganizations

    def delete(self,suborganizationid):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("DELETE FROM suborganizations WHERE suborganizationid = %s",(suborganizationid))
            cur.close()

    def update(self,suborganizationid,organizationid,name,parsename,websiteurl,documentsurl,scriptname,dbpopulated):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("UPDATE suborganizations SET organizationid = %s,name = %s,parsename = %s,websiteurl = %s,documentsurl = %s,scriptname = %s,dbpopulated = %s WHERE suborganizationid = %s",(self.__sanitize(organizationid),self.__sanitize(name),self.__sanitize(parsename),self.__sanitize(websiteurl),self.__sanitize(documentsurl),self.__sanitize(scriptname),self.__sanitize(dbpopulated),self.__sanitize(suborganizationid)))
            cur.close()

##### Application Specific Functions #####
