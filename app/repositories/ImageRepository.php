<?php

require_once './app/core/Repository.php';
require_once './app/entities/Image.php';

class ImageRepository
{
	private $pdo;

	public function __construct()
	{
		$this->pdo = Repository::getInstance()->getPDO();
	}

	public function findAll(): array
	{
		$stmt = $this->pdo->query('SELECT * FROM "image"');
		$images = [];
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$images[] = $this->createImageFromRow($row);
		}
		return $images;
	}

	public function create(array $data): Image
	{
		$errors = [];

		if (empty($data['nom_image']))
		{
			$errors[] = "Le nom de l'image est requis !";
		}
		if (empty($data['chemin_image']))
		{
			$errors[] = "Le chemin de l'image est requis !";
		}
		if (empty($data['id_produit']))
		{
			$errors[] = "L'ID du produit est requis !";
		}

		if (!empty($errors))
		{
			throw new Exception(implode(', ', $errors));
		}

		$image = new Image(
			null,
			$data['nom_image'],
			$data['chemin_image'],
			(int) $data['id_produit']
		);

		$stmt = $this->pdo->prepare('
			INSERT INTO "image" (nom_image, chemin_image, id_produit) 
			VALUES (:nom_image, :chemin_image, :id_produit)
		');

		$success = $stmt->execute([
			'nom_image' => $image->getNom_image(),
			'chemin_image' => $image->getChemin_image(),
			'id_produit' => $image->getId_produit()
		]);

		if (!$success)
		{
			throw new Exception("La création de l'image a échoué.");
		}

		$image->setId_image((int) $this->pdo->lastInsertId());

		return $image;
	}

	private function createImageFromRow(array $row): Image
	{
		return new Image(
			$row['id_image'],
			$row['nom_image'],
			$row['chemin_image'],
			$row['id_produit']
		);
	}

	public function update(int $id_image, array $data): bool
	{
		$image = $this->findById($id_image);

		if (!$image)
		{
			throw new Exception("Image non trouvée.");
		}

		if (isset($data['nom_image']) && !empty($data['nom_image']))
		{
			$image->setNom_image($data['nom_image']);
		}
		else if (isset($data['nom_image']))
		{
			throw new Exception("Le nom de l'image est requis !");
		}

		if (isset($data['chemin_image']) && !empty($data['chemin_image']))
		{
			$image->setChemin_image($data['chemin_image']);
		}
		else if (isset($data['chemin_image']))
		{
			throw new Exception("Le chemin de l'image est requis !");
		}

		if (isset($data['id_produit']) && !empty($data['id_produit']))
		{
			$image->setId_produit((int) $data['id_produit']);
		}
		else if (isset($data['id_produit']))
		{
			throw new Exception("L'ID du produit est requis !");
		}

		$stmt = $this->pdo->prepare('
			UPDATE "image" 
			SET nom_image = :nom_image, chemin_image = :chemin_image, id_produit = :id_produit 
			WHERE id_image = :id_image
		');

		$success = $stmt->execute([
			':nom_image' => $image->getNom_image(),
			':chemin_image' => $image->getChemin_image(),
			':id_produit' => $image->getId_produit(),
			':id_image' => $id_image
		]);

		if (!$success)
		{
			throw new Exception("La mise à jour de l'image a échoué.");
		}

		return true;
	}

	public function delete(int $id_image): bool
	{
		$image = $this->findById($id_image);

		if (!$image)
		{
			throw new Exception("Image non trouvée.");
		}

		$stmt = $this->pdo->prepare('DELETE FROM "image" WHERE id_image = :id');
		$success = $stmt->execute([':id' => $id_image]);

		if (!$success)
		{
			throw new Exception("La suppression de l'image a échoué.");
		}

		return true;
	}

	public function findById(int $id): ?Image
	{
		$stmt = $this->pdo->prepare('SELECT * FROM "image" WHERE id_image = :id');
		$stmt->execute([':id' => $id]);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row ? $this->createImageFromRow($row) : null;
	}
}