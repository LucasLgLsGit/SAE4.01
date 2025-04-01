<?php
require_once './app/core/Controller.php';
require_once './app/services/ProduitService.php';

class ShopController extends Controller
{
    private ProduitService $produitService;

    public function __construct()
    {
        $this->produitService = new ProduitService();
    }

    public function index()
    {
        try {
            $produits = $this->produitService->allProduits();

            // Regrouper les produits par titre
            $produitsGroupes = [];
            foreach ($produits as $produit) {
                $titre = strtolower($produit->getTitre_produit());
                if (!isset($produitsGroupes[$titre])) {
                    $produitsGroupes[$titre] = $produit;
                }
            }

            // Passez les données à la vue
            $this->view('/shop/index.html.twig', [
                'title' => 'Liste des Produits',
                'produitsGroupes' => $produitsGroupes
            ]);
        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }

    private function getQueryParam(string $key): ?string
    {
        return $_GET[$key] ?? null;
    }

    public function detail()
    {
        try {
            $titre = $this->getQueryParam('titre');
            if (!$titre) {
                throw new Exception("Le titre du produit est requis !");
            }

            // Fetch all products to find those with the same title
            $allProduits = $this->produitService->allProduits();
            $produitsWithSameTitre = [];
            $representativeProduit = null;

            // Group products by the given title and collect sizes and colors
            foreach ($allProduits as $produit) {
                if (strtolower($produit->getTitre_produit()) === strtolower($titre)) {
                    if (!$representativeProduit) {
                        $representativeProduit = $produit; // Use the first product as the representative
                    }
                    $produitsWithSameTitre[] = $produit;
                }
            }

            if (!$representativeProduit) {
                throw new Exception("Produit non trouvé !");
            }

            // Aggregate sizes and colors
            $tailles = [];
            $couleurs = [];
            foreach ($produitsWithSameTitre as $produit) {
                $taille = $produit->getTaille();
                $couleur = $produit->getCouleur();
                if (!in_array($taille, $tailles)) {
                    $tailles[] = $taille;
                }
                if (!in_array($couleur, $couleurs)) {
                    $couleurs[] = $couleur;
                }
            }

            $this->view('/shop/detail.html.twig', [
                'title' => 'Détail du Produit',
                'produit' => $representativeProduit, // Representative product for title, price, etc.
                'tailles' => $tailles,               // All available sizes
                'couleurs' => $couleurs              // All available colors
            ]);
        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }
}