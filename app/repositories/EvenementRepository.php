<?php
require_once './app/core/Repository.php';
require_once './app/entities/Evenement.php';

class EvenementRepository {
	private $pdo;

	public function __construct() {
		$this->pdo = Repository::getInstance()->getPDO();
	}

	public function findAll(): array {
		$stmt = $this->pdo->query('SELECT * FROM "evenement"');
		$evenements = [];
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$evenements[] = $this->createEvenementFromRow($row);
		}
		return $evenements;
	}

	public function create(array $data): Evenement {
		$errors = [];
		if (empty($data['titre_event'])) $errors[] = "Le titre de l'événement est requis !";
		if (empty($data['date_debut'])) $errors[] = "La date de début est requise !";
		if (empty($data['date_fin'])) $errors[] = "La date de fin est requise !";
		if (empty($data['adresse'])) $errors[] = "L'adresse est requise !";
		if (empty($data['description'])) $errors[] = "La description est requise !";
		if (!isset($data['prix']) || $data['prix'] < 0) $errors[] = "Le prix doit être un nombre positif !";
		if (empty($data['id_user'])) $errors[] = "L'identifiant utilisateur (id_user) est requis !";
		if (!empty($errors)) throw new Exception(implode(', ', $errors));

		$evenement = new Evenement(
			null,
			$data['titre_event'],
			new DateTime($data['date_debut']),
			new DateTime($data['date_fin']),
			$data['adresse'],
			$data['description'],
			(float)$data['prix'],
			(int)$data['id_user']
		);

		$stmt = $this->pdo->prepare('
			INSERT INTO "evenement" (titre_event, date_debut, date_fin, adresse, description, prix, id_user) 
			VALUES (:titre_event, :date_debut, :date_fin, :adresse, :description, :prix, :id_user)
		');

		$success = $stmt->execute([
			'titre_event' => $evenement->getTitreEvent(),
			'date_debut' => $evenement->getDateDebut()->format('Y-m-d H:i:s'),
			'date_fin' => $evenement->getDateFin()->format('Y-m-d H:i:s'),
			'adresse' => $evenement->getAdresse(),
			'description' => $evenement->getDescription(),
			'prix' => $evenement->getPrix(),
			'id_user' => $evenement->getIdUser()
		]);

		if (!$success) throw new Exception("La création de l'événement a échoué.");

		$evenement->setId((int)$this->pdo->lastInsertId());
		return $evenement;
	}

	private function createEvenementFromRow(array $row): Evenement {
		return new Evenement(
			$row['id_event'],
			$row['titre_event'],
			new DateTime($row['date_debut']),
			new DateTime($row['date_fin']),
			$row['adresse'],
			$row['description'],
			(float)$row['prix'],
			(int)$row['id_user']
		);
	}

	public function update(Evenement $evenement): bool {
		$stmt = $this->pdo->prepare('
			UPDATE "evenement" 
			SET titre_event = :titre_event, date_debut = :date_debut, date_fin = :date_fin, adresse = :adresse, description = :description, prix = :prix 
			WHERE id_event = :id_event
		');
		return $stmt->execute([
			'id_event' => $evenement->getId(),
			'titre_event' => $evenement->getTitreEvent(),
			'date_debut' => $evenement->getDateDebut()->format('Y-m-d H:i:s'),
			'date_fin' => $evenement->getDateFin()->format('Y-m-d H:i:s'),
			'adresse' => $evenement->getAdresse(),
			'description' => $evenement->getDescription(),
			'prix' => $evenement->getPrix()
		]);
	}

	public function delete(int $id): bool {
		$stmt = $this->pdo->prepare('DELETE FROM "evenement" WHERE id_event = :id_event');
		return $stmt->execute(['id_event' => $id]);
	}





	public function findById(int $id): ?Evenement {
		$stmt = $this->pdo->prepare('SELECT * FROM evenement WHERE id_event = :id');
		$stmt->execute(['id' => $id]);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($row) return $this->createEvenementFromRow($row);
		return null;
	}





	public function findUpcomingEvents(int $limit = null): array {
		$stmt = $this->pdo->prepare('
			SELECT * FROM "evenement"
			WHERE date_debut >= NOW()
			ORDER BY date_debut ASC
			LIMIT :limit
		');
		$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
		$stmt->execute();
		$evenements = [];
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$evenements[] = $this->createEvenementFromRow($row);
		}
		return $evenements;
	}

	public function countEvenementsByYear(int $year): int {
		$stmt = $this->pdo->prepare('
			SELECT COUNT(*) 
			FROM "evenement" 
			WHERE date_debut >= :start_date AND date_debut < :end_date
		');
		$start_date = "$year-01-01";
		$end_date = ($year + 1) . "-01-01";
		$stmt->execute([
			':start_date' => $start_date,
			':end_date' => $end_date
		]);
		return (int) $stmt->fetchColumn();
	}
}
?>
