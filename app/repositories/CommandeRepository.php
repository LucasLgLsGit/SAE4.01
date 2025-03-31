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

	private function createCommandeFromRow(array $row): Commande {
		return new Commande(
			$row['id_user'],
			$row['id_produit'],
			$row['quantite'],
			$row['numero_commande']
		);
	}

	public function findById(int $id_user, int $id_produit): ?Commande {
		$stmt = $this->pdo->prepare('SELECT * FROM "commande" WHERE id_user = :id_user AND id_produit = :id_produit');
		$stmt->execute(['id_user' => $id_user, 'id_produit' => $id_produit]);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row ? $this->createCommandeFromRow($row) : null;
	}

	public function create(Commande $commande): bool {
		$stmt = $this->pdo->prepare('INSERT INTO "commande" (id_user, id_produit, quantite, numero_commande) VALUES (:id_user, :id_produit, :quantite, :numero_commande)');
		return $stmt->execute([
			'id_user' => $commande->getIdUser(),
			'id_produit' => $commande->getIdProduit(),
			'quantite' => $commande->getQuantite(),
			'numero_commande' => $commande->getNumeroCommande()
		]);
	}

	public function update(Commande $commande): bool {
		$stmt = $this->pdo->prepare('UPDATE "commande" SET quantite = :quantite, numero_commande = :numero_commande WHERE id_user = :id_user AND id_produit = :id_produit');
		return $stmt->execute([
			'id_user' => $commande->getIdUser(),
			'id_produit' => $commande->getIdProduit(),
			'quantite' => $commande->getQuantite(),
			'numero_commande' => $commande->getNumeroCommande()
		]);
	}

	public function delete(int $id_user, int $id_produit): bool {
		$stmt = $this->pdo->prepare('DELETE FROM "commande" WHERE id_user = :id_user AND id_produit = :id_produit');
		return $stmt->execute(['id_user' => $id_user, 'id_produit' => $id_produit]);
	}
}
?>