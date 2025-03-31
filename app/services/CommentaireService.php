<?php

require_once '../repositories/CommentaireRepository.php';
require_once '../entities/Commentaire.php';

class CommentaireService
{
    public function allCommentaires(): array
    {
        $commentaireRepo = new CommentaireRepository();
        return $commentaireRepo->findAll();
    }

    public function create(array $data): Commentaire
    {
        $errors = [];

        if (empty($data['texte_commentaire'])) {
            $errors[] = "Le texte du commentaire est requis !";
        }
        if (empty($data['date_commentaire'])) {
            $errors[] = "La date du commentaire est requise !";
        }
        if (empty($data['id_user'])) {
            $errors[] = "L'identifiant utilisateur (id_user) est requis !";
        }
        if (empty($data['id_event'])) {
            $errors[] = "L'identifiant de l'événement (id_event) est requis !";
        }

        if (!empty($errors)) {
            throw new Exception(implode(', ', $errors));
        }

        $commentaire = new Commentaire(
            0,
            $data['texte_commentaire'],
            new DateTime($data['date_commentaire']),
            (int) $data['id_user'],
            (int) $data['id_event']
        );

        $commentaireRepo = new CommentaireRepository();
        if (!$commentaireRepo->create($commentaire)) {
            throw new Exception("La création du commentaire a échoué.");
        }

        return $commentaire;
    }
}
