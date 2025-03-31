<?php
require_once './app/core/Repository.php';
require_once './app/entities/Utilisateur.php';

class UtilisateurRepository {
	private $pdo;

	public function __construct() {
		$this->pdo = Repository::getInstance()->getPDO();
	}

	public function findAll(): array {
		$stmt = $this->pdo->query('SELECT * FROM "User"');
		$utilisateurs = [];
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$utilisateurs[] = $this->createUserFromRow($row);
		}
		return $utilisateurs;
	}

	private function createUserFromRow(array $row): Utilisateur
	{
		return new Utilisateur($row['id_user'], $row['prenom'], $row['nom'], $row['mail'], $row['mdp'], $row['permission']);
	}

	public function create(Utilisateur $utilisateur): bool {
		$stmt = $this->pdo->prepare('INSERT INTO "User" (prenom, nom, mail, mdp, permission) VALUES (:prenom, :nom, :mail, :mdp, :permission)');
		return $stmt->execute([
			'prenom' => $utilisateur->getPrenom(),
			'nom' => $utilisateur->getNom(),
			'mail' => $utilisateur->getMail(),
			'mdp' => password_hash($utilisateur->getMdp(), PASSWORD_BCRYPT),
			'permission' => $utilisateur->getPermission()
		]);
	}

	public function update(Utilisateur $utilisateur): bool {
		$stmt = $this->pdo->prepare('UPDATE "User" SET prenom = :newprenom, nom = :newnom, mail = :newmail, mdp = :newmdp, permission = :newpermission WHERE id_user = :id_user');
		return $stmt->execute([
			'id_user' => $utilisateur->getId(),
			'newprenom' => $utilisateur->getPrenom(),
			'newnom' => $utilisateur->getNom(),
			'newmail' => $utilisateur->getMail(),
			'newmdp' => password_hash($utilisateur->getMdp(), PASSWORD_BCRYPT),
			'newpermission' => $utilisateur->getPermission()
		]);
	}

	public function findById(int $id): ?Utilisateur {
		$stmt = $this->pdo->prepare('SELECT * FROM "User" WHERE id_user = :id_user');
		$stmt->execute(['id_user' => $id]);
		$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($utilisateur) {
			return $this->createUserFromRow($utilisateur);
		}
		return null;
	}
}