import MySQLdb as mdb
import _mysql as mysql
import re

import __dbcreds__

class Docs:

    __con = False

    def __connect(self):
        con = mdb.connect(host   = __dbcreds__.__server__,
                          user   = __dbcreds__.__username__,
                          passwd = __dbcreds__.__password__,
                          db     = __dbcreds__.__database__,
                         )
        return con

    def __sanitize(self,valuein):
        if type(valuein) == 'str':
            valueout = mysql.escape_string(valuein)
        else:
            valueout = valuein
        return valuein

    def add(self,docurl,linktext,urlid,creationdatetime,pdfhash):
        try:
            con = self.__connect()
            with con:
                cur = con.cursor()
                cur.execute("INSERT INTO docs(docurl,linktext,urlid,creationdatetime,pdfhash) VALUES(%s,%s,%s,%s,%s)",
                            (self.__sanitize(docurl),self.__sanitize(linktext),self.__sanitize(urlid),self.__sanitize(creationdatetime),self.__sanitize(pdfhash))
                           )
                cur.close()
                newid = cur.lastrowid
            con.close()
        except Exception, e:
            raise Exception("sql2api error - add() failed with error:\n\n\t{0}".format(e))
        return newid

    def get(self,docid):
        try:
            con = self.__connect()
            with con:
                cur = con.cursor()
                cur.execute("SELECT * FROM docs WHERE docid = %s",
                            (docid)
                           )
                row = cur.fetchone()
                cur.close()
            con.close()
        except Exception, e:
            raise Exception("sql2api error - get() failed with error:\n\n\t{0}".format(e))
        return row

    def getall(self):
        try:
            con = self.__connect()
            with con:
                cur = con.cursor()
                cur.execute("SELECT * FROM docs")
                rows = cur.fetchall()
                cur.close()
            _docs = []
            for row in rows:
                _docs.append(row)
            con.close()
        except Exception, e:
            raise Exception("sql2api error - getall() failed with error:\n\n\t{0}".format(e))
        return _docs

    def delete(self,docid):
        try:
            con = self.__connect()
            with con:
                cur = con.cursor()
                cur.execute("DELETE FROM docs WHERE docid = %s",
                            (self.__sanitize(docid))
                           )
                cur.close()
            con.close()
        except Exception, e:
            raise Exception("sql2api error - delete() failed with error:\n\n\t{0}".format(e))

    def update(self,docid,docurl,linktext,urlid,creationdatetime,pdfhash):
        try:
            con = self.__connect()
            with con:
                cur = con.cursor()
                cur.execute("UPDATE docs SET docurl = %s,linktext = %s,urlid = %s,creationdatetime = %s,pdfhash = %s WHERE docid = %s",
                            (self.__sanitize(docurl),self.__sanitize(linktext),self.__sanitize(urlid),self.__sanitize(creationdatetime),self.__sanitize(pdfhash),self.__sanitize(docid))
                           )
                cur.close()
            con.close()
        except Exception, e:
            raise Exception("sql2api error - update() failed with error:\n\nt{0}".format(e))

    ##### Application Specific Functions #####

#    def myfunc():
#        try:
#            con = self.__connect()
#            with con:
#                cur = son.cursor()
#                cur.execute("")
#                row = cur.fetchone()
#                cur.close()
#            con.close()
#        raise Exception("sql2api error - myfunct() failed with error:\n\n\t".format(e))
#        return row


