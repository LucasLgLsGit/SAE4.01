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

	private function createCommentaireFromRow(array $row): Commentaire
	{
		return new Commentaire(
			$row['id_commentaire'], 
			$row['texte'], 
			new DateTime($row['date_commentaire']), 
			$row['id_user'], 
			$row['id_event']
		);
	}

	public function findById(int $id): ?Commentaire {
		$stmt = $this->pdo->prepare('SELECT * FROM "commentaire" WHERE id_commentaire = :id');
		$stmt->execute(['id' => $id]);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row ? $this->createCommentaireFromRow($row) : null;
	}

	public function create(Commentaire $commentaire): bool {
		$stmt = $this->pdo->prepare('INSERT INTO "commentaire" (id_commentaire, texte, date_commentaire, id_user, id_event) VALUES (:id, :texte, :date, :user, :event)');
		return $stmt->execute([
			'id' => $commentaire->getId_commentaire(),
			'texte' => $commentaire->getTexte_commentaire(),
			'date' => $commentaire->getDate_commentaire()->format('Y-m-d H:i:s'),
			'user' => $commentaire->getId_user(),
			'event' => $commentaire->getId_event()
		]);
	}

	public function update(Commentaire $commentaire): bool {
		$stmt = $this->pdo->prepare('UPDATE "commentaire" SET texte = :texte, date_commentaire = :date, id_user = :user, id_event = :event WHERE id_commentaire = :id');
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
}

?>