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

	public function create(array $data): Commande {
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
		if (empty($data['numero_commande'])) {
			$errors[] = "Le numéro de commande ne peut pas être nul";
		}
		if (!empty($errors)) {
			throw new Exception(implode(', ', $errors));
		}
		$commande = new Commande(
			$data['id_user'],
			$data['id_produit'],
			$data['quantite'],
			$data['numero_commande']
		);
		$stmt = $this->pdo->prepare('
			INSERT INTO "commande" (id_user, id_produit, quantite, numero_commande) 
			VALUES (:id_user, :id_produit, :quantite, :numero_commande)
		');
		$success = $stmt->execute([
			'id_user' => $commande->getIdUser(),
			'id_produit' => $commande->getIdProduit(),
			'quantite' => $commande->getQuantite(),
			'numero_commande' => $commande->getNumeroCommande()
		]);
		if (!$success) {
			throw new Exception("La création de la commande a échoué.");
		}
		return $commande;
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
		if (empty($data['numero_commande'])) {
			$errors[] = "Le numéro de commande ne peut pas être nul";
		}
		if (!empty($errors)) {
			throw new Exception(implode(', ', $errors));
		}
		$commande = new Commande($data['id_user'], $data['id_produit'], $data['quantite'], $data['numero_commande']);
		$commandeRepo = new CommandeRepository();
		if (!$commandeRepo->update($commande)) {
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
