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

	public function create(Evenement $evenement): bool {
		$stmt = $this->pdo->prepare('INSERT INTO "evenement" (titre_event, date_debut, date_fin, adresse, description, prix, id_user) VALUES (:titre_event, :date_debut, :date_fin, :adresse, :description, :prix, :id_user)');
		return $stmt->execute([
			'titre_event' => $evenement->getTitreEvent(),
			'date_debut' => $evenement->getDateDebut()->format('Y-m-d H:i:s'),
			'date_fin' => $evenement->getDateFin()->format('Y-m-d H:i:s'),
			'adresse' => $evenement->getAdresse(),
			'description' => $evenement->getDescription(),
			'prix' => $evenement->getPrix(),
			'id_user' => $evenement->getIdUser()
		]);
	}

	public function update(Evenement $evenement): bool {
		$stmt = $this->pdo->prepare('UPDATE "evenement" SET titre_event = :titre_event, date_debut = :date_debut, date_fin = :date_fin, adresse = :adresse, description = :description, prix = :prix WHERE id_event = :id_event');
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

	public function findById(int $id): ?Evenement {
		$stmt = $this->pdo->prepare('SELECT * FROM "evenement" WHERE id_event = :id_event');
		$stmt->execute(['id_event' => $id]);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($row) {
			return $this->createEvenementFromRow($row);
		}
		return null;
	}

	public function delete(int $id): bool {
		$stmt = $this->pdo->prepare('DELETE FROM "evenement" WHERE id_event = :id_event');
		return $stmt->execute(['id_event' => $id]);
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