<?php
require_once './app/core/Repository.php';
require_once './app/entities/Produit.php';

class ProduitRepository {
	private $pdo;

	public function __construct() {
		$this->pdo = Repository::getInstance()->getPDO();
	}

	public function findAll(): array {
		$stmt = $this->pdo->query('SELECT p.*, nom_image FROM "produit" p LEFT JOIN "image" i ON p.id_produit = i.id_produit ORDER BY id_produit ASC');
		$produits = [];
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$produits[] = $this->createProduitFromRow($row);
		}
		return $produits;
	}


	public function findByAttributes(string $titre_produit, string $taille, string $couleur): ?Produit {
		$stmt = $this->pdo->prepare('
			SELECT * FROM produit 
			WHERE LOWER(titre_produit) = LOWER(:titre_produit) 
			AND taille = :taille 
			AND couleur = :couleur
		');
		$stmt->execute([
			'titre_produit' => $titre_produit,
			'taille' => $taille,
			'couleur' => $couleur
		]);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
	
		return $row ? $this->createProduitFromRow($row) : null;
	}

	public function create(array $data): Produit {
		$errors = [];
	
		if (empty($data['titre_produit'])) {
			$errors[] = "Le titre du produit est requis !";
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
	
		$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt = $this->pdo->prepare('
			INSERT INTO produit (titre_produit, description_produit, date_produit, couleur, taille, stock, prix, id_user)
			VALUES (:titre_produit, :description_produit, :date_produit, :couleur, :taille, :stock, :prix, :id_user)
		');
		$stmt->bindValue(':titre_produit', $data['titre_produit']);
		$stmt->bindValue(':description_produit', $data['description_produit']);
		$stmt->bindValue(':date_produit', $data['date_produit']);
		$stmt->bindValue(':couleur', $data['couleur']);
		$stmt->bindValue(':taille', $data['taille']);
		$stmt->bindValue(':stock', $data['stock'], PDO::PARAM_INT);
		$stmt->bindValue(':prix', $data['prix'], PDO::PARAM_STR);
		$stmt->bindValue(':id_user', $data['id_user'],PDO::PARAM_INT);
	
		if (!$stmt->execute()) {
			throw new Exception("Erreur lors de l'insertion dans la base de données.");
		}
	
		return new Produit(
			$this->pdo->lastInsertId(),
			$data['titre_produit'],
			$data['description_produit'],
			new DateTime($data['date_produit']),
			$data['couleur'],
			$data['taille'],
			$data['stock'],
			$data['prix'],
			$data['id_user']
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

	public function update(array $data): Produit
    {
        $errors = [];
        if (empty($data['id_produit'])) {
            $errors[] = "L'identifiant du produit est requis !";
        }

        if (!empty($errors)) {
            throw new Exception(implode(', ', $errors));
        }

        $stmt = $this->pdo->prepare('SELECT * FROM "produit" WHERE id_produit = :id_produit');
        $stmt->execute(['id_produit' => $data['id_produit']]);
        $currentProduct = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$currentProduct) {
            throw new Exception("Produit avec l'ID {$data['id_produit']} non trouvé.");
        }

        $updatedData = [
            'id_produit' => (int) $data['id_produit'],
            'titre_produit' => $data['titre_produit'] ?? $currentProduct['titre_produit'],
            'description_produit' => $data['description_produit'] ?? $currentProduct['description_produit'],
            'date_produit' => $currentProduct['date_produit'], // On conserve la date existante
            'couleur' => $data['couleur'] ?? $currentProduct['couleur'],
            'taille' => $data['taille'] ?? $currentProduct['taille'],
            'stock' => isset($data['stock']) ? (int) $data['stock'] : $currentProduct['stock'],
            'prix' => isset($data['prix']) ? (float) $data['prix'] : $currentProduct['prix'],
            'id_user' => $currentProduct['id_user'] // On conserve l'id_user existant
        ];

        $stmt = $this->pdo->prepare('
            UPDATE "produit" 
            SET titre_produit = :titre_produit, 
                description_produit = :description_produit, 
                date_produit = :date_produit, 
                couleur = :couleur, 
                taille = :taille, 
                stock = :stock, 
                prix = :prix,
                id_user = :id_user
            WHERE id_produit = :id_produit
        ');

        $success = $stmt->execute([
            'id_produit' => $updatedData['id_produit'],
            'titre_produit' => $updatedData['titre_produit'],
            'description_produit' => $updatedData['description_produit'],
            'date_produit' => $updatedData['date_produit'],
            'couleur' => $updatedData['couleur'],
            'taille' => $updatedData['taille'],
            'stock' => $updatedData['stock'],
            'prix' => $updatedData['prix'],
            'id_user' => $updatedData['id_user']
        ]);

        if (!$success) {
            throw new Exception("Échec de la mise à jour du produit.");
        }

        return new Produit(
            $updatedData['id_produit'],
            $updatedData['titre_produit'],
            $updatedData['description_produit'],
            new DateTime($updatedData['date_produit']),
            $updatedData['couleur'],
            $updatedData['taille'],
            $updatedData['stock'],
            $updatedData['prix'],
            $updatedData['id_user']
        );
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
