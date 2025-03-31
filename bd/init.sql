CREATE TABLE Actualité(
	id_article INT,
	titre_article VARCHAR(50) NOT NULL,
	contenu TEXT NOT NULL,
	date_publication DATETIME NOT NULL,
	id_user INT NOT NULL,
	PRIMARY KEY(id_article)
);

CREATE TABLE Commentaire(
	id_comm INT,
	id_user INT NOT NULL,
	id_event INT NOT NULL,
	texte TEXT NOT NULL,
	note INT NOT NULL,
	date_comm DATETIME NOT NULL,
	PRIMARY KEY(id_comm)
);

CREATE TABLE Stock(
	id_stock INT,
	id_produit INT NOT NULL,
	couleur VARCHAR(50) NOT NULL,
	taille VARCHAR(2) NOT NULL,
	stock INT NOT NULL,
	PRIMARY KEY(id_stock)
);

CREATE TABLE Produit(
	id_produit INT,
	titre_produit VARCHAR(50) NOT NULL,
	description_produit TEXT NOT NULL,
	date_produit DATETIME NOT NULL,
	id_user INT NOT NULL,
	id_stock INT NOT NULL,
	PRIMARY KEY(id_produit),
	FOREIGN KEY(id_stock) REFERENCES Stock(id_stock)
);

CREATE TABLE Commande(
	id_vente INT,
	prix DECIMAL(5,2) NOT NULL,
	statut VARCHAR(50) NOT NULL,
	id_prod INT NOT NULL,
	id_user INT NOT NULL,
	id_produit INT NOT NULL,
	PRIMARY KEY(id_vente),
	FOREIGN KEY(id_produit) REFERENCES Produit(id_produit)
);

CREATE TABLE Evénement(
	id_event INT,
	titre_event VARCHAR(50) NOT NULL,
	date_debut DATETIME NOT NULL,
	date_fin DATETIME,
	adresse VARCHAR(50) NOT NULL,
	description TEXT NOT NULL,
	prix DECIMAL(4,2),
	id_user INT NOT NULL,
	id_comm INT NOT NULL,
	PRIMARY KEY(id_event),
	FOREIGN KEY(id_comm) REFERENCES Commentaire(id_comm)
);

CREATE TABLE Utilisateur(
	id_user INT,
	mail VARCHAR(50) NOT NULL,
	mdp VARCHAR(50) NOT NULL,
	permission BYTE NOT NULL,
	nom VARCHAR(50) NOT NULL,
	prénom VARCHAR(50) NOT NULL,
	id_event INT NOT NULL,
	id_produit INT NOT NULL,
	id_article INT NOT NULL,
	id_vente INT NOT NULL,
	id_comm INT NOT NULL,
	PRIMARY KEY(id_user),
	FOREIGN KEY(id_event) REFERENCES Evénement(id_event),
	FOREIGN KEY(id_produit) REFERENCES Produit(id_produit),
	FOREIGN KEY(id_article) REFERENCES Actualité(id_article),
	FOREIGN KEY(id_vente) REFERENCES Commande(id_vente),
	FOREIGN KEY(id_comm) REFERENCES Commentaire(id_comm)
);

CREATE TABLE Participation(
	id_user_1 INT,
	id_event_1 INT,
	id_user INT NOT NULL,
	id_event INT NOT NULL,
	date_inscription DATETIME NOT NULL,
	PRIMARY KEY(id_user_1, id_event_1),
	FOREIGN KEY(id_user_1) REFERENCES Utilisateur(id_user),
	FOREIGN KEY(id_event_1) REFERENCES Evénement(id_event)
);