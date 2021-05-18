-- taper "SOURCE refDuScript" pour le lancer
CREATE DATABASE NoodleBDD;
USE NoodleBDD;
CREATE TABLE IF NOT EXISTS Users (userid INTEGER, pseudo VARCHAR(30), mdp VARCHAR(60), mail VARCHAR(40), birthdate VARCHAR(10), admin INTEGER, visibilite INTEGER);
CREATE TABLE IF NOT EXISTS Publications (id INTEGER, userid INTEGER, nomArticle VARCHAR(50), extensionArticle VARCHAR(10), nomApercu VARCHAR(50), extensionApercu VARCHAR(10), description TEXT, date DATETIME, visibilite INTEGER);
CREATE TABLE IF NOT EXISTS Abonnements (abonne VARCHAR(30), abonnement VARCHAR(30));
