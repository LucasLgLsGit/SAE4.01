<?php

class Commande
{
    public function __construct(
        private int $id_user,
        private int $id_produit,
        private int $quantite,
        private string $numero_commande
    ) {}

    public function getIdUser(): int
    {
        return $this->id_user;
    }

    public function setIdUser(int $id_user): void
    {
        $this->id_user = $id_user;
    }

    public function getIdProduit(): int
    {
        return $this->id_produit;
    }

    public function setIdProduit(int $id_produit): void
    {
        $this->id_produit = $id_produit;
    }

    public function getQuantite(): int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): void
    {
        $this->quantite = $quantite;
    }

    public function getNumeroCommande(): string
    {
        return $this->numero_commande;
    }

    public function setNumeroCommande(string $numero_commande): void
    {
        $this->numero_commande = $numero_commande;
    }
}
?>