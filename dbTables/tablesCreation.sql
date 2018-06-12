/*
  Author: Quentin Guenther, Kianna Dyck, Jen Shin, Bessy Torres-Miller
  Date: 04/10/2018
  Name: tablesCreation.sql
  Purpose: This file has the sql statements used to create the db tables

 */

/* Create team Table */
DROP TABLE IF EXISTS team;
CREATE TABLE IF NOT EXISTS team
(
    teamId int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    team_name VARCHAR(100) UNIQUE,
    date_created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_modified timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

/* Create user Table */
DROP TABLE IF EXISTS user;
CREATE TABLE IF NOT EXISTS `user`
(
    userId int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    email VARCHAR(150) UNIQUE,
    password VARCHAR(40),
    isAdmin tinyInt,
    teamId int NOT NULL,
	  hasChangedTeam tinyInt NOT NULL DEFAULT 0,
    date_created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_modified timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT FK_teamUser FOREIGN KEY (teamId) REFERENCES team(teamId)
);

/*Create post Table  */
DROP TABLE IF EXISTS post;
CREATE TABLE IF NOT EXISTS post
(
    postId int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(50) NOT NULL,
    content longtext NOT NULL,
    isActive tinyInt,
    date_created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_modified timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    userId int NOT NULL,
    teamId int NOT NULL,
    parent_id int,
    CONSTRAINT FK_userPost FOREIGN KEY (userId) REFERENCES user(userId),
    CONSTRAINT FK_teamPost FOREIGN KEY (teamId) REFERENCES team(teamId),
    CONSTRAINT FK_postPost FOREIGN KEY (parent_id) REFERENCES post(parent_id)
);

/*Create postVotes Table*/
DROP TABLE IF EXISTS postVotes;
CREATE TABLE IF NOT EXISTS postVotes
(
    userId int NOT NULL,
    parent_id int NOT NULL,
	  points int,
    date_created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_modified timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY(userId, parent_id),
    CONSTRAINT FK_userPostVotes FOREIGN KEY (userId) REFERENCES user(userId),
	  CONSTRAINT FK_postPostVotes FOREIGN KEY (parent_id) REFERENCES post(parent_id)
);

/* Create Admin user */
INSERT INTO user (email, password, isAdmin) VALUES ('admin@greenriver.edu', sha1('admin'), 1);