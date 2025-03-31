<?php
require_once './app/core/Repository.php';
require_once './app/entities/Evenement.php';

class EvenementRepository
{
	private $pdo;

	public function __construct() {
		$this->pdo = Repository::getInstance()->getPDO();
	}

	public function findAll() {
		$stmt = $this->pdo->prepare("SELECT * FROM Evenement");
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_CLASS, 'Evenement');
	}

	public function findById($id_event) {
		$stmt = $this->pdo->prepare("SELECT * FROM Evenement WHERE id_event = :id_event");
		$stmt->bindParam(':id_event', $id_event, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchObject('Evenement');
	}

	public function create($evenement) {
		$stmt = $this->pdo->prepare("INSERT INTO Evenement (titre_event, date_debut, date_fin, adresse, description, prix, id_user) VALUES (:titre_event, :date_debut, :date_fin, :adresse, :description, :prix, :id_user)");
		$stmt->bindParam(':titre_event', $evenement->titre_event);
		$stmt->bindParam(':date_debut', $evenement->date_debut);
		$stmt->bindParam(':date_fin', $evenement->date_fin);
		$stmt->bindParam(':adresse', $evenement->adresse);
		$stmt->bindParam(':description', $evenement->description);
		$stmt->bindParam(':prix', $evenement->prix);
		$stmt->bindParam(':id_user', $evenement->id_user, PDO::PARAM_INT);
		return $stmt->execute();
	}

	public function update($evenement) {
		$stmt = $this->pdo->prepare("UPDATE Evenement SET titre_event = :titre_event, date_debut = :date_debut, date_fin = :date_fin, adresse = :adresse, description = :description, prix = :prix, id_user = :id_user WHERE id_event = :id_event");
		$stmt->bindParam(':titre_event', $evenement->titre_event);
		$stmt->bindParam(':date_debut', $evenement->date_debut);
		$stmt->bindParam(':date_fin', $evenement->date_fin);
		$stmt->bindParam(':adresse', $evenement->adresse);
		$stmt->bindParam(':description', $evenement->description);
		$stmt->bindParam(':prix', $evenement->prix);
		$stmt->bindParam(':id_user', $evenement->id_user, PDO::PARAM_INT);
		$stmt->bindParam(':id_event', $evenement->id_event, PDO::PARAM_INT);
		return $stmt->execute();
	}

	public function delete($id_event) {
		$stmt = $this->pdo->prepare("DELETE FROM Evenement WHERE id_event = :id_event");
		$stmt->bindParam(':id_event', $id_event, PDO::PARAM_INT);
		return $stmt->execute();
	}
}

?>