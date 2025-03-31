<?php
require_once './app/core/Repository.php';
require_once './app/entities/User.php';

class UserRepository {
	private $pdo;

	public function __construct() {
		$this->pdo = Repository::getInstance()->getPDO();
	}

	public function findAll(): array {
		$stmt = $this->pdo->query('SELECT * FROM "User"');
		$users = [];
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$users[] = $this->createUserFromRow($row);
		}
		return $users;
	}

	private function createUserFromRow(array $row): User
	{
		return new User($row['id_user'], $row['prenom'], $row['nom'], $row['mail'], $row['mdp'], $row['permission']);
	}

	public function create(User $user): bool {
		$stmt = $this->pdo->prepare('INSERT INTO "User" (prenom, nom, mail, mdp, permission) VALUES (:prenom, :nom, :mail, :mdp, :permission)');
		return $stmt->execute([
			'prenom' => $user->getPrenom(),
			'nom' => $user->getNom(),
			'mail' => $user->getMail(),
			'mdp' => password_hash($user->getMdp(), PASSWORD_BCRYPT),
			'permission' => $user->getPermission()
		]);
	}

	public function update(User $user): bool {
		$stmt = $this->pdo->prepare('UPDATE "User" SET prenom = :newprenom, nom = :newnom, mail = :newmail, mdp = :newmdp, permission = :newpermission WHERE id_user = :id_user');
		return $stmt->execute([
			'id_user' => $user->getId(),
			'newprenom' => $user->getPrenom(),
			'newnom' => $user->getNom(),
			'newmail' => $user->getMail(),
			'newmdp' => password_hash($user->getMdp(), PASSWORD_BCRYPT),
			'newpermission' => $user->getPermission()
		]);
	}

	public function findById(int $id): ?User {
		$stmt = $this->pdo->prepare('SELECT * FROM "User" WHERE id = :id');
		$stmt->execute(['id' => $id]);
		$user = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($user) {
			return $this->createUserFromRow($user);
		}
		return null;
	}
}