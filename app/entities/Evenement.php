<?php

class Evenement
{

	public function __construct(
		private int $id_event,
		private string $titre_event,
		private DateTime $date_debut,
		private DateTime $date_fin,
		private string $adresse,
		private string $description,
		private float $prix,
		private int $id_user
	)
	{}

	public function setId(int $id_event): void
	{
		$this->id_event = $id_event;
	}

	public function setIdUser(int $id_user): void
	{
		$this->id_user = $id_user;
	}

	public function getId(): int
	{
		return $this->id_event;
	}

	public function getTitreEvent(): string
	{
		return $this->titre_event;
	}

	public function getDateDebut(): DateTime
	{
		return $this->date_debut;
	}

	public function getDateFin(): DateTime
	{
		return $this->date_fin;
	}

	public function getAdresse(): string
	{
		return $this->adresse;
	}

	public function getDescription(): string
	{
		return $this->description;
	}

	public function getPrix(): float
	{
		return $this->prix;
	}

	public function getIdUser(): int
	{
		return $this->id_user;
	}

	public function setTitreEvent(string $titre_event): void
	{
		$this->titre_event = $titre_event;
	}

	public function setDateDebut(DateTime $date_debut): void
	{
		$this->date_debut = $date_debut;
	}

	public function setDateFin(DateTime $date_fin): void
	{
		$this->date_fin = $date_fin;
	}

	public function setAdresse(string $adresse): void
	{
		$this->adresse = $adresse;
	}

	public function setDescription(string $description): void
	{
		$this->description = $description;
	}

	public function setPrix(float $prix): void
	{
		$this->prix = $prix;
	}
}

?>