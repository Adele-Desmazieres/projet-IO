-- taper "SOURCE refDuScript" pour le lancer
CREATE DATABASE IO_TEST;
USE IO_TEST;
CREATE TABLE IF NOT EXISTS Users (userid INTEGER, pseudo VARCHAR(30), mdp VARCHAR(60), mail VARCHAR(40), birthdate VARCHAR(10), admin INTEGER, visibilite INTEGER);
CREATE TABLE IF NOT EXISTS Publications (userid INTEGER, nom VARCHAR(50), extension VARCHAR(10), nomApercu VARCHAR(50), extensionApercu VARCHAR(10), description TEXT, id INTEGER, date DATETIME, visibilite INTEGER);
CREATE TABLE IF NOT EXISTS Abonnements (abonne VARCHAR(30), abonnement VARCHAR(30));
