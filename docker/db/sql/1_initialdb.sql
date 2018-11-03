CREATE TABLE Articles
(
    id serial PRIMARY KEY,
    title varchar(100) DEFAULT '' NOT NULL,
    body TEXT DEFAULT '' NOT NULL,
    created_at timestamp with time zone
);