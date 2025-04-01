<?php

require_once './app/repositories/UtilisateurRepository.php';
require_once './app/entities/Utilisateur.php';
require_once './app/trait/AuthTrait.php';
require_once './app/trait/FormTrait.php';

class UtilisateurService
{
	use FormTrait;
	use AuthTrait;
	
	public function allUtilisateurs(): array
	{
		$utilisateurRepo = new UtilisateurRepository();
		return $utilisateurRepo->findAll();
	}

	public function create(array $data): Utilisateur
	{
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

		$hashedPassword = $this->hash($data['mdp']);
		$utilisateur = new Utilisateur(
			null,
			$data['mail'],
			$hashedPassword,
			1,
			$data['nom'],
			$data['prenom']
		);

		$utilisateurRepo = new UtilisateurRepository();
		if (!$utilisateurRepo->create($utilisateur)) {
			throw new Exception("La création de l'utilisateur a échoué.");
		}

		return $utilisateur;
	}

	public function update(int $id_user, array $data): bool
	{
		$utilisateurRepo = new UtilisateurRepository();
		$utilisateur = $utilisateurRepo->findById($id_user);

		if (!$utilisateur) {
			throw new Exception("Utilisateur non trouvé.");
		}

		if (empty($data['mail']) || !filter_var($data['mail'], FILTER_VALIDATE_EMAIL)) {
			$errors[] = "L'adresse e-mail est invalide !";
		}
		if (empty($data['mdp']) && strlen($data['mdp']) < 6) {
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

		$utilisateur->setMail($data['mail']);
		if (!empty($data['mdp'])) {
			$hashedPassword = $this->hash($data['mdp']);
			$utilisateur->setMdp($hashedPassword);
		}
		$utilisateur->setPermission((int) $data['permission']);
		$utilisateur->setNom($data['nom']);
		$utilisateur->setPrenom($data['prenom']);

		return $utilisateurRepo->update($utilisateur);
	}

	public function updateEmail(int $id, string $newEmail): bool 
	{
		$utilisateurRepo = new UtilisateurRepository();
		$user = $utilisateurRepo->findById($id);
		
		if (!$user) {
			throw new Exception("Utilisateur non trouvé");
		}
		
		if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
			throw new Exception("Email invalide");
		}
		
		$user->setMail($newEmail);
		return $utilisateurRepo->update($user);
	}

	public function updateMdp(int $id, string $plainPassword): bool
	{
		// Validation minimale
		if (strlen($plainPassword) < 6) {
			throw new Exception("Le mot de passe doit faire au moins 6 caractères");
		}

		$utilisateurRepo = new UtilisateurRepository();
		$user = $utilisateurRepo->findById($id);

		if (!$user) {
			throw new Exception("Utilisateur non trouvé");
		}

		// Le hachage sera fait dans repository->update()
		$user->setMdp($plainPassword);
		
		return $utilisateurRepo->update($user);
	}
}
