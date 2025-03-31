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
        if (empty($data['permission'])) {
            $errors[] = "Le niveau de permission est requis !";
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

        $utilisateur = new Utilisateur(
            null, // L'ID est auto-incrémenté
            $data['mail'],
            $data['mdp'],
            (int) $data['permission'],
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

        if (isset($data['mail'])) {
            $utilisateur->setMail($data['mail']);
        }
        if (isset($data['mdp'])) {
            $utilisateur->setMdp($data['mdp']);
        }
        if (isset($data['permission'])) {
            $utilisateur->setPermission((int) $data['permission']);
        }
        if (isset($data['nom'])) {
            $utilisateur->setNom($data['nom']);
        }
        if (isset($data['prenom'])) {
            $utilisateur->setPrenom($data['prenom']);
        }

        return $utilisateurRepo->update($utilisateur);
    }
}
