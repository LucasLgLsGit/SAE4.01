DROP TABLE IF EXISTS Commande;
DROP TABLE IF EXISTS Participation;
DROP TABLE IF EXISTS Commentaire;
DROP TABLE IF EXISTS Actualite;
DROP TABLE IF EXISTS Evenement;
DROP TABLE IF EXISTS Image;
DROP TABLE IF EXISTS Produit;
DROP TABLE IF EXISTS Utilisateur;
DROP TABLE IF EXISTS Question;

CREATE TABLE Utilisateur(
	id_user SERIAL,
	mail VARCHAR(50) NOT NULL,
	mdp VARCHAR(255) NOT NULL,
	permission INT NOT NULL,
	nom VARCHAR(50) NOT NULL,
	prenom VARCHAR(50) NOT NULL,
	boolean newsletter NOT NULL,
	PRIMARY KEY(id_user)
);

CREATE TABLE Produit(
	id_produit SERIAL,
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
	id_image SERIAL,
	nom_image VARCHAR(50) NOT NULL,
	id_produit INT NOT NULL,
	PRIMARY KEY(id_image),
	FOREIGN KEY(id_produit) REFERENCES Produit(id_produit)
);

CREATE TABLE Evenement(
	id_event SERIAL,
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
	id_article SERIAL,
	titre_article VARCHAR(255) NOT NULL,
	contenu TEXT NOT NULL,
	date_publication TIMESTAMP NOT NULL,
	id_user INT NOT NULL,
	PRIMARY KEY(id_article),
	FOREIGN KEY(id_user) REFERENCES Utilisateur(id_user)
);

CREATE TABLE Commentaire(
	id_commentaire SERIAL,
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
	numero_commande SERIAL,
	PRIMARY KEY(id_user, id_produit),
	FOREIGN KEY(id_user) REFERENCES Utilisateur(id_user),
	FOREIGN KEY(id_produit) REFERENCES Produit(id_produit)
);

CREATE TABLE Question(
	id_question SERIAL,
	question TEXT NOT NULL,
	reponse TEXT NOT NULL,
	PRIMARY KEY(id_question)
);