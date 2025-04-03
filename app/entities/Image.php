<?php
class Image
{
    private ?int $id_image;
    private string $nom_image;
    private int $id_produit;

    public function __construct(?int $id_image, string $nom_image, int $id_produit)
    {
        $this->id_image = $id_image;
        $this->nom_image = $nom_image;
        $this->id_produit = $id_produit;
    }

    public function getId_image(): ?int { return $this->id_image; }
    public function setId_image(int $id_image): void { $this->id_image = $id_image; }
    public function getNom_image(): string { return $this->nom_image; }
    public function setNom_image(string $nom_image): void { $this->nom_image = $nom_image; }
    public function getId_produit(): int { return $this->id_produit; }
    public function setId_produit(int $id_produit): void { $this->id_produit = $id_produit; }
}