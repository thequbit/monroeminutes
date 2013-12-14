CREATE DATABASE monroeminutes;

GRANT USAGE ON monroeminutes.* TO mmuser IDENTIFIED BY 'password123%%%';

GRANT ALL PRIVILEGES ON monroeminutes.* TO mmuser;

USE monroeminutes;

-- barkingowl urls table - holds urls to be scraped
CREATE TABLE IF NOT EXISTS urls(
    urlid INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    targeturl TEXT NOT NULL,
    maxlinklevel INT NOT NULL,
    creationdatetime DATETIME NOT NULL,
    doctypeid INT NOT NULL,
    FOREIGN KEY (doctypeid) REFERENCES doctypes(doctypeid)
);
CREATE INDEX urls_urlid ON urls(urlid);

-- barkingowl document types table - holds the different document types
CREATE TABLE IF NOT EXISTS doctypes(
    doctypeid INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title CHAR(256) NOT NULL,
    description TEXT NOT NULL,
    doctype CHAR(256) NOT NULL
);
CREATE INDEX doctypes_doctypeid ON doctypes(doctypeid);
CREATE INDEX doctypes_doctype ON doctypes(doctype);

CREATE TABLE IF NOT EXISTS docs(
    
); 
