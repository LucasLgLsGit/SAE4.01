<?php
require_once './app/core/Controller.php';
require_once './app/repositories/ProduitRepository.php';
require_once './app/controllers/ImageController.php';
require_once './app/trait/FormTrait.php';

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
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->getAllPostParams();
            $errors = [];

            try {
                if (empty($data['nom'])) $errors[] = "Le nom du produit est requis.";
                if (empty($data['description'])) $errors[] = "La description du produit est requise.";
                if (empty($data['prix']) || !is_numeric($data['prix'])) $errors[] = "Le prix du produit est requis et doit être un nombre.";
                if (empty($data['couleurs']) || !is_array($data['couleurs'])) $errors[] = "Au moins une couleur est requise.";
                if (empty($data['stock']) || !is_array($data['stock'])) $errors[] = "Les stocks sont requis.";

                if (!empty($errors)) {
                    throw new Exception(implode(', ', $errors));
                }

                $createdProductIds = [];

                foreach ($data['couleurs'] as $colorIndex => $color) {
                    if (!isset($data['stock'][$colorIndex])) {
                        echo "Aucun stock pour la couleur $color\n";
                        continue;
                    }

                    foreach ($data['stock'][$colorIndex] as $sizeIndex => $stock) {
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
                            $product = $this->produitRepo->create($productData);
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

    public function deleteProduct()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $idProduit = $_POST['id_produit'] ?? null;
    
                if (!$idProduit) {
                    throw new Exception("L'identifiant du produit est requis !");
                }
    
                $this->produitRepo->delete($idProduit);
    
                $this->redirectTo('/products_admin.php');
            } catch (Exception $e) {
                http_response_code(400);
                $this->view('/admin/shopAdmin.html.twig', [
                    'errors' => [$e->getMessage()],
                    'title' => 'Gestion des produits'
                ]);
            }
        } else {
            http_response_code(405);
            echo "Méthode non autorisée.";
        }
    }

    public function updateProduct()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = $this->getAllPostParams();
                
                if (empty($data['id_produit'])) {
                    throw new Exception("ID produit manquant");
                }
    
                $productData = [
                    'id_produit' => (int)$data['id_produit'],
                    'titre_produit' => $data['nom'],
                    'description_produit' => $data['description'],
                    'prix' => (float)$data['prix'],
                    'couleur' => $data['couleur'],
                    'taille' => $data['taille'],
                    'stock' => (int)$data['stock']
                ];
    
                $success = $this->produitRepo->update($productData);
                
                if (!$success) {
                    throw new Exception("Échec de la mise à jour");
                }
    
                $this->redirectTo('/products_admin.php');
    
            } catch (Exception $e) {
                http_response_code(400);
                $this->view('/admin/shopAdmin.html.twig', [
                    'errors' => [$e->getMessage()],
                    'title' => 'Erreur modification'
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