<?php

require_once './app/core/Controller.php';
require_once './app/repositories/CommandeRepository.php';

class CommandeController extends Controller
{
    private CommandeRepository $CommandeRepository;

    public function __construct()
    {
        $this->CommandeRepository = new CommandeRepository();
    }

    public function getCommandesByUser(int $id_user): array
    {
        return $this->CommandeRepository->findByUserId($id_user);
    }

    public function createCommande(array $commandeData)
    {
        try {
            if (empty($commandeData['id_user']) || empty($commandeData['id_produit']) || empty($commandeData['quantite']) || empty($commandeData['numero_commande'])) {
                throw new Exception('Données de commande incomplètes.');
            }

            $this->CommandeRepository->create($commandeData);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit;
        }
    }

    public function upsertCommande(array $commandeData)
    {
        try {
            if (empty($commandeData['id_user']) || empty($commandeData['id_produit']) || empty($commandeData['quantite']) || empty($commandeData['numero_commande'])) {
                throw new Exception('Données de commande incomplètes.');
            }

            // Vérifier si la combinaison id_user, id_produit, numero_commande existe déjà
            $id_user = $commandeData['id_user'];
            $id_produit = $commandeData['id_produit'];
            $numero_commande = $commandeData['numero_commande'];

            // Si elle existe, générer un nouveau numero_commande jusqu'à ce qu'il soit unique
            while ($this->CommandeRepository->exists($id_user, $id_produit, $numero_commande)) {
                $numero_commande = uniqid('CMD_');
            }

            $commandeData['numero_commande'] = $numero_commande;

            // Insérer la nouvelle commande
            $this->CommandeRepository->create($commandeData);

            return $numero_commande;
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit;
        }
    }

    public function updateCommande(int $id_user, int $id_produit, string $numero_commande)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $quantite = $_POST['quantite'] ?? null;

                if (empty($quantite) || $quantite <= 0) {
                    throw new Exception("La quantité doit être supérieure à 0.");
                }

                $commandeData = [
                    'id_user' => $id_user,
                    'id_produit' => $id_produit,
                    'quantite' => $quantite,
                    'numero_commande' => $numero_commande
                ];

                $this->CommandeRepository->update($commandeData);
                echo json_encode(['success' => true, 'message' => 'Commande mise à jour avec succès.']);
            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        } else {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
        }
    }

    public function deleteCommande(int $id_user, int $id_produit, string $numero_commande)
    {
        try {
            $this->CommandeRepository->delete($id_user, $id_produit, $numero_commande);
            $this->redirectTo('/commande/list');
        } catch (Exception $e) {
            http_response_code(400);
            echo "Erreur lors de la suppression de la commande : " . $e->getMessage();
        }
    }

    public function getCommandeDetails(int $id_user)
    {
        try {
            $commandes = $this->CommandeRepository->findByUserId($id_user);
            echo json_encode(['success' => true, 'commandes' => $commandes]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
?> 