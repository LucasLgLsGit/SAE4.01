<?php

require_once '../repositories/ActualiteRepository.php';
require_once '../entities/Actualite.php';

class ActualiteService
{
	public function allActualites(): array
	{
		$actualiteRepo = new ActualiteRepository();
		return $actualiteRepo->findAll();
	}

	public function create(array $data): Actualite
	{
		$errors = [];

		if (empty($data['titre_article'])) {
			$errors[] = "Le titre de l'article est requis !";
		}
		if (empty($data['contenu'])) {
			$errors[] = "Le contenu de l'article est requis !";
		}
		if (empty($data['date_publication'])) {
			$errors[] = "La date de publication est requise !";
		}
		if (empty($data['id_user'])) {
			$errors[] = "L'identifiant utilisateur (id_user) est requis !";
		}

		if (!empty($errors)) {
			throw new Exception(implode(', ', $errors));
		}

		$actualite = new Actualite(
			null,
			$data['titre_article'],
			$data['contenu'],
			new DateTime($data['date_publication']),
			(int) $data['id_user']
		);

		$actualiteRepo = new ActualiteRepository();
		if (!$actualiteRepo->create($actualite)) {
			throw new Exception("La création de l'actualité a échoué.");
		}

		return $actualite;
	}
}
