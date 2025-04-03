<?php
require_once './app/core/Repository.php';
require_once './app/entities/Produit.php';

class ProduitRepository {
	private $pdo;

	public function __construct() {
		$this->pdo = Repository::getInstance()->getPDO();
	}

	public function findAll(): array {
		$stmt = $this->pdo->query('SELECT p.*, nom_image FROM "produit" p LEFT JOIN "image" i ON p.id_produit = i.id_produit');
		$produits = [];
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$produits[] = $this->createProduitFromRow($row);
		}
		return $produits;
	}

	public function create(array $data): Produit {
		$errors = [];
	
		if (empty($data['nom_produit'])) {
			$errors[] = "Le nom du produit est requis !";
		}
		if (empty($data['description_produit'])) {
			$errors[] = "La description du produit est requise !";
		}
		if (empty($data['prix'])) {
			$errors[] = "Le prix du produit est requis !";
		}
		if (empty($data['stock'])) {
			$errors[] = "Le stock du produit est requis !";
			}
		if (empty($data['id_user'])) {
			$errors[] = "L'identifiant utilisateur (id_user) est requis !";
		}
		if (!empty($errors)) {
			throw new Exception(implode(', ', $errors));
		}
	
		$stmt = $this->pdo->prepare('
			INSERT INTO produit (titre_produit, description_produit, date_produit, couleur, taille, stock, prix, id_user)
			VALUES (:titre_produit, :description_produit, :date_produit, :couleur, :taille, :stock, :prix, :id_user)
		');
		$stmt->bindValue(':titre_produit', $data['titre_produit']);
		$stmt->bindValue(':description_produit', $data['description_produit']);
		$stmt->bindValue(':date_produit', $data['date_produit']);
		$stmt->bindValue(':couleur', $data['couleur']);
		$stmt->bindValue(':taille', $data['taille']);
		$stmt->bindValue(':stock', $data['stock']);
		$stmt->bindValue(':prix', (float) $data['prix'], PDO::PARAM_STR);
		$stmt->bindValue(':id_user', $data['id_user']);
	
		if (!$stmt->execute()) {
			throw new Exception("Erreur lors de l'insertion dans la base de données.");
		}
	
		return new Produit(
			$this->pdo->lastInsertId(),
			$data['nom_produit'],
			$data['description_produit'],
			new DateTime(),
			'',
			'',
			$data['stock'],
			$data['prix'],
			0
		);
	}

	private function createProduitFromRow(array $row): Produit {
		return new Produit(
			$row['id_produit'],
			$row['titre_produit'],
			$row['description_produit'],
			new DateTime($row['date_produit']),
			$row['couleur'],
			$row['taille'],
			$row['stock'],
			$row['prix'],
			$row['id_user'],
			$row['nom_image'] ?? null
		);
	}

	public function update(array $data): Produit {
		$errors = [];

		if (empty($data['id_produit'])) {
			$errors[] = "L'identifiant du produit est requis !";
		}
		if (empty($data['nom_produit'])) {
			$errors[] = "Le nom du produit est requis !";
		}
		if (empty($data['description_produit'])) {
			$errors[] = "La description du produit est requise !";
		}
		if (empty($data['date_ajout'])) {
			$errors[] = "La date d'ajout est requise !";
		}
		if (empty($data['couleur'])) {
			$errors[] = "La couleur du produit est requise !";
		}
		if (empty($data['taille'])) {
			$errors[] = "La taille du produit est requise !";
		}
		if (empty($data['stock'])) {
			$errors[] = "Le stock du produit est requis !";
		}
		if (empty($data['prix'])) {
			$errors[] = "Le prix du produit est requis !";
		}
		if (empty($data['id_user'])) {
			$errors[] = "L'identifiant utilisateur (id_user) est requis !";
		}

		if (!empty($errors)) {
			throw new Exception(implode(', ', $errors));
		}

		$data['date_produit'] = (new DateTime($data['date_produit']))->format('Y-m-d H:i:s');

		$produit = new Produit(
			(int) $data['id_produit'],
			$data['nom_produit'],
			$data['description_produit'],
			new DateTime($data['date_ajout']),
			$data['couleur'],
			$data['taille'],
			(int) $data['stock'],
			(float) $data['prix'],
			(int) $data['id_user']
		);

		$stmt = $this->pdo->prepare('
			UPDATE "produit" 
			SET titre_produit = :titre_produit, 
				description_produit = :description_produit, 
				date_produit = :date_produit, 
				couleur = :couleur, 
				taille = :taille, 
				stock = :stock, 
				prix = :prix 
			WHERE id_produit = :id_produit
		');

		$stmt->execute([
			'id_produit' => $produit->getId_produit(),
			'titre_produit' => $produit->getTitre_produit(),
			'description_produit' => $produit->getDescription_produit(),
			'date_produit' => $produit->getDate_produit()->format('Y-m-d H:i:s'),
			'couleur' => $produit->getCouleur(),
			'taille' => $produit->getTaille(),
			'stock' => $produit->getStock(),
			'prix' => $produit->getPrix()
		]);

		return $produit;
	}

	public function delete(int $id): bool {
		$stmt = $this->pdo->prepare('DELETE FROM "produit" WHERE id_produit = :id_produit');
		return $stmt->execute(['id_produit' => $id]);
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

	public function findByTitre(string $titre): ?Produit {
		$stmt = $this->pdo->prepare('SELECT * FROM "produit" WHERE LOWER(titre_produit) = LOWER(:titre_produit)');
		$stmt->execute(['titre_produit' => $titre]);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row ? $this->createProduitFromRow($row) : null;
	}
}
