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
		$isLoggedIn = $this->isLoggedIn();
		$user = $this->getCurrentUser();
		$isAdmin = $user && $user->isAdmin();

		try {
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

	private function getQueryParam(string $key): ?string
	{
		return $_GET[$key] ?? null;
	}

    public function detail()
{
    try {
        $id = $this->getQueryParam('id');
        if (!$id) {
            throw new Exception("L'identifiant du produit est requis !");
        }

        $produit = $this->produitService->findById((int)$id);

        if (!$produit) {
            throw new Exception("Produit non trouvé !");
        }

        $this->view('/shop/detail.html.twig', [
            'title' => 'Détail du Produit',
            'produit' => $produit
        ]);
    } catch (Exception $e) {
        $this->view('error.html.twig', [
            'title' => 'Erreur',
            'message' => $e->getMessage()
        ]);
    }
}
}