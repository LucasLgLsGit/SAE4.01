<?php
class Produit { 

	public function __construct(private int $id_produit,
	private string $titre_produit,
	private string $description_produit,
	private Datetime $date_produit,
	private string $couleur,
	private string $taille,
	private int $stock,
	private float $prix,
	private int $id_user

	) {
		$this->id_produit = $id_produit;
		$this->titre_produit = $titre_produit;
		$this->description_produit = $description_produit;
		$this->date_produit = $date_produit;
		$this->couleur = $couleur;
		$this->taille = $taille;
		$this->stock = $stock;
		$this->prix = $prix;
		$this->id_user = $id_user;
	}

	// Getters
	public function getId_produit(): int {
		return $this->id_produit;
	}
	public function getTitre_produit(): string {
		return $this->titre_produit;
	}
	public function getDescription_produit(): string {
		return $this->description_produit;
	}
	public function getDate_produit(): Datetime {
		return $this->date_produit;
	}
	public function getCouleur(): string {
		return $this->couleur;
	}
	public function getTaille(): string {
		return $this->taille;
	} 
	public function getStock(): int {
		return $this->stock;
	}
	public function getPrix(): float {
		return $this->prix;
	}
	public function getId_user(): int {
		return $this->id_user;
	}

	// Setters
	public function setId_produit(int $id_produit): void {
		$this->id_produit = $id_produit;
	}
	public function setTitre_produit(string $titre_produit): void {
		$this->titre_produit = $titre_produit;
	}
	public function setDescription_produit(string $description_produit): void {
		$this->description_produit = $description_produit;
	}
	public function setDate_produit(Datetime $date_produit): void {
		$this->date_produit = $date_produit;
	}
	public function setCouleur(string $couleur): void {
		$this->couleur = $couleur;
	}
	public function setTaille(string $taille): void {
		$this->taille = $taille;
	}
	public function setStock(int $stock): void {
		$this->stock = $stock;
	}
	public function setPrix(float $prix): void {
		$this->prix = $prix;
	}
	public function setId_user(int $id_user): void {
		$this->id_user = $id_user;
	}


	public function __toString(): string {
		return "Produit [id_produit=$this->id_produit, titre_produit=$this->titre_produit, description_produit=$this->description_produit, date_produit=$this->date_produit, couleur=$this->couleur, taille=$this->taille, stock=$this->stock, prix=$this->prix]";
	}
}