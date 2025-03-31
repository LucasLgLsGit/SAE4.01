<?php

require_once './app/core/Repository.php';
require_once './app/entities/Image.php';

class ImageRepository {
    private $pdo;

    public function __construct() {
        $this->pdo = Repository::getInstance()->getPDO();
    }

    public function findAll(): array {
        $stmt = $this->pdo->query('SELECT * FROM "Image"');
        $images = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $images[] = $this->createImageFromRow($row);
        }
        return $images;
    }

    private function createImageFromRow(array $row): Image {
        return new Image($row['id_image'], $row['nom_image'], $row['chemin_image'], $row['id_produit']);
    }

    public function create(Image $image): void {
        $stmt = $this->pdo->prepare('INSERT INTO "Image" (nom_image, chemin_image, id_produit) VALUES (:nom_image, :chemin_image, :id_produit)');
        $stmt->bindParam(':nom_image', $image->getNom_image());
        $stmt->bindParam(':chemin_image', $image->getChemin_image());
        $stmt->bindParam(':id_produit', $image->getId_produit(), PDO::PARAM_INT);
        $stmt->execute();
    }

    public function update(Image $image): void {
        $stmt = $this->pdo->prepare('UPDATE "Image" SET nom_image = :nom_image, chemin_image = :chemin_image, id_produit = :id_produit WHERE id_image = :id_image');
        $stmt->bindParam(':nom_image', $image->getNom_image());
        $stmt->bindParam(':chemin_image', $image->getChemin_image());
        $stmt->bindParam(':id_produit', $image->getId_produit(), PDO::PARAM_INT);
        $stmt->bindParam(':id_image', $image->getId_image(), PDO::PARAM_INT);
        $stmt->execute();
    }

    public function findById(int $id): ?Image {
        $stmt = $this->pdo->prepare('SELECT * FROM "Image" WHERE id_image = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->createImageFromRow($row) : null;
    }

    public function delete(int $id): void {
        $stmt = $this->pdo->prepare('DELETE FROM "Image" WHERE id_image = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}