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

	public function create(array $data): Utilisateur {
		$errors = [];
		if (empty($data['mail']) || !filter_var($data['mail'], FILTER_VALIDATE_EMAIL)) {
			$errors[] = "L'adresse e-mail est invalide !";
		}
		if (empty($data['mdp']) || strlen($data['mdp']) < 6) {
			$errors[] = "Le mot de passe doit contenir au moins 6 caractères !";
		}
		if (empty($data['nom'])) {
			$errors[] = "Le nom est requis !";
		}
		if (empty($data['prenom'])) {
			$errors[] = "Le prénom est requis !";
		}
		if (!empty($errors)) {
			throw new Exception(implode(', ', $errors));
		}
		$hashedPassword = password_hash($data['mdp'], PASSWORD_BCRYPT);
		$utilisateur = new Utilisateur(
			null,
			$data['mail'],
			$hashedPassword,
			1,
			$data['nom'],
			$data['prenom']
		);
		$stmt = $this->pdo->prepare('
			INSERT INTO "utilisateur" (mail, mdp, permission, nom, prenom) 
			VALUES (:mail, :mdp, :permission, :nom, :prenom)
		');
		$success = $stmt->execute([
			'mail' => $utilisateur->getMail(),
			'mdp' => $utilisateur->getMdp(),
			'permission' => $utilisateur->getPermission(),
			'nom' => $utilisateur->getNom(),
			'prenom' => $utilisateur->getPrenom(),
		]);
		if (!$success) {
			throw new Exception("La création de l'utilisateur a échoué.");
		}
		$utilisateur->setId_user((int) $this->pdo->lastInsertId());
		return $utilisateur;
	}

	private function createUserFromRow(array $row): Utilisateur {
		return new Utilisateur(
			$row['id_user'], 
			$row['mail'], 
			$row['mdp'], 
			$row['permission'], 
			$row['nom'], 
			$row['prenom']
		);
	}

	public function update(int $id_user, array $data): bool {
		$errors = [];
		$utilisateur = $this->findById($id_user);
		if (!$utilisateur) {
			throw new Exception("Utilisateur non trouvé.");
		}
		if (empty($data['mail']) || !filter_var($data['mail'], FILTER_VALIDATE_EMAIL)) {
			$errors[] = "L'adresse e-mail est invalide !";
		}
		if (!empty($data['mdp']) && strlen($data['mdp']) < 6) {
			$errors[] = "Le mot de passe doit contenir au moins 6 caractères !";
		}
		if (empty($data['permission'])) {
			$errors[] = "La permission est requise !";
		}
		if (empty($data['nom'])) {
			$errors[] = "Le nom est requis !";
		}
		if (empty($data['prenom'])) {
			$errors[] = "Le prénom est requis !";
		}
		if (!empty($errors)) {
			throw new Exception(implode(', ', $errors));
		}
		$utilisateur->setMail($data['mail']);
		if (!empty($data['mdp'])) {
			$hashedPassword = password_hash($data['mdp'], PASSWORD_BCRYPT);
			$utilisateur->setMdp($hashedPassword);
		}
		$utilisateur->setPermission((int) $data['permission']);
		$utilisateur->setNom($data['nom']);
		$utilisateur->setPrenom($data['prenom']);
		$stmt = $this->pdo->prepare('
			UPDATE "utilisateur" 
			SET mail = :mail, mdp = :mdp, permission = :permission, nom = :nom, prenom = :prenom 
			WHERE id_user = :id_user
		');
		$success = $stmt->execute([
			'mail' => $utilisateur->getMail(),
			'mdp' => $utilisateur->getMdp(),
			'permission' => $utilisateur->getPermission(),
			'nom' => $utilisateur->getNom(),
			'prenom' => $utilisateur->getPrenom(),
			'id_user' => $id_user,
		]);
		if (!$success) {
			throw new Exception("La mise à jour de l'utilisateur a échoué.");
		}
		return true;
	}

	public function updateById(int $id, array $data): void {
		$stmt = $this->pdo->prepare('UPDATE utilisateur SET nom = :nom, prenom = :prenom, mail = :mail, permission = :permission WHERE id_user = :id_user');
		$stmt->execute([
			'nom' => $data['nom'],
			'prenom' => $data['prenom'],
			'mail' => $data['mail'],
			'permission' => $data['permission'] ?? $this->findById($id)->getPermission(),
			'id_user' => $id,
		]);
	}

	public function deleteById(int $id): void {
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

	public function updateEmail(int $id, string $newEmail): bool {
		$user = $this->findById($id);
		if (!$user) {
			throw new Exception("Utilisateur non trouvé");
		}
		if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
			throw new Exception("Email invalide");
		}
		$stmt = $this->pdo->prepare('UPDATE "utilisateur" SET mail = :mail WHERE id_user = :id_user');
		$success = $stmt->execute([
			'mail' => $newEmail,
			'id_user' => $id,
		]);
		if (!$success) {
			throw new Exception("La mise à jour de l'email a échoué.");
		}
		return true;
	}

	public function updateMdp(int $id, string $plainPassword): bool {
		if (strlen($plainPassword) < 6) {
			throw new Exception("Le mot de passe doit faire au moins 6 caractères.");
		}
		$user = $this->findById($id);
		if (!$user) {
			throw new Exception("Utilisateur non trouvé.");
		}
		$hashedPassword = password_hash($plainPassword, PASSWORD_BCRYPT);
		$stmt = $this->pdo->prepare('UPDATE "utilisateur" SET mdp = :mdp WHERE id_user = :id_user');
		$success = $stmt->execute([
			'mdp' => $hashedPassword,
			'id_user' => $id,
		]);
		if (!$success) {
			throw new Exception("La mise à jour du mot de passe a échoué.");
		}
		return true;
	}
}
