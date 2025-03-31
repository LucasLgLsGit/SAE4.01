<?php

require_once '../repositories/EvenementRepository.php';
require_once '../entities/Evenement.php';

class EvenementService
{
    public function allEvenements(): array
    {
        $evenementRepo = new EvenementRepository();
        return $evenementRepo->findAll();
    }

    public function create(array $data): Evenement
    {
        $errors = [];

        if (empty($data['titre_event'])) {
            $errors[] = "Le titre de l'événement est requis !";
        }
        if (empty($data['date_debut'])) {
            $errors[] = "La date de début est requise !";
        }
        if (empty($data['date_fin'])) {
            $errors[] = "La date de fin est requise !";
        }
        if (empty($data['adresse'])) {
            $errors[] = "L'adresse est requise !";
        }
        if (empty($data['description'])) {
            $errors[] = "La description est requise !";
        }
        if (!isset($data['prix']) || $data['prix'] < 0) {
            $errors[] = "Le prix doit être un nombre positif !";
        }
        if (empty($data['id_user'])) {
            $errors[] = "L'identifiant utilisateur (id_user) est requis !";
        }

        if (!empty($errors)) {
            throw new Exception(implode(', ', $errors));
        }

        $evenement = new Evenement(
            0,
            $data['titre_event'],
            new DateTime($data['date_debut']),
            new DateTime($data['date_fin']),
            $data['adresse'],
            $data['description'],
            (float)$data['prix'],
            (int)$data['id_user']
        );

        $evenementRepo = new EvenementRepository();
        if (!$evenementRepo->create($evenement)) {
            throw new Exception("La création de l'événement a échoué.");
        }

        return $evenement;
    }
}
 