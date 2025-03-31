<?php

class CommandeRepository
{

	private $pdo;

	public function __construct() {
		$this->pdo = Repository::getInstance()->getPDO();
	}

	public function findAll() {
		$sql = "SELECT * FROM Commande";
		$stmt = $this->pdo->query($sql);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function findById($id_user, $id_produit) {
		$sql = "SELECT * FROM Commande WHERE id_user = :id_user AND id_produit = :id_produit";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute(['id_user' => $id_user, 'id_produit' => $id_produit]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function update($id_user, $id_produit, $quantite, $numero_commande) {
		$sql = "UPDATE Commande SET quantite = :quantite, numero_commande = :numero_commande WHERE id_user = :id_user AND id_produit = :id_produit";
		$stmt = $this->pdo->prepare($sql);
		return $stmt->execute([
			'quantite' => $quantite,
			'numero_commande' => $numero_commande,
			'id_user' => $id_user,
			'id_produit' => $id_produit
		]);
	}

	public function delete($id_user, $id_produit) {
		$sql = "DELETE FROM Commande WHERE id_user = :id_user AND id_produit = :id_produit";
		$stmt = $this->pdo->prepare($sql);
		return $stmt->execute(['id_user' => $id_user, 'id_produit' => $id_produit]);
	}

	public function create($id_user, $id_produit, $quantite, $numero_commande) {
		$sql = "INSERT INTO Commande (id_user, id_produit, quantite, numero_commande) VALUES (:id_user, :id_produit, :quantite, :numero_commande)";
		$stmt = $this->pdo->prepare($sql);
		return $stmt->execute([
			'id_user' => $id_user,
			'id_produit' => $id_produit,
			'quantite' => $quantite,
			'numero_commande' => $numero_commande
		]);
	}

}
?>