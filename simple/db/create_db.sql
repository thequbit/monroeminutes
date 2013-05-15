drop database monroeminutesdb;
create database monroeminutesdb;

grant usage on monroeminutesdb.* to mmuser identified by 'xxx';

grant all privileges on monroeminutesdb.* to mmuser;

use monroeminutesdb;

create table organizations(
organizationid int not null auto_increment primary key,
name varchar(255) not null,
type varchar(255) not null
);

create table suborganizations(
suborganizationid int not null auto_increment primary key,
organizationid int not null,
foreign key (organizationid) references organizations(organizationid),
name text,
websiteurl text,
documentsurl text,
scriptname text,
dbpopulated bool not null
);

create table documents(
documentid int not null auto_increment primary key,
suborganizationid int not null,
foreign key (suborganizationid) references suborganizations(suborganizationid),
organizationid int not null,
foreign key (organizationid) references organizations(organizationid),
sourceurl text not null,
documentdate date not null,
scrapedate date not null,
name text not null,
documenttext text not null
);

create table words(
wordid int not null auto_increment primary key,
documentid int not null,
foreign key (documentid) references documents(documentid),
suborganizationid int not null,
foreign key (suborganizationid) references suborganizations(suborganizationid),
organizationid int not null,
foreign key (organizationid) references organizations(organizationid),
word varchar(127) not null,
frequency int not null
);

create table searches(
searchid int not null auto_increment primary key,
searchterm varchar(255) not null,
searchdt datetime not null,
organizationid int not null,
foreign key (organizationid) references organizations(organizationid)
);


INSERT INTO organizations VALUES (1,'Brighton','town'),(2,'Brockport','village'),(3,'Chili','town'),(4,'Churchville','village'),(5,'Clarkson','town'),(6,'East Rochester','village/town'),(7,'Fairport','village'),(8,'Gates','town'),(9,'Greece','town'),(10,'Hamlin','town'),(11,'Henrietta','town'),(12,'Hilton','village'),(13,'Honeoye Falls','village'),(14,'Irondequoit','town'),(15,'Mendon','town'),(16,'Ogden','town'),(17,'Parma','town'),(18,'Penfield','town'),(19,'Perinton','town'),(20,'Pittsford','town'),(22,'Riga','town'),(23,'Rochester','city'),(24,'Rush','town'),(25,'Scottsville','village'),(26,'Spencerport','village'),(27,'Sweden','town'),(28,'Webster','town'),(29,'Webster','village'),(30,'Wheatland','town'),(31,'Monroe County','County');

INSERT INTO suborganizations VALUES (1,1,'Town of Brighton','http://www.townofbrighton.org/','http://www.townofbrighton.org/index.aspx?nid=78','simpledifscript',1),(2,2,'Village of Brockport','http://www.brockportny.org/','http://www.brockportny.org/html/government/minutes.html','simpledifscript',1),(3,3,'Town of Chili','http://www.townofchili.org/','http://www.townofchili.org/index.php?option=com_docman&task=cat_view&gid=322&Itemid=52','simpledifscript',1),(4,5,'Town of Clarkson','http://www.clarksonny.org/','http://www.clarksonny.org/html/minutes.html','simpledifscript',1),(5,4,'Town of Churchville','http://www.churchville.net/','','simpledifscript',0),(6,6,'Town of East Rochester','http://eastrochester.org/','http://eastrochester.org/government/board/content/documents/','simpledifscript',1),(7,7,'Village of Fairport','http://www.village.fairport.ny.us/','http://ecode360.com//documents/list/FA0327/quick?CFID=7998379&CFTOKEN=27833541#sub62893','simpledifscript',1),(8,8,'Town of Gates','http://www.townofgates.org/','http://www.townofgates.org/index.php?option=com_content&view=article&id=66&Itemid=87','simpledifscript',1),(9,9,'Town of Greece','http://greeceny.gov/','http://greeceny.gov/board-meetings-past','simpledifscript',0),(10,10,'Town of Hamlin','http://www.hamlinny.org/','http://www.hamlinny.org/Town_Board/index.html#minutes','simpledifscript',1),(11,12,'Village of Hilton','http://www.hiltonny.org/','http://www.hiltonny.org/html/trustees.html','simpledifscript',1),(12,13,'Village of Honeoye Falls','http://www.villageofhoneoyefalls.org/','http://www.villageofhoneoyefalls.org/archive_minutes.php','simpledifscript',1),(13,16,'Town of Ogden','http://www.ogdenny.com','http://www.ecode360.com/documents/list/OG0089/quick/-5','simpledifscript',1),(14,17,'Town of Parma','http://www.parmany.org/','http://www.parmany.org/Town-Boards/','simpledifscript',1),(15,18,'Town of Penfield','http://www.penfield.org/','http://www.penfield.org/index.php?pr=Town_Board_Agendas','simpledifscript',1),(16,19,'Town of Perinton','http://www.perinton.org/','http://www.perinton.org/Boards/TwnBrd/twnbdAgd/','simpledifscript',1),(17,20,'Town of Pitsford','http://townofpittsford.org/','http://townofpittsford.org/home-tbminutes','simpledifscript',0);


