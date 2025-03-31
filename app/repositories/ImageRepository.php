<?php

require_once './app/core/Repository.php';
require_once './app/entities/Image.php';

class ImageRepository {
    private $pdo;

    public function __construct() {
        $this->pdo = Repository::getInstance()->getPDO();
    }

    public function findAll(): array {
        $stmt = $this->pdo->query('SELECT * FROM "image"');
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
        $stmt = $this->pdo->prepare('INSERT INTO "image" (nom_image, chemin_image, id_produit) VALUES (:nom_image, :chemin_image, :id_produit)');
        $stmt->execute([
            ':nom_image' => $image->getNom_image(),
            ':chemin_image' => $image->getChemin_image(),
            ':id_produit' => $image->getId_produit()
        ]);
    }

    public function update(Image $image): void {
        $stmt = $this->pdo->prepare('UPDATE "image" SET nom_image = :nom_image, chemin_image = :chemin_image, id_produit = :id_produit WHERE id_image = :id_image');
        $stmt->execute([
            ':nom_image' => $image->getNom_image(),
            ':chemin_image' => $image->getChemin_image(),
            ':id_produit' => $image->getId_produit(),
            ':id_image' => $image->getId_image()
        ]);
    }

    public function findById(int $id): ?Image {
        $stmt = $this->pdo->prepare('SELECT * FROM "image" WHERE id_image = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->createImageFromRow($row) : null;
    }

    public function delete(int $id): void {
        $stmt = $this->pdo->prepare('DELETE FROM "image" WHERE id_image = :id');
        $stmt->execute([':id' => $id]);
    }
}