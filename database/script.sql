CREATE USER dev WITH PASSWORD 'dbwt2';

CREATE DATABASE abalo
    WITH ENCODING = 'UTF8';

CREATE SCHEMA IF NOT EXISTS public;

CREATE TABLE testdata(
                            id INT8 PRIMARY KEY,
                            testname VARCHAR(80) NOT NULL UNIQUE
);

INSERT INTO testdata (id,testname) VALUES
                                             (1,'Fotokamera'),(2,'Blitzlicht');

DELETE FROM article;
DELETE FROM user;

DROP TABLE testdata;
