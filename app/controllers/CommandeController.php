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

	public function createCommande(array $commandeData)
	{
		try {
			if (empty($commandeData['id_user']) || empty($commandeData['id_produit']) || empty($commandeData['quantite'])) {
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
        if (empty($commandeData['id_user']) || empty($commandeData['id_produit']) || empty($commandeData['quantite'])) {
            throw new Exception('Données de commande incomplètes.');
        }

        // Vérifier si la commande existe déjà
        $existingCommande = $this->CommandeRepository->findById($commandeData['id_user'], $commandeData['id_produit']);

        if ($existingCommande) {
            // Mettre à jour la commande existante
            $this->CommandeRepository->update($commandeData);
        } else {
            // Créer une nouvelle commande
            $this->CommandeRepository->create($commandeData);
        }

        echo json_encode(['success' => true, 'message' => 'Commande créée ou mise à jour avec succès.']);
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}


	public function updateCommande(int $id_user, int $id_produit)
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
					'quantite' => $quantite
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

	public function deleteCommande(int $id_user, int $id_produit)
	{
		try {
			$this->CommandeRepository->delete($id_user, $id_produit);
			$this->redirectTo('/commande/list');
		} catch (Exception $e) {
			http_response_code(400);
			echo "Erreur lors de la suppression de la commande : " . $e->getMessage();
		}
	}
}