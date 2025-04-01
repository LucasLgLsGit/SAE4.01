<?php

//TODO: Faire tout les redirections avec les $this->view


require_once '../services/CommandeService.php';

class CommandeController
{
    private CommandeService $commandeService;
    private CommandeRepository $commandeRepository;

    public function __construct()
    {
        $this->commandeService = new CommandeService();
    }

    public function listCommandes()
    {
        try {
            $commandes = $this->commandeService->allCommandes();
            $this->view('commande/list.html.twig', 'Liste des commandes', ['commandes' => $commandes]);
        } catch (Exception $e) {
            $this->view('error.html.twig', 'Erreur', ['message' => $e->getMessage()]);
        }
    }

    public function createCommande()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $commande = $this->commandeService->create($_POST);
                header("Location: /commande/list");
                exit();
            } catch (Exception $e) {
                //$this->view('commande/create.html.twig', 'Création d\'une commande', ['errors' => $e->getMessage()]);
            }
        } else {
            //$this->view('commande/create.html.twig', 'Création d\'une commande');
        }
    }

    public function updateCommande(int $id_user, int $id_produit)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $commandeData = [
                'id_user' => $id_user,
                'id_produit' => $id_produit,
                'quantite' => $_POST['quantite'],
                'numero_commande' => $_POST['numero_commande']
            ];

            $this->commandeService->update($commandeData);

            header("Location: /commande/list");
            exit();
        } catch (Exception $e) {
            $this->view('commande/update.html.twig', 'Modification d\'une commande', [
                'errors' => $e->getMessage(),
                'data' => $_POST,
                'id_user' => $id_user,
                'id_produit' => $id_produit
            ]);
        }
    } else {
        $commande = $this->commandeRepository->findById($id_user, $id_produit);
        //$this->view('commande/update.html.twig', 'Modification d\'une commande', ['commande' => $commande]);
    }
}

    public function deleteCommande(int $id_user, int $id_produit)
    {
        try {
            $this->commandeService->delete($id_user, $id_produit);
            header("Location: /commande/list");
            exit();
        } catch (Exception $e) {
            //$this->view('error.html.twig', 'Erreur', ['message' => $e->getMessage()]);
        }
    }

    private function view(string $template, string $title, array $data = [])
    {
        extract($data);
        require_once "../views/$template";
    }
}

?>
