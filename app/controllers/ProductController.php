<?php
require_once './app/core/Controller.php';
require_once './app/repositories/ProduitRepository.php';
require_once './app/controllers/ImageController.php';
require_once './app/trait/FormTrait.php';

function prettyDump($var, $label = '') {
    echo "<pre>";
    if ($label) echo "$label:\n";
    print_r($var);
    echo "</pre>";
}

class ProductController extends Controller
{
    use FormTrait;

    private ProduitRepository $produitRepo;
    private ImageController $imageController;

    public function __construct()
    {
        $this->produitRepo = new ProduitRepository();
        $this->imageController = new ImageController();
    }

    public function createProduct()
    {
        echo "Début de createProduct\n";
        prettyDump($_SERVER['REQUEST_METHOD'], 'Méthode HTTP');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->getAllPostParams();
            $errors = [];

            try {
                if (empty($data['nom'])) $errors[] = "Le nom du produit est requis.";
                if (empty($data['description'])) $errors[] = "La description du produit est requise.";
                if (empty($data['prix']) || !is_numeric($data['prix'])) $errors[] = "Le prix du produit est requis et doit être un nombre.";
                if (empty($data['couleurs']) || !is_array($data['couleurs'])) $errors[] = "Au moins une couleur est requise.";
                if (empty($data['stock']) || !is_array($data['stock'])) $errors[] = "Les stocks sont requis.";

                prettyDump($errors, 'Erreurs de validation');
                prettyDump($data, 'Données reçues');
                prettyDump($_FILES, 'Fichiers uploadés');

                if (!empty($errors)) {
                    throw new Exception(implode(', ', $errors));
                }

                $createdProductIds = [];

                // Création des produits
                foreach ($data['couleurs'] as $colorIndex => $color) {
                    if (!isset($data['stock'][$colorIndex])) {
                        echo "Aucun stock pour la couleur $color\n";
                        continue;
                    }

                    prettyDump($data['stock'][$colorIndex], "Stocks pour la couleur $color (index $colorIndex)");

                    foreach ($data['stock'][$colorIndex] as $sizeIndex => $stock) {
                        prettyDump($sizeIndex, "Index de taille");
                        prettyDump($stock, "Stock pour cette taille");
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
                            prettyDump($productData, "Produit à créer (couleur: $color, taille: " . $this->getSizeFromIndex($sizeIndex) . ")");
                            $product = $this->produitRepo->create($productData);
                            echo "Produit créé pour $color / " . $this->getSizeFromIndex($sizeIndex) . " (ID: " . $product->getId_produit() . ")\n";
                            $createdProductIds[] = $product->getId_produit();
                        }
                    }
                }

                if (!empty($_FILES['images']['name'][0])) {
                    $firstProductId = $createdProductIds[0]; 
                    foreach ($_FILES['images']['name'] as $key => $name) {
                        if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
                            $file = [
                                'name' => $_FILES['images']['name'][$key],
                                'tmp_name' => $_FILES['images']['tmp_name'][$key],
                                'error' => $_FILES['images']['error'][$key]
                            ];
                            $this->imageController->createImage($file, $firstProductId);
                            echo "Image créée pour le produit ID $firstProductId\n";
                        }
                    }
                }

                echo "Tous les produits et l'image ont été créés avec succès\n";
                $this->redirectTo('/products_admin.php');
            } catch (Exception $e) {
                prettyDump($e->getMessage(), 'Exception');
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