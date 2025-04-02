<?php

require_once './app/repositories/ParticipationRepository.php';
require_once './app/entities/Participation.php';

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

	public function update(array $data): bool
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
		if (!$participationRepo->update($participation)) {
			throw new Exception("La mise à jour de la participation a échoué.");
		}

		return true;
	}

	public function delete(int $id_user, int $id_event): bool
	{
		$participationRepo = new ParticipationRepository();

		$result = $participationRepo->delete($id_user, $id_event);

		if (!$result) {
			throw new Exception("Erreur : impossible de supprimer la participation avec l'utilisateur ID $id_user et l'événement ID $id_event.");
		}

		return $result;
	}

	public function isUserRegistered(int $idEvent, int $idUser): bool
	{
		$participationRepo = new ParticipationRepository();
		return $participationRepo->findById($idEvent, $idUser);
	}
}