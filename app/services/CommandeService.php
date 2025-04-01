<?php

require_once '../repositories/CommandeRepository.php';
require_once '/../entities/Commande.php';

class CommandeService
{
    public function allCommandes()
    {
        $commandeRepo = new CommandeRepository();
        return $commandeRepo->findAll();
    }

    public function create(array $data): Commande
    {
        $errors = [];

        if (empty($data['id_user'])) {
            $errors[] = "L'identifiant utilisateur (id_user) est requis !";
        }
        if (empty($data['id_produit'])) {
            $errors[] = "L'identifiant produit (id_produit) est requis !";
        }
        if (empty($data['quantite']) || $data['quantite'] < 0) {
            $errors[] = "La quantité doit être supérieure à 0";
        }
        if (empty($data['numero_commande'])) {
            $errors[] = "Le numéro de commande ne peut pas être nul";
        }

        if (!empty($errors)) {
            throw new Exception(implode(', ', $errors));
        }

        $commande = new Commande($data['id_user'], $data['id_produit'], $data['quantite'], $data['numero_commande']);

        $commandeRepo = new CommandeRepository();
        if (!$commandeRepo->create($commande)) {
            throw new Exception("La création de la commande a échoué.");
        }

        return $commande;
    }

    public function update(array $data): bool
    {
        $errors = [];

        if (empty($data['id_user'])) {
            $errors[] = "L'identifiant utilisateur (id_user) est requis !";
        }
        if (empty($data['id_produit'])) {
            $errors[] = "L'identifiant produit (id_produit) est requis !";
        }
        if (empty($data['quantite']) || $data['quantite'] < 0) {
            $errors[] = "La quantité doit être supérieure à 0";
        }
        if (empty($data['numero_commande'])) {
            $errors[] = "Le numéro de commande ne peut pas être nul";
        }

        if (!empty($errors)) {
            throw new Exception(implode(', ', $errors));
        }

        $commande = new Commande($data['id_user'], $data['id_produit'], $data['quantite'], $data['numero_commande']);

        $commandeRepo = new CommandeRepository();
        if (!$commandeRepo->update($commande)) {
            throw new Exception("La mise à jour de la commande a échoué.");
        }

        return true;
    }

    public function delete(int $id_user, int $id_produit): bool
    {
        $commandeRepo = new CommandeRepository();

        if (!$commandeRepo->delete($id_user, $id_produit)) {
            throw new Exception("La suppression de la commande a échoué.");
        }

        return true;
    }
}

?>
