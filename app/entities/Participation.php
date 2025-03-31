<?php

class Participation {
	public function __construct(private int $id_user,
	private int $id_event,
	private Datetime $date_inscription
	) {
		$this->id_user = $id_user;
		$this->id_event = $id_event;
		$this->date_inscription = $date_inscription;
	}


	// Getters
	public function getId_user(): int {
		return $this->id_user;
	}
	public function getId_event(): int {
		return $this->id_event;
	}
	public function getDate_inscription(): Datetime {
		return $this->date_inscription;
	}


	// Setters
	public function setId_user(int $id_user): void {
		$this->id_user = $id_user;
	}
	public function setId_event(int $id_event): void {
		$this->id_event = $id_event;
	}
	public function setDate_inscription(Datetime $date_inscription): void {
		$this->date_inscription = $date_inscription;
	}


	public function __toString(): string {
		return "Participation: [id_user: $this->id_user, id_event: $this->id_event, date_inscription: $this->date_inscription]";
	}   
}