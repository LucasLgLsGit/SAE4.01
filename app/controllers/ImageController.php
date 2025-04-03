<?php
require_once './app/core/Controller.php';
require_once './app/repositories/ImageRepository.php';

class ImageController extends Controller
{
    private ImageRepository $imageRepo;

    public function __construct()
    {
        $this->imageRepo = new ImageRepository();
    }

    public function createImage(array $file, int $id_produit): void
    {
        if (empty($file['name']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
            return; // Pas d'image uploadée
        }

        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileError = $file['error'];

        if ($fileError !== UPLOAD_ERR_OK) {
            throw new Exception("Erreur d'upload de l'image (code : $fileError).");
        }

        $uploadDir = 'assets/images/products/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $uniqueFileName = uniqid() . '_' . $fileName; // Nom unique pour le fichier
        $destination = $uploadDir . $uniqueFileName;

        if (!move_uploaded_file($fileTmpName, $destination)) {
            throw new Exception("Erreur lors du déplacement de l'image.");
        }

        $imageData = [
            'nom_image' => $uniqueFileName, // Nom unique stocké dans la base
            'id_produit' => $id_produit
        ];

        $this->imageRepo->create($imageData);
    }
}