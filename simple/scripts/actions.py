import MySQLdb as mdb
import _mysql as mysql
import re

class actions:

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

    def add(self,userid,actiontype,pagename,description):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("INSERT INTO actions(userid,actiontype,pagename,description) VALUES(%s,%s,%s,%s)",(self.__sanitize(userid),self.__sanitize(actiontype),self.__sanitize(pagename),self.__sanitize(description)))
            cur.close()
            newid = cur.lastrowid
        return newid

    def get(self,actionid):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("SELECT * FROM actions WHERE actionid = %s",(actionid))
            row = cur.fetchone()
            cur.close()

    def getall(self):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("SELECT * FROM actions")
            rows = cur.fetchall()
            cur.close()

        _actions = []
        for row in rows:
            _actions.append(row)

        return _actions

    def delete(self,actionid):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("DELETE FROM actions WHERE actionid = %s",(actionid))
            cur.close()

    def update(self,actionid,userid,actiontype,pagename,description):
        with self.__con:
            cur = self.__con.cursor()
            cur.execute("UPDATE actions SET userid = %s,actiontype = %s,pagename = %s,description = %s WHERE actionid = %s",(self.__sanitize(userid),self.__sanitize(actiontype),self.__sanitize(pagename),self.__sanitize(description),self.__sanitize(actionid)))
            cur.close()

##### Application Specific Functions #####
