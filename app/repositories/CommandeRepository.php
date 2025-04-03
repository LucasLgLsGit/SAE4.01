<?php

require_once './app/core/Repository.php';
require_once './app/entities/Commande.php';

class CommandeRepository
{
	private $pdo;

	public function __construct() {
		$this->pdo = Repository::getInstance()->getPDO();
	}

	public function findAll(): array {
		$stmt = $this->pdo->query('SELECT * FROM "commande"');
		$commandes = [];
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$commandes[] = $this->createCommandeFromRow($row);
		}
		return $commandes;
	}


	public function create(array $data)
	{
		$errors = [];
		if (empty($data['id_user'])) $errors[] = "L'identifiant utilisateur (id_user) est requis !";
		if (empty($data['id_produit'])) $errors[] = "L'identifiant produit (id_produit) est requis !";
		if (empty($data['quantite']) || $data['quantite'] <= 0) $errors[] = "La quantité doit être supérieure à 0.";

		if (!empty($errors)) {
			throw new Exception(implode(', ', $errors));
		}

		$stmt = $this->pdo->prepare('
			INSERT INTO Commande (id_user, id_produit, quantite) 
			VALUES (:id_user, :id_produit, :quantite)
		');
		$stmt->execute([
			'id_user' => $data['id_user'],
			'id_produit' => $data['id_produit'],
			'quantite' => $data['quantite']
		]);
	}

	private function createCommandeFromRow(array $row): Commande {
		return new Commande(
			$row['id_user'],
			$row['id_produit'],
			$row['quantite'],
			$row['numero_commande']
		);
	}

	public function update(array $data): bool {
		$errors = [];
		if (empty($data['id_user'])) {
			$errors[] = "L'identifiant utilisateur (id_user) est requis !";
		}
		if (empty($data['id_produit'])) {
			$errors[] = "L'identifiant produit (id_produit) est requis !";
		}
		if (empty($data['quantite']) || $data['quantite'] < 0) {
			$errors[] = "La quantité doit être supérieure à 0";
		}
		if (!empty($errors)) {
			throw new Exception(implode(', ', $errors));
		}
	
		// Récupérer la commande existante pour obtenir le numero_commande
		$existingCommande = $this->findById($data['id_user'], $data['id_produit']);
		$numero_commande = $existingCommande ? $existingCommande->getNumeroCommande() : 0;
	
		$commande = new Commande(
			$data['id_user'],
			$data['id_produit'],
			$data['quantite'],
			$numero_commande
		);
	
		$stmt = $this->pdo->prepare('
			UPDATE "commande" 
			SET quantite = :quantite 
			WHERE id_user = :id_user AND id_produit = :id_produit
		');
		$success = $stmt->execute([
			'quantite' => $commande->getQuantite(),
			'id_user' => $commande->getIdUser(),
			'id_produit' => $commande->getIdProduit()
		]);
	
		if (!$success) {
			throw new Exception("La mise à jour de la commande a échoué.");
		}
	
		return true;
	}

	public function delete(int $id_user, int $id_produit): bool {
		if (empty($id_user)) {
			throw new Exception("L'identifiant utilisateur (id_user) est requis !");
		}
		if (empty($id_produit)) {
			throw new Exception("L'identifiant produit (id_produit) est requis !");
		}
		$stmt = $this->pdo->prepare('DELETE FROM "commande" WHERE id_user = :id_user AND id_produit = :id_produit');
		$success = $stmt->execute(['id_user' => $id_user, 'id_produit' => $id_produit]);
		if (!$success) {
			throw new Exception("La suppression de la commande a échoué.");
		}
		return true;
	}

	public function findById(int $id_user, int $id_produit): ?Commande {
		$stmt = $this->pdo->prepare('SELECT * FROM "commande" WHERE id_user = :id_user AND id_produit = :id_produit');
		$stmt->execute(['id_user' => $id_user, 'id_produit' => $id_produit]);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row ? $this->createCommandeFromRow($row) : null;
	}
}
?>
