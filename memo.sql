CREATE DATABASE mini_bbs;
USE mini_bbs;

CREATE TABLE members(
    id int auto_increment,
    name varchar(255),
    email varchar(255),
    password varchar(100),
    picture varchar(255),
    created datetime,
    modified timestamp,
    primary key (id)
);
show columns from members;

CREATE TABLE posts(
    id int auto_increment,
    message text,
    member_id int,
    reply_post_id int,
    created datetime,
    modified timestamp,
    primary key (id)
);
show columns from posts;