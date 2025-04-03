<?php 
require_once './app/core/Repository.php';
require_once './app/entities/Participation.php';

class ParticipationRepository 
{
	private $pdo;

	public function __construct() 
	{
		$this->pdo = Repository::getInstance()->getPDO();
	}

	public function findAll(): array 
	{
		$stmt = $this->pdo->query('
			SELECT 
				p.id_user,
				u.nom AS user_nom,
				u.prenom AS user_prenom,
				p.id_event,
				e.titre_event AS event_titre,
				p.date_inscription AS date_participation
			FROM 
				Participation p
			INNER JOIN 
				Utilisateur u ON p.id_user = u.id_user
			INNER JOIN 
				Evenement e ON p.id_event = e.id_event
		');

		$participations = [];
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$participations[] = $row;
		}
		return $participations;
	}

	public function create(array $data): Participation
	{
		$errors = [];
		if (empty($data['id_user'])) 
		{
			$errors[] = "L'identifiant utilisateur (id_user) est requis !";
		}
		if (empty($data['id_event'])) 
		{
			$errors[] = "L'identifiant de l'événement (id_event) est requis !";
		}
		if (empty($data['date_inscription'])) 
		{
			$errors[] = "La date d'inscription est requise !";
		}
		if (!empty($errors)) 
		{
			throw new Exception(implode(', ', $errors));
		}
		$participation = new Participation(
			(int) $data['id_user'],
			(int) $data['id_event'],
			new DateTime($data['date_inscription'])
		);
		$stmt = $this->pdo->prepare('
			INSERT INTO "participation" (id_user, id_event, date_inscription) 
			VALUES (:id_user, :id_event, :date_inscription)
		');
		$success = $stmt->execute([
			'id_user' => $participation->getId_user(),
			'id_event' => $participation->getId_event(),
			'date_inscription' => $participation->getDate_inscription()->format('Y-m-d H:i:s')
		]);
		if (!$success) 
		{
			throw new Exception("La création de la participation a échoué.");
		}
		return $participation;
	}

	private function createParticipationFromRow(array $row): Participation 
	{
		return new Participation(
			$row['id_user'], 
			$row['id_event'], 
			new DateTime($row['date_inscription'])
		);
	}

	public function update(array $data): bool
	{
		$errors = [];
		if (empty($data['id_user'])) 
		{
			$errors[] = "L'identifiant utilisateur (id_user) est requis !";
		}
		if (empty($data['id_event'])) 
		{
			$errors[] = "L'identifiant de l'événement (id_event) est requis !";
		}
		if (empty($data['date_inscription'])) 
		{
			$errors[] = "La date d'inscription est requise !";
		}
		if (!empty($errors)) 
		{
			throw new Exception(implode(', ', $errors));
		}
		$participation = new Participation(
			(int) $data['id_user'],
			(int) $data['id_event'],
			new DateTime($data['date_inscription'])
		);
		$stmt = $this->pdo->prepare('
			UPDATE "participation" 
			SET date_inscription = :date_inscription 
			WHERE id_user = :id_user AND id_event = :id_event
		');
		$success = $stmt->execute([
			'id_user' => $participation->getId_user(),
			'id_event' => $participation->getId_event(),
			'date_inscription' => $participation->getDate_inscription()->format('Y-m-d H:i:s')
		]);
		if (!$success) 
		{
			throw new Exception("La mise à jour de la participation a échoué.");
		}
		return true;
	}

	public function delete(int $id_user, int $id_event): bool
	{
		$participation = $this->findById($id_user, $id_event);
		if (!$participation) 
		{
			throw new Exception("Participation non trouvée pour l'utilisateur ID $id_user et l'événement ID $id_event.");
		}
		$stmt = $this->pdo->prepare('DELETE FROM "participation" WHERE id_user = :id_user AND id_event = :id_event');
		$success = $stmt->execute([
			'id_user' => $id_user,
			'id_event' => $id_event
		]);
		if (!$success) 
		{
			throw new Exception("Erreur : impossible de supprimer la participation.");
		}
		return true;
	}





	public function findById(int $id_user, int $id_event): ?Participation 
	{
		$stmt = $this->pdo->prepare('SELECT * FROM "participation" WHERE id_user = :id_user AND id_event = :id_event');
		$stmt->execute([
			'id_user' => $id_user,
			'id_event' => $id_event
		]);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row ? $this->createParticipationFromRow($row) : null;
	}
}
