<?php 

class Commentaire {
	public function __construct(private int $id_commentaire,
	private string $texte,
	private Datetime $date_commentaire,
	private int $id_user,
	private int $id_event,
	
	) {
		$this->id_commentaire = $id_commentaire;
		$this->texte = $texte;
		$this->date_commentaire = $date_commentaire;
		$this->id_user = $id_user;
		$this->id_event = $id_event;
	}


	// Getters
	public function getId_commentaire(): int {
		return $this->id_commentaire;
	}
	public function getTexte(): string {
		return $this->texte;
	}
	public function getDate_commentaire(): Datetime {
		return $this->date_commentaire;
	}
	public function getId_user(): int {
		return $this->id_user;
	}
	public function getId_event(): int {
		return $this->id_event;
	}


	// Setters
	public function setId_commentaire(int $id_commentaire): void {
		$this->id_commentaire = $id_commentaire;
	}
	public function setTexte(string $texte): void {
		$this->texte = $texte;
	}
	public function setDate_commentaire(Datetime $date_commentaire): void {
		$this->date_commentaire = $date_commentaire;
	}  
	public function setId_user(int $id_user): void {
		$this->id_user = $id_user;
	}
	public function setId_event(int $id_event): void {
		$this->id_event = $id_event;
	}


	public function __toString(): string {
		return "Commentaire: [id_commentaire: $this->id_commentaire, texte: $this->texte, date_commentaire: $this->date_commentaire, id_user: $this->id_user, id_event: $this->id_event]";
	}
}