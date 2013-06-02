import MySQLdb as mdb
import _mysql as mysql
import re

class permissions:

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

    def add(self,isadmin,canlogin):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("INSERT INTO permissions(isadmin,canlogin) VALUES(%s,%s)",(self.__sanitize(isadmin),self.__sanitize(canlogin)))
            cur.close()
            newid = cur.lastrowid
        return newid

    def get(self,permissionid):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("SELECT * FROM permissions WHERE permissionid = %s",(permissionid))
            row = cur.fetchone()
            cur.close()

    def getall(self):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("SELECT * FROM permissions")
            rows = cur.fetchall()
            cur.close()

        _permissions = []
        for row in rows:
            _permissions.append(row)

        return _permissions

    def delete(self,permissionid):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("DELETE FROM permissions WHERE permissionid = %s",(permissionid))
            cur.close()

    def update(self,permissionid,isadmin,canlogin):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("UPDATE permissions SET isadmin = %s,canlogin = %s WHERE permissionid = %s",(self.__sanitize(isadmin),self.__sanitize(canlogin),self.__sanitize(permissionid)))
            cur.close()

##### Application Specific Functions #####
