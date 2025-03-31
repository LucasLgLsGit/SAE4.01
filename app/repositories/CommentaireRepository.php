<?php
require_once './app/core/Repository.php';
require_once './app/entities/Commentaire.php';

class CommentaireRepository {
	private $pdo;

	public function __construct() {
		$this->pdo = Repository::getInstance()->getPDO();
	}

	public function findAll(): array {
		$stmt = $this->pdo->query('SELECT * FROM "Commentaire"');
		$commentaires = [];
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$commentaires[] = $this->createCommentaireFromRow($row);
		}
		return $commentaires;
	}

	private function createCommentaireFromRow(array $row): Commentaire
	{
		return new Commentaire($row['id_commentaire'], $row['texte_commentaire'], $row['date_commentaire'], $row['id_produit'], $row['id_user']);
	}

	public function create(Commentaire $commentaire): bool {
		$stmt = $this->pdo->prepare('INSERT INTO "Commentaire" (texte_commentaire, date_commentaire, id_produit, id_user) VALUES (:texte_commentaire, :date_commentaire, :id_produit, :id_user)');
		return $stmt->execute([
			'texte_commentaire' => $commentaire->getTexte_commentaire(),
			'date_commentaire' => $commentaire->getDate_commentaire(),
			'id_produit' => $commentaire->getId_produit(),
			'id_user' => $commentaire->getId_user()
		]);
	}

	public function update(Commentaire $commentaire): bool {
		$stmt = $this->pdo->prepare('UPDATE "Commentaire" SET texte_commentaire = :newtexte_commentaire, date_commentaire = :newdate_commentaire, id_produit = :newid_produit, id_user = :newid_user WHERE id_commentaire = :id_commentaire');
		return $stmt->execute([
			'id_commentaire' => $commentaire->getId_commentaire(),
			'newtexte_commentaire' => $commentaire->getTexte_commentaire(),
			'newdate_commentaire' => $commentaire->getDate_commentaire(),
			'newid_produit' => $commentaire->getId_produit(),
			'newid_user' => $commentaire->getId_user()
		]);
	}

	public function findById(int $id): ?Commentaire {
		$stmt = $this->pdo->prepare('SELECT * FROM "Commentaire" WHERE id_commentaire = :id_commentaire');
		$stmt->execute(['id_commentaire' => $id]);
		$commentaire = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($commentaire) {
			return $this->createCommentaireFromRow($commentaire);
		}
		return null;
	}
}