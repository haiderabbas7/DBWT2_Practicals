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

DELETE FROM article WHERE id > 30;

DROP TABLE article_has_category;
DROP TABLE articlecategory;
DROP TABLE article;
DROP TABLE benutzer;

DROP TABLE migrations;

SELECT setval('article_id_seq', (SELECT MAX(id) FROM article));

INSERT INTO shoppingcart (id, creator_id, createdate)
VALUES
    (1, 1, now())

//shoppingcart_id

SELECT * FROM article
    JOIN shoppingcart_item
        ON article.id = shoppingcart_item.article_id
    WHERE shoppingcart_id = 1
