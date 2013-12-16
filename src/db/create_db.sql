CREATE DATABASE monroeminutes;

GRANT USAGE ON monroeminutes.* TO mmuser IDENTIFIED BY 'password123%%%';

GRANT ALL PRIVILEGES ON monroeminutes.* TO mmuser;

USE monroeminutes;

CREATE TABLE IF NOT EXISTS organizations(
    organizationid INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name CHAR(255) NOT NULL,
    description TEXT NOT NULL,
    creationdatetime DATETIME NOT NULL
);
CREATE INDEX organizations_organizationid ON organizations(organizationid);

CREATE TABLE IF NOT EXISTS urls(
    urlid INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    targeturl TEXT NOT NULL,
    title CHAR(255) NOT NULL,
    description TEXT NOT NULL,
    maxlinklevel INT NOT NULL,
    creationdatetime DATETIME NOT NULL,
    doctype CHAR(127) NOT NULL,
    frequency INT NOT NULL,
    organizationid INT NOT NULL,
    FOREIGN KEY (organizationid) REFERENCES organizations(organizationid)
);
CREATE INDEX urls_urlid ON urls(urlid);

CREATE TABLE IF NOT EXISTS runs(
    runid INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    startdatetime DATETIME NOT NULL,
    enddatetime DATETIME NOT NULL,
    linkcount INT NOT NULL,
    scraperid CHAR(255) NOT NULL
);
CREATE INDEX runs_runid ON runs(runid);

CREATE TABLE IF NOT EXISTS docs(
    docid INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    docurl TEXT NOT NULL,
    linktext CHAR(255) NOT NULL,
    urlid INT NOT NULL,
    FOREIGN KEY (urlid) REFERENCES docs(docid),
    creationdatetime DATETIME NOT NULL,
    pdfhash CHAR(255) NOT NULL,
    runid INT NOT NULL,
    FOREIGN KEY (runid) REFERENCES runs(runid)
);
CREATE INDEX docs_docid ON docs(docid);

