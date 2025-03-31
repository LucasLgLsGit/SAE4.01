<?php

require_once '../repositories/CommandeRepository.php';
require_once '/../entities/Commande.php';

class CommandeService
{
	public function allCommandes()
	{
		$commandeRepo = new CommandeRepository();
		$commandes = $commandeRepo->findAll();
	}

	public function create(array $data): Commande
	{
		$error = [];

		if(empty($data['id_user']))
		{
			$error = ["L'identifiant utilisateur (id_user) est requis !"];
		}
		if(empty($data['id_produit']))
		{
			$error = ["L'identifiant produit (id_produit) est requis !"];
		}
		if(empty($data['quantite']) || $data['quantite'] < 0)
		{
			$error = ["La quantité doit être supérieur à 0"];
		}
		if(empty($data['numero_commande']))
		{
			$error = ["Le numéro de commande ne peut pas être nul"];
		}

		if (!empty($errors)) {
            throw new Exception(implode(', ', $errors));
        }

		$commande = new Commande($data['id_user'], $data['id_produit'], $data['quantite'], $data['numero_commande']);

		
		$commandeRepo = new CommandeRepository();
		if(!$commandeRepo->create($commande)) {
			throw new Exception("La création de la commande a échoué.");
		}

		return $commande;

	} 
}

?>