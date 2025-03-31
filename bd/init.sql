DROP TABLE IF EXISTS Commande;
DROP TABLE IF EXISTS Participation;
DROP TABLE IF EXISTS Commentaire;
DROP TABLE IF EXISTS Actualite;
DROP TABLE IF EXISTS Evenement;
DROP TABLE IF EXISTS Image;
DROP TABLE IF EXISTS Produit;
DROP TABLE IF EXISTS Utilisateur;

CREATE TABLE Utilisateur(
	id_user INT,
	mail VARCHAR(50) NOT NULL,
	mdp VARCHAR(255) NOT NULL,
	permission INT NOT NULL,
	nom VARCHAR(50) NOT NULL,
	prenom VARCHAR(50) NOT NULL,
	PRIMARY KEY(id_user)
);

CREATE TABLE Produit(
	id_produit INT,
	titre_produit VARCHAR(50) NOT NULL,
	description_produit TEXT NOT NULL,
	date_produit TIMESTAMP NOT NULL,
	couleur VARCHAR(50) NOT NULL,
	taille VARCHAR(3) NOT NULL,
	stock INT NOT NULL,
	prix DECIMAL(4,2),
	id_user INT NOT NULL,
	PRIMARY KEY(id_produit),
	FOREIGN KEY(id_user) REFERENCES Utilisateur(id_user)
);

CREATE TABLE Image(
	id_image INT,
	nom_image VARCHAR(50) NOT NULL,
	chemin_image VARCHAR(50) NOT NULL,
	id_produit INT NOT NULL,
	PRIMARY KEY(id_image),
	FOREIGN KEY(id_produit) REFERENCES Produit(id_produit)
);

CREATE TABLE Evenement(
	id_event INT,
	titre_event VARCHAR(50) NOT NULL,
	date_debut TIMESTAMP NOT NULL,
	date_fin TIMESTAMP,
	adresse VARCHAR(50) NOT NULL,
	description TEXT NOT NULL,
	prix DECIMAL(4,2),
	id_user INT NOT NULL,
	PRIMARY KEY(id_event),
	FOREIGN KEY(id_user) REFERENCES Utilisateur(id_user)
);

CREATE TABLE Actualite(
	id_article INT,
	titre_article VARCHAR(50) NOT NULL,
	contenu TEXT NOT NULL,
	date_publication TIMESTAMP NOT NULL,
	id_user INT NOT NULL,
	PRIMARY KEY(id_article),
	FOREIGN KEY(id_user) REFERENCES Utilisateur(id_user)
);

CREATE TABLE Commentaire(
	id_commentaire INT,
	texte TEXT NOT NULL,
	date_commentaire TIMESTAMP NOT NULL,
	id_user INT NOT NULL,
	id_event INT NOT NULL,
	PRIMARY KEY(id_commentaire),
	FOREIGN KEY(id_user) REFERENCES Utilisateur(id_user),
	FOREIGN KEY(id_event) REFERENCES Evenement(id_event)
);

CREATE TABLE Participation(
	id_user INT,
	id_event INT,
	date_inscription TIMESTAMP NOT NULL,
	PRIMARY KEY(id_user, id_event),
	FOREIGN KEY(id_user) REFERENCES Utilisateur(id_user),
	FOREIGN KEY(id_event) REFERENCES Evenement(id_event)
);

CREATE TABLE Commande(
	id_user INT,
	id_produit INT,
	quantite INT NOT NULL,
	numero_commande INT NOT NULL,
	PRIMARY KEY(id_user, id_produit),
	FOREIGN KEY(id_user) REFERENCES Utilisateur(id_user),
	FOREIGN KEY(id_produit) REFERENCES Produit(id_produit)
);