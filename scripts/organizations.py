import MySQLdb as mdb
import _mysql as mysql
import re

class organizations:

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

    def add(self,name,type,websiteurl):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("INSERT INTO organizations(name,type,websiteurl) VALUES(%s,%s,%s)",(self.__sanitize(name),self.__sanitize(type),self.__sanitize(websiteurl)))
            cur.close()
            newid = cur.lastrowid
        return newid

    def get(self,organizationid):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("SELECT * FROM organizations WHERE organizationid = %s",(organizationid))
            row = cur.fetchone()
            cur.close()

    def getall(self):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("SELECT * FROM organizations")
            rows = cur.fetchall()
            cur.close()

        _organizations = []
        for row in rows:
            _organizations.append(row)

        return _organizations

    def delete(self,organizationid):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("DELETE FROM organizations WHERE organizationid = %s",(organizationid))
            cur.close()

    def update(self,organizationid,name,type,websiteurl):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("UPDATE organizations SET name = %s,type = %s,websiteurl = %s WHERE organizationid = %s",(self.__sanitize(name),self.__sanitize(type),self.__sanitize(websiteurl),self.__sanitize(organizationid)))
            cur.close()

##### Application Specific Functions #####
