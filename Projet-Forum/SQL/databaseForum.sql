DROP DATABASE IF EXISTS forum;
SET NAMES utf8mb4;
CREATE DATABASE forum;
USE forum;

CREATE TABLE Rank (
  idRank INT NOT NULL AUTO_INCREMENT,
  libelle VARCHAR(64),
  PRIMARY KEY (idRank)
);

CREATE TABLE  Categorie (
  idCategorie INT NOT NULL AUTO_INCREMENT,
  nom VARCHAR(64),
  PRIMARY KEY (idCategorie)
);

CREATE TABLE Users (
	idUser INT NOT NULL AUTO_INCREMENT,
	username VARCHAR(64) NOT NULL,
  password VARCHAR(64) NOT NULL,
  email VARCHAR(128) NOT NULL,
  avatar VARCHAR(64),
  id_Rank INT NOT NULL,
  PRIMARY KEY (idUser),
  FOREIGN KEY (id_Rank) REFERENCES Rank (idRank)
);

CREATE TABLE Thread (
	idThread INT NOT NULL AUTO_INCREMENT,
	title VARCHAR(128),
  auteur_id INT NOT NULL,
  categorie_id INT NOT NULL,
  content TEXT NOT NULL,
  date_creation DATE,
  isClosed BOOLEAN DEFAULT FALSE,
  PRIMARY KEY (idThread),
  FOREIGN KEY (auteur_id) REFERENCES Users(idUser),
  FOREIGN KEY (categorie_id) REFERENCES Categorie(idCategorie)
);

CREATE TABLE Commentaire (
	idCommentaire INT NOT NULL AUTO_INCREMENT,
  auteur_id INT,
	thread_id INT,
  content TEXT,
  date_creation DATE,
  PRIMARY KEY (idCommentaire),
  FOREIGN KEY (auteur_id) REFERENCES Users(idUser),
  FOREIGN KEY (thread_id) REFERENCES Thread(idThread)
);

CREATE TABLE Ban (
    id INT NOT NULL AUTO_INCREMENT,
    user_id INT,
    raison VARCHAR(255),
    date_debut TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES Users(idUser)
);

CREATE TABLE BanTemporaire (
    id INT NOT NULL AUTO_INCREMENT,
    user_id INT,
    raison VARCHAR(255),
    date_debut TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_fin DATE,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES Users(idUser)
);

INSERT INTO Rank (libelle) VALUES
('Administrateur'),
('Membre'),
('Banni'),
('BanniTemporaire');

INSERT INTO Categorie (nom) VALUES
('SLAM'),
('SISR'),
('Math'),
('Français'),
('CEJM'),
('Anglais'),
('Espagnol');

INSERT INTO Users (username, email, avatar, id_Rank) VALUES
('Robb', 'robimatic3000.game@gmail.com', "basic.jpg", 2),
('ADMIN', 'robin.chevalier5@gmail.com', "basic.jpg", 1),
('Observateur', 'xmar3ikx@gmail.com', "basic.jpg", 2),
('Troll', 'robin.chevalier@hscd.fr', "basic.jpg", 2),
('Lambda', 'odile.peuckert@orange.fr', "basic.jpg", 2);

INSERT INTO Thread (idThread, title, auteur_id, categorie_id, content, date_creation) VALUES
(1, "je n'en peux plus", 1, 1, "J'essaye de faire un formulaire php depuis 22ANS SAH", "2023-03-15"),
(2, "je n'en peux vraiment plus", 1, 1, "J'essaye de faire un formulaire php depuis 24ANS maintenant je pète un CABLE aidez svpp", "2023-02-15"),
(3, "SPANISH1", 3, 7, "SPANISHHHH", "2023-01-15"),
(4, "SPANISH2", 1, 7, "LE SPANO OUI", "2023-01-15"),   
(5, "SPANISH3", 3, 7, "EFFECTIVEMENT J'SUIS UN GROS SPAN", "2017-03-15"),
(6, "SPANISH4", 1, 7, "JE PEUX PLUSSSS", "2015-03-15"),
(7, "LA BELLE VIE", 2, 2, "J'adore les VMs VRAIMENT les gars installez DEBIAN c'est INCRR", "1865-01-11") ;  

INSERT INTO Commentaire (auteur_id, thread_id, content, date_creation) VALUES
(2, 1, "Pourrais-tu mieux formuler s'il te plait ? Les mots tels que SAH ne sont pas des plus adaptés sur ce forum", "2023-03-22"),
(3, 1, "Moi j'ai beaucoup aimé la formulation je ne comprend pas ?", "2023-03-23"),
(1, 5, "Moi aussi mec j'ai remarqué àa y'a pas très longtemps", "2023-03-20");
