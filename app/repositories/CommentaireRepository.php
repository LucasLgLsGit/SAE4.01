<?php
require_once './app/core/Repository.php';
require_once './app/entities/Commentaire.php';

class CommentaireRepository {
	private $pdo;

	public function __construct() {
		$this->pdo = Repository::getInstance()->getPDO();
	}

	public function findAll(): array {
		$stmt = $this->pdo->query('SELECT * FROM "commentaire"');
		$commentaires = [];
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$commentaires[] = $this->createCommentaireFromRow($row);
		}
		return $commentaires;
	}

	public function create(array $data): Commentaire {
		$errors = [];
		if (empty($data['texte_commentaire'])) {
			$errors[] = "Le texte du commentaire est requis !";
		}
		if (empty($data['date_commentaire'])) {
			$errors[] = "La date du commentaire est requise !";
		}
		if (empty($data['id_user'])) {
			$errors[] = "L'identifiant utilisateur (id_user) est requis !";
		}
		if (empty($data['id_event'])) {
			$errors[] = "L'identifiant de l'événement (id_event) est requis !";
		}
		if (!empty($errors)) {
			throw new Exception(implode(', ', $errors));
		}
		$commentaire = new Commentaire(
			null,
			$data['texte_commentaire'],
			new DateTime($data['date_commentaire']),
			(int) $data['id_user'],
			(int) $data['id_event']
		);
		$stmt = $this->pdo->prepare('
			INSERT INTO "commentaire" (texte, date_commentaire, id_user, id_event) 
			VALUES (:texte, :date, :user, :event)
		');
		$success = $stmt->execute([
			'texte' => $commentaire->getTexte(),
			'date' => $commentaire->getDate_commentaire()->format('Y-m-d H:i:s'),
			'user' => $commentaire->getId_user(),
			'event' => $commentaire->getId_event()
		]);
		if (!$success) {
			throw new Exception("La création du commentaire a échoué.");
		}
		$commentaire->setId_commentaire((int) $this->pdo->lastInsertId());
		return $commentaire;
	}

	private function createCommentaireFromRow(array $row): Commentaire {
		return new Commentaire(
			$row['id_commentaire'], 
			$row['texte'], 
			new DateTime($row['date_commentaire']), 
			$row['id_user'], 
			$row['id_event']
		);
	}

	public function update(Commentaire $commentaire): bool {
		$stmt = $this->pdo->prepare('
			UPDATE "commentaire" 
			SET texte = :texte, date_commentaire = :date, id_user = :user, id_event = :event 
			WHERE id_commentaire = :id
		');
		return $stmt->execute([
			'id' => $commentaire->getId_commentaire(),
			'texte' => $commentaire->getTexte_commentaire(),
			'date' => $commentaire->getDate_commentaire()->format('Y-m-d H:i:s'),
			'user' => $commentaire->getId_user(),
			'event' => $commentaire->getId_event()
		]);
	}

	public function delete(int $id): bool {
		$stmt = $this->pdo->prepare('DELETE FROM "commentaire" WHERE id_commentaire = :id');
		return $stmt->execute(['id' => $id]);
	}





	public function findById(int $id): ?Commentaire {
		$stmt = $this->pdo->prepare('SELECT * FROM "commentaire" WHERE id_commentaire = :id');
		$stmt->execute(['id' => $id]);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row ? $this->createCommentaireFromRow($row) : null;
	}
}
?>
