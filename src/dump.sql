CREATE DATABASE IF NOT EXISTS PM;
CREATE TABLE IF NOT EXISTS PM.ACCOUNT(
    ID INTEGER PRIMARY KEY,
    MAIL CHAR(50),
    SHA3 CHAR(255),
    SALT CHAR(255)
);
CREATE TABLE IF NOT EXISTS PM.CREDENZIALE(
    ID INTEGER PRIMARY KEY AUTO_INCREMENT,
    ACCOUNT_ID INTEGER,
    PASSWORD CHAR (255),
    SITO CHAR(255),
    MAIL CHAR(100),
    DATA DATETIME,
    FOREIGN KEY (ACCOUNT_ID) REFERENCES ACCOUNT(ID)
);
CREATE TABLE IF NOT EXISTS PM.SESSIONE(
    ID_SESSIONE CHAR(50) PRIMARY KEY,
    ACCOUNT_ID INTEGER,
    DATA_INIZIO DATETIME,
    TIMEOUT DATETIME,
    FOREIGN KEY (ACCOUNT_ID) REFERENCES ACCOUNT(ID)
);
CREATE TABLE IF NOT EXISTS PM.VERIFICA(
    ID INTEGER PRIMARY KEY AUTO_INCREMENT,
    DATA_RICHIESTA DATETIME,
    MAIL CHAR(255),
    SHA3 CHAR(255),
    SALT CHAR(255),
    TOKEN_AUTH INTEGER
);