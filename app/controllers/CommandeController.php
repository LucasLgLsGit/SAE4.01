<?php

require_once '../services/CommandeService.php';

class CommandeController extends Controller
{
	private CommandeService $commandeService;

	public function __construct()
	{
		$this->commandeService = new CommandeService();
	}

	public function createCommande()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			try {
				$commandeData = [
					'id_user' => $_POST['id_user'],
					'id_produit' => $_POST['id_produit'],
					'quantite' => $_POST['quantite'],
					'numero_commande' => $_POST['numero_commande']
				];

				$this->commandeService->create($commandeData);
				$this->redirectTo('/commande/list'); 
			} catch (Exception $e) {
				http_response_code(400);
				echo "Erreur lors de la création de la commande : " . $e->getMessage();
			}
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
				$this->redirectTo('/commande/list');
			} catch (Exception $e) {
				http_response_code(400);
				echo "Erreur lors de la modification de la commande : " . $e->getMessage();
			}
		}
	}

	public function deleteCommande(int $id_user, int $id_produit)
	{
		try {
			$this->commandeService->delete($id_user, $id_produit);
			$this->redirectTo('/commande/list');
		} catch (Exception $e) {
			http_response_code(400);
			echo "Erreur lors de la suppression de la commande : " . $e->getMessage();
		}
	}
}