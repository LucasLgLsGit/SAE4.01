<?php
require_once './app/core/Repository.php';
require_once './app/entities/Utilisateur.php';

class UtilisateurRepository {
	private $pdo;

	public function __construct() {
		$this->pdo = Repository::getInstance()->getPDO();
	}

	public function findAll(): array {
		$stmt = $this->pdo->query('SELECT * FROM "utilisateur"');
		$utilisateurs = [];
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$utilisateurs[] = $this->createUserFromRow($row);
		}
		return $utilisateurs;
	}

	private function createUserFromRow(array $row): Utilisateur
	{
		return new Utilisateur(	$row['id_user'], 
								$row['mail'], 
								$row['mdp'], 
								$row['permission'], 
								$row['nom'], 
								$row['prenom']);
	}

	public function create(Utilisateur $utilisateur): bool {
		$stmt = $this->pdo->prepare('INSERT INTO "utilisateur" (mail, mdp, permission, nom, prenom) VALUES (:mail, :mdp, :permission, :nom, :prenom)');
		return $stmt->execute([
			'mail' => $utilisateur->getMail(),
			'mdp' => $utilisateur->getMdp(),
			'permission' => $utilisateur->getPermission(),
			'nom' => $utilisateur->getNom(),
			'prenom' => $utilisateur->getPrenom()
		]);
	}

	public function updateById(int $id, array $data): void
	{
		$stmt = $this->pdo->prepare('UPDATE utilisateur SET nom = :nom, prenom = :prenom, mail = :mail, permission = :permission WHERE id_user = :id_user');
		$stmt->execute([
			'nom' => $data['nom'],
			'prenom' => $data['prenom'],
			'mail' => $data['mail'],
			'permission' => $data['permission'] ?? $this->findById($id)->getPermission(),
			'id_user' => $id,
		]);
}

	public function deleteById(int $id): void
	{
		$stmt = $this->pdo->prepare('DELETE FROM "utilisateur" WHERE id_user = :id_user');
		$stmt->execute(['id_user' => $id]);
	}

	public function findById(int $id): ?Utilisateur {
		$stmt = $this->pdo->prepare('SELECT * FROM "utilisateur" WHERE id_user = :id_user');
		$stmt->execute(['id_user' => $id]);
		$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($utilisateur) {
			return $this->createUserFromRow($utilisateur);
		}
		return null;
	}

	public function findByEmail(string $email): ?Utilisateur {
		$stmt = $this->pdo->prepare('SELECT * FROM "utilisateur" WHERE mail = :mail');
		$stmt->execute(['mail' => $email]);
		$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($utilisateur) {
			return $this->createUserFromRow($utilisateur);
		}
		return null;
	}

	public function countMembres(): int {
		$stmt = $this->pdo->query('SELECT COUNT(*) FROM "utilisateur" WHERE (permission & 1) != 0');
		return (int) $stmt->fetchColumn();
	}

	public function countAdherents(): int {
		$stmt = $this->pdo->query('SELECT COUNT(*) FROM "utilisateur" WHERE (permission & 2) != 0');
		return (int) $stmt->fetchColumn();
	}
}