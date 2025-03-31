<?php

require_once '../repositories/ImageRepository.php';
require_once '../entities/Image.php';

class ImageService
{
	public function allImages(): array
	{
		$imageRepo = new ImageRepository();
		return $imageRepo->findAll();
	}

	public function create(array $data): Image
	{
		$errors = [];

		if (empty($data['nom_image'])) {
			$errors[] = "Le nom de l'image est requis !";
		}
		if (empty($data['chemin_image'])) {
			$errors[] = "Le chemin de l'image est requis !";
		}
		if (empty($data['id_produit'])) {
			$errors[] = "L'ID du produit est requis !";
		}

		if (!empty($errors)) {
			throw new Exception(implode(', ', $errors));
		}

		$image = new Image(
			0, // L'ID est auto-incrémenté
			$data['nom_image'],
			$data['chemin_image'],
			(int) $data['id_produit']
		);

		$imageRepo = new ImageRepository();
		$imageRepo->create($image);

		return $image;
	}

	public function update(int $id_image, array $data): bool
	{
		$imageRepo = new ImageRepository();
		$image = $imageRepo->findById($id_image);

		if (!$image) {
			throw new Exception("Image non trouvée.");
		}

		if (isset($data['nom_image'])) {
			$image->setNom_image($data['nom_image']);
		}
		if (isset($data['chemin_image'])) {
			$image->setChemin_image($data['chemin_image']);
		}
		if (isset($data['id_produit'])) {
			$image->setId_produit((int) $data['id_produit']);
		}

		$imageRepo->update($image);
		return true;
	}

	public function delete(int $id_image): bool
	{
		$imageRepo = new ImageRepository();
		$image = $imageRepo->findById($id_image);

		if (!$image) {
			throw new Exception("Image non trouvée.");
		}

		$imageRepo->delete($id_image);
		return true;
	}
}
