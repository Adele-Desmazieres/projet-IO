-- taper "SOURCE refDuScript" pour le lancer
CREATE DATABASE IO_TEST;
USE IO_TEST;
CREATE TABLE IF NOT EXISTS Users (pseudo VARCHAR(30), mdp VARCHAR(60), mail VARCHAR(30), birthdate VARCHAR(10), userid INTEGER);
CREATE TABLE IF NOT EXISTS Publications (nom VARCHAR(50), description TEXT, type VARCHAR(10), size FLOAT, auteur INTEGER, date DATETIME, id INTEGER);
CREATE TABLE IF NOT EXISTS Abonnements (abonne VARCHAR(30), abonnement VARCHAR(30));
CREATE TABLE IF NOT EXISTS admin (id INTEGER);
CREATE TABLE IF NOT EXISTS prive (id INTEGER);
