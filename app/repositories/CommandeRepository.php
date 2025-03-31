<?php

class CommandeRepository
{

	private $pdo;

	public function __construct() {
		$this->pdo = Repository::getInstance()->getPDO();
	}

	public function findAll() {
		$sql = "SELECT * FROM commande";
		$stmt = $this->pdo->query($sql);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function create($id_user, $id_produit, $quantite, $numero_commande) {
		$sql = "INSERT INTO commande (id_user, id_produit, quantite, numero_commande) VALUES (:id_user, :id_produit, :quantite, :numero_commande)";
		$stmt = $this->pdo->prepare($sql);
		return $stmt->execute([
			'id_user' => $id_user,
			'id_produit' => $id_produit,
			'quantite' => $quantite,
			'numero_commande' => $numero_commande
		]);
	}

	public function update($id_user, $id_produit, $quantite, $numero_commande) {
		$sql = "UPDATE commande SET quantite = :quantite, numero_commande = :numero_commande WHERE id_user = :id_user AND id_produit = :id_produit";
		$stmt = $this->pdo->prepare($sql);
		return $stmt->execute([
			'quantite' => $quantite,
			'numero_commande' => $numero_commande,
			'id_user' => $id_user,
			'id_produit' => $id_produit
		]);
	}

	public function findById($id_user, $id_produit) {
		$sql = "SELECT * FROM commande WHERE id_user = :id_user AND id_produit = :id_produit";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute(['id_user' => $id_user, 'id_produit' => $id_produit]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function delete($id_user, $id_produit) {
		$sql = "DELETE FROM commande WHERE id_user = :id_user AND id_produit = :id_produit";
		$stmt = $this->pdo->prepare($sql);
		return $stmt->execute(['id_user' => $id_user, 'id_produit' => $id_produit]);
	}
}
?>