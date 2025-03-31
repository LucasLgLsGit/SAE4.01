<?php
require_once './app/core/Repository.php';
require_once './app/entities/Produit.php';

class ProduitRepository {
	private $pdo;

	public function __construct() {
		$this->pdo = Repository::getInstance()->getPDO();
	}

	public function findAll(): array {
		$stmt = $this->pdo->query('SELECT * FROM "produit"');
		$produits = [];
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$produits[] = $this->createProduitFromRow($row);
		}
		return $produits;
	}

	private function createProduitFromRow(array $row): Produit
	{
		return new Produit(	$row['id_produit'], 
							$row['titre_produit'], 
							$row['description_produit'], 
							new DateTime($row['date_produit']), 
							$row['couleur'], $row['taille'], 
							$row['stock'], 
							$row['prix'], 
							$row['id_user']);
	}

	public function create(Produit $produit): bool {
		$stmt = $this->pdo->prepare('INSERT INTO "produit" (titre_produit, description_produit, date_produit, couleur, taille, stock, prix, id_user) VALUES (:titre_produit, :description_produit, :date_produit, :couleur, :taille, :stock, :prix, :id_user)');
		return $stmt->execute([
			'titre_produit' => $produit->getTitre_produit(),
			'description_produit' => $produit->getDescription_produit(),
			'date_produit' => $produit->getDate_produit()->format('Y-m-d H:i:s'),
			'couleur' => $produit->getCouleur(),
			'taille' => $produit->getTaille(),
			'stock' => $produit->getStock(),
			'prix' => $produit->getPrix(),
			'id_user' => $produit->getId_user()
		]);
	}

	public function update(Produit $produit): bool {
		$stmt = $this->pdo->prepare('UPDATE "produit" SET titre_produit = :newtitre_produit, description_produit = :newdescription_produit, date_produit = :newdate_produit, couleur = :newcouleur, taille = :newtaille, stock = :newstock, prix = :newprix WHERE id_produit = :id_produit');
		return $stmt->execute([
			'id_produit' => $produit->getId_produit(),
			'newtitre_produit' => $produit->getTitre_produit(),
			'newdescription_produit' => $produit->getDescription_produit(),
			'newdate_produit' => $produit->getDate_produit(),
			'newcouleur' => $produit->getCouleur(),
			'newtaille' => $produit->getTaille(),
			'newstock' => $produit->getStock(),
			'newprix' => $produit->getPrix()
		]);
	}

	public function findById(int $id): ?Produit {
		$stmt = $this->pdo->prepare('SELECT * FROM "produit" WHERE id_produit = :id_produit');
		$stmt->execute(['id_produit' => $id]);
		$produit = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($produit) {
			return $this->createProduitFromRow($produit);
		}
		return null;
	}

	public function delete(int $id): bool {
		$stmt = $this->pdo->prepare('DELETE FROM "produit" WHERE id_produit = :id_produit');
		return $stmt->execute(['id_produit' => $id]);
	}
}