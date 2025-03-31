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

	public function update(Utilisateur $utilisateur): bool {
		$stmt = $this->pdo->prepare('UPDATE "utilisateur" SET mail = :newmail, mdp = :newmdp, permission = :newpermission, nom = :newnom, prenom = :newprenom WHERE id_user = :id_user');
		return $stmt->execute([
			'id_user' => $utilisateur->getId(),
			'newmail' => $utilisateur->getMail(),
			'newmdp' => password_hash($utilisateur->getMdp(), PASSWORD_BCRYPT),
			'newpermission' => $utilisateur->getPermission(),
			'newnom' => $utilisateur->getNom(),
			'newprenom' => $utilisateur->getPrenom()
		]);
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
}