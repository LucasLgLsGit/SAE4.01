<?php
require_once './app/core/Repository.php';
require_once './app/entities/Produit.php';

class ProduitRepository {
	private $pdo;

	public function __construct() {
		$this->pdo = Repository::getInstance()->getPDO();
	}

	public function findAll(): array {
		$stmt = $this->pdo->query('SELECT * FROM "Produit"');
		$produits = [];
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$produits[] = $this->createProduitFromRow($row);
		}
		return $produits;
	}

	private function createProduitFromRow(array $row): Produit
	{
		return new Produit($row['id_produit'], $row['nom_produit'], $row['description_produit'], $row['date_ajout'], $row['couleur'], $row['taille'], $row['stock'], $row['prix'], $row['id_user']);
	}

	public function create(Produit $produit): bool {
		$stmt = $this->pdo->prepare('INSERT INTO "Produit" (nom_produit, description_produit, date_ajout, couleur, taille, stock, prix, id_user) VALUES (:nom_produit, :description_produit, :date_ajout, :couleur, :taille, :stock, :prix, :id_user)');
		return $stmt->execute([
			'nom_produit' => $produit->getNom_produit(),
			'description_produit' => $produit->getDescription_produit(),
			'date_ajout' => $produit->getDate_ajout(),
			'couleur' => $produit->getCouleur(),
			'taille' => $produit->getTaille(),
			'stock' => $produit->getStock(),
			'prix' => $produit->getPrix(),
			'id_user' => $produit->getId_user()
		]);
	}

	public function update(User $produit): bool {
		$stmt = $this->pdo->prepare('UPDATE "Produit" SET nom_produit = :newnom_produit, description_produit = :newdescription_produit, date_ajout = :newdate_ajout, couleur = :newcouleur, taille = :newtaille, stock = :newstock, prix = :newprix WHERE id_produit = :id_produit');
		return $stmt->execute([
			'id_produit' => $produit->getId_produit(),
			'newnom_produit' => $produit->getNom_produit(),
			'newdescription_produit' => $produit->getDescription_produit(),
			'newdate_ajout' => $produit->getDate_ajout(),
			'newcouleur' => $produit->getCouleur(),
			'newtaille' => $produit->getTaille(),
			'newstock' => $produit->getStock(),
			'newprix' => $produit->getPrix()
		]);
	}

	public function findById(int $id): ?Produit {
		$stmt = $this->pdo->prepare('SELECT * FROM "Produit" WHERE id_produit = :id_produit');
		$stmt->execute(['id_produit' => $id]);
		$produit = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($produit) {
			return $this->createProduitFromRow($produit);
		}
		return null;
	}
}