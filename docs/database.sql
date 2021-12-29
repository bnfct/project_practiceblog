CREATE TABLE pb_articles (
   id int AUTO_INCREMENT PRIMARY KEY NOT NULL,
   title varchar(255) NOT NULL,
   summary varchar(255),
   published DATETIME NOT NULL,
   category INT NOT NULL,
   author INT NOT NULL,
   picture varchar(255),
   content TEXT NOT NULL,
   link varchar(255) NOT NULL,
   hidden INT NOT NULL
);
INSERT INTO pb_articles (title, summary, published, category, author, picture, content, link, hidden) VALUES ("asdtitle1","asdummary","2021-12-23 20:41:01",1,1,"","asdasdasdtulajdonképpenvalami","asdtitle1",0);
INSERT INTO pb_articles (title, summary, published, category, author, picture, content, link, hidden) VALUES ("asdtitle2","asdummary","2021-12-23 20:43:01",2,1,"","asdasdasdtulajdonképpenvalami","asdtitle2",0);
CREATE TABLE pb_categories (
   id int AUTO_INCREMENT PRIMARY KEY NOT NULL,
   title varchar(255) NOT NULL,
   summary varchar(255),
   picture varchar(255),
   link varchar(255) NOT NULL
);
INSERT INTO pb_categories (title, summary, picture, link) VALUES ("asd1","summarika","","asd1");
INSERT INTO pb_categories (title, summary, picture, link) VALUES ("asd2","summarika","","asd2");
CREATE TABLE pb_users (
   id int AUTO_INCREMENT PRIMARY KEY NOT NULL,
   username varchar(255) NOT NULL,
   password TEXT NOT NULL,
   displayname varchar(255) NOT NULL,
   email varchar(255) NOT NULL,
   profilepic varchar(255),
   motto TEXT,
   role INT NOT NULL,
   active INT NOT NULL,
   regdate DATETIME NOT NULL,
   code varchar(255) NOT NULL
);
INSERT INTO pb_users (username, password, displayname, email, profilepic, motto, role, active, regdate) VALUES ("benfact","123","Ben","asd@asd.com","","lol",1,1,"2021-12-23 20:41:01");
CREATE TABLE pb_base (
   id int AUTO_INCREMENT PRIMARY KEY NOT NULL,
   sitename varchar(255) NOT NULL,
   siteversion varchar(255) NOT NULL,
   sitefooter varchar(255) NOT NULL
);
INSERT INTO pb_base (sitename, siteversion, sitefooter) VALUES ("PracticeBlog","v0.10","Created By Benfact 2021");

