<?php

require_once './app/core/Controller.php';
require_once './app/repositories/ProduitRepository.php';
require_once './app/trait/FormTrait.php';

class ProductController extends Controller
{
	use FormTrait;

	private ProduitRepository $produitRepo;

	public function __construct()
	{
		$this->produitRepo = new ProduitRepository();
	}

	public function createProduct()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = $this->getAllPostParams();
        $errors = [];

        try {
            // Validation des données de base
            if (empty($data['nom'])) {
                $errors[] = "Le nom du produit est requis.";
            }
            if (empty($data['description'])) {
                $errors[] = "La description du produit est requise.";
            }
            if (empty($data['prix']) || !is_numeric($data['prix'])) {
                $errors[] = "Le prix du produit est requis et doit être un nombre.";
            }
            if (empty($data['couleurs']) || !is_array($data['couleurs'])) {
                $errors[] = "Au moins une couleur est requise.";
            }
            if (empty($data['stock']) || !is_array($data['stock'])) {
                $errors[] = "Les stocks sont requis.";
            }

            if (!empty($errors)) {
                throw new Exception(implode(', ', $errors));
            }

			echo "<pre>";
			print_r($data['stock']);
			echo "</pre>";

			// Parcourir les couleurs et les stocks
            foreach ($data['couleurs'] as $colorIndex => $color) {
                if (!isset($data['stock'][$colorIndex])) {
                    continue;
                }

                foreach ($data['stock'][0][$colorIndex] as $sizeIndex => $stock) {
                    if ($stock > 0) {
						$productData = [
							'titre_produit' => $data['nom'],
							'description_produit' => $data['description'],
							'prix' => (float)$data['prix'],
							'date_produit' => date('Y-m-d H:i:s'),
							'couleur' => $color,
							'taille' => $this->getSizeFromIndex($sizeIndex),
							'stock' => (int)$stock,
							'id_user' => 2
						];


                        $this->produitRepo->create($productData);
                    }
                }
            }
            $this->redirectTo('/products_admin.php');
        } catch (Exception $e) {
            $this->view('/admin/shopAdmin.html.twig', [
                'errors' => explode(', ', $e->getMessage()),
                'data' => $data,
                'title' => 'Création d\'un produit'
            ]);
        }
    } else {
        http_response_code(405);
        echo "Méthode non autorisée.";
    }
}

private function getSizeFromIndex($index)
{
    $sizes = ['S', 'M', 'L', 'XL'];
    return $sizes[$index] ?? 'Inconnu';
}
}