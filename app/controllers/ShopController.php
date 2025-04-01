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

    // Afficher tous les produits
    public function index()
    {
        try {
            // Récupérer tous les produits via le service
            $produits = $this->produitService->allProduits();

            $this->view('/shop/index.html.twig', [
                'title' => 'Liste des Produits',
                'produits' => $produits
            ]);
        } catch (Exception $e) {
            $this->view('error.html.twig', [
                'title' => 'Erreur',
                'message' => $e->getMessage()
            ]);
        }
    }
}