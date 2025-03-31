<?php

require_once '../repositories/ParticipationRepository.php';
require_once '../entities/Participation.php';

class ParticipationService
{
	public function allParticipations(): array
	{
		$participationRepo = new ParticipationRepository();
		return $participationRepo->findAll();
	}

	public function create(array $data): Participation
	{
		$errors = [];

		if (empty($data['id_user'])) {
			$errors[] = "L'identifiant utilisateur (id_user) est requis !";
		}
		if (empty($data['id_event'])) {
			$errors[] = "L'identifiant de l'événement (id_event) est requis !";
		}
		if (empty($data['date_inscription'])) {
			$errors[] = "La date d'inscription est requise !";
		}

		if (!empty($errors)) {
			throw new Exception(implode(', ', $errors));
		}

		$participation = new Participation(
			(int) $data['id_user'],
			(int) $data['id_event'],
			new DateTime($data['date_inscription'])
		);

		$participationRepo = new ParticipationRepository();
		$participationRepo->create($participation);

		return $participation;
	}
}
