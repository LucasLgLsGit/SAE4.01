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

            $produitsGroupes = [];
            foreach ($produits as $produit) {
                $titre = strtolower($produit->getTitre_produit());
                if (!isset($produitsGroupes[$titre])) {
                    $produitsGroupes[$titre] = $produit;
                }
            }

            $isLoggedIn = $this->isLoggedIn();
            $user = $this->getCurrentUser();
            $isAdmin = $user && $user->isAdmin();

            $this->view('/shop/index.html.twig', [
                'title' => 'Liste des Produits',
                'produitsGroupes' => $produitsGroupes,
                'isLoggedIn' => $isLoggedIn,
				'isAdmin' => $isAdmin
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

            $allProduits = $this->produitService->allProduits();
            $produitsWithSameTitre = [];
            $representativeProduit = null;

            foreach ($allProduits as $produit) {
                if (strtolower($produit->getTitre_produit()) === strtolower($titre)) {
                    if (!$representativeProduit) {
                        $representativeProduit = $produit;
                    }
                    $produitsWithSameTitre[] = $produit;
                }
            }

            if (!$representativeProduit) {
                throw new Exception("Produit non trouvé !");
            }

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

            usort($tailles, function ($a, $b) {
                $order = ['XXS','XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'];
                return array_search($a, $order) - array_search($b, $order);
            });

            $isLoggedIn = $this->isLoggedIn();
            $user = $this->getCurrentUser();
            $isAdmin = $user && $user->isAdmin();

            $this->view('/shop/detail.html.twig', [
                'title' => 'Détail du Produit',
                'produit' => $representativeProduit,
                'tailles' => $tailles,
                'couleurs' => $couleurs,
                'isLoggedIn' => $isLoggedIn,
				'isAdmin' => $isAdmin
            ]);
        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }
}