<?php

require_once '../repositories/ProduitRepository.php';
require_once '../entities/Produit.php';

class ProduitService
{
    public function allProduits(): array
    {
        $produitRepo = new ProduitRepository();
        return $produitRepo->findAll();
    }

    public function create(array $data): Produit
    {
        $errors = [];

        if (empty($data['nom_produit'])) {
            $errors[] = "Le nom du produit est requis !";
        }
        if (empty($data['description_produit'])) {
            $errors[] = "La description du produit est requise !";
        }
        if (empty($data['date_ajout'])) {
            $errors[] = "La date d'ajout est requise !";
        }
        if (empty($data['couleur'])) {
            $errors[] = "La couleur du produit est requise !";
        }
        if (empty($data['taille'])) {
            $errors[] = "La taille du produit est requise !";
        }
        if (empty($data['stock'])) {
            $errors[] = "Le stock du produit est requis !";
        }
        if (empty($data['prix'])) {
            $errors[] = "Le prix du produit est requis !";
        }
        if (empty($data['id_user'])) {
            $errors[] = "L'identifiant utilisateur (id_user) est requis !";
        }

        if (!empty($errors)) {
            throw new Exception(implode(', ', $errors));
        }

        $produit = new Produit(
            0, // L'ID est auto-incrémenté
            $data['nom_produit'],
            $data['description_produit'],
            new DateTime($data['date_ajout']),
            $data['couleur'],
            $data['taille'],
            (int) $data['stock'],
            (float) $data['prix'],
            (int) $data['id_user']
        );

        $produitRepo = new ProduitRepository();
        $produitRepo->create($produit);

        return $produit;
    }

    public function update(array $data): Produit
    {
        $errors = [];

        if (empty($data['id_produit'])) {
            $errors[] = "L'identifiant du produit est requis !";
        }
        if (empty($data['nom_produit'])) {
            $errors[] = "Le nom du produit est requis !";
        }
        if (empty($data['description_produit'])) {
            $errors[] = "La description du produit est requise !";
        }
        if (empty($data['date_ajout'])) {
            $errors[] = "La date d'ajout est requise !";
        }
        if (empty($data['couleur'])) {
            $errors[] = "La couleur du produit est requise !";
        }
        if (empty($data['taille'])) {
            $errors[] = "La taille du produit est requise !";
        }
        if (empty($data['stock'])) {
            $errors[] = "Le stock du produit est requis !";
        }
        if (empty($data['prix'])) {
            $errors[] = "Le prix du produit est requis !";
        }
        if (empty($data['id_user'])) {
            $errors[] = "L'identifiant utilisateur (id_user) est requis !";
        }

        if (!empty($errors)) {
            throw new Exception(implode(', ', $errors));
        }

        $produit = new Produit(
            (int) $data['id_produit'],
            $data['nom_produit'],
            $data['description_produit'],
            new DateTime($data['date_ajout']),
            $data['couleur'],
            $data['taille'],
            (int) $data['stock'],
            (float) $data['prix'],
            (int) $data['id_user']
        );

        $produitRepo = new ProduitRepository();
        $produitRepo->update($produit);

        return $produit;
    }
}
