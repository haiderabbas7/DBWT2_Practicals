CREATE USER dev WITH PASSWORD 'dbwt2';

CREATE DATABASE abalo
    WITH ENCODING = 'UTF8';

CREATE SCHEMA IF NOT EXISTS public;

CREATE TABLE ab_testdata(
                            id INT8 PRIMARY KEY,
                            ab_testname VARCHAR(80) NOT NULL UNIQUE
);

INSERT INTO ab_testdata (id,ab_testname) VALUES
                                             (1,'Fotokamera'),(2,'Blitzlicht');

DELETE FROM ab_article;
DELETE FROM ab_user;
