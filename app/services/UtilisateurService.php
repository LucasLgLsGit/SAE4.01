<?php

require_once '../repositories/UtilisateurRepository.php';
require_once '../entities/Utilisateur.php';

class UtilisateurService
{
	public function allUtilisateurs(): array
	{
		$utilisateurRepo = new UtilisateurRepository();
		return $utilisateurRepo->findAll();
	}

	public function create(array $data): Utilisateur
	{
		$errors = [];

		if (empty($data['mail'])) {
			$errors[] = "L'adresse e-mail est requise !";
		}
		if (empty($data['mdp'])) {
			$errors[] = "Le mot de passe est requis !";
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
			null, // L'ID est auto-incrémenté
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
}
