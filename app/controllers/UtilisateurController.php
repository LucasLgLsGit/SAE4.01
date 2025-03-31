<?php

require_once './app/core/Controller.php';
require_once './app/repositories/UtilisateurRepository.php';
require_once './app/trait/FormTrait.php';
require_once './app/trait/AuthTrait.php';

class UtilisateurController extends Controller {

	use FormTrait;
	use AuthTrait;

	public function index()
	{
		$repository = new UtilisateurRepository();
		$utilisateurs = $repository->findAll();

		$this->view('/utilisateur/index.html.twig', ['utilisateurs' => $utilisateurs]);
	}

	public function create() {
		$data = $this->getAllPostParams();
		$errors = [];

		if (!empty($data)) {
			try {
				$errors = [];

				if (empty($data['mail']) || !filter_var($data['mail'], FILTER_VALIDATE_EMAIL)) {
					$errors[] = 'L\'email est invalide.';
				}
				if (empty($data['mdp']) || strlen($data['mdp']) < 6) {
					$errors[] = 'Le mot de passe doit contenir au moins 6 caractères.';
				}
				if (empty($data['nom'])) {
					$errors[] = 'Le nom est requis.';
				}
				if (empty($data['prenom'])) {
					$errors[] = 'Le prénom est requis.';
				}

				if (!empty($errors)) {
					throw new Exception(implode(', ', $errors));
				}

				$hashedPassword = $this->hash($data['mdp']);
				$user = new User(null, $data['mail'], $hashedPassword, 1, $data['nom'], $data['prenom']);

				$userRepo = new UtilisateurRepository();
				if (!$userRepo->create($user)) {
					throw new Exception('Erreur lors de la création de l\'utilisateur.');
				}

				$this->redirectTo('users.php');
			} catch (Exception $e) {
				$errrors = explode(', ', $e->getMessage());
			}
		}

		$this->view('/utilisateur/form.html.twig', [
			'errors' => $errors,
			'data' => $data
		]);
	}

	public function update()
	{
		$id = $this->getQueryParam('id');

		if ($id === null) {
			throw new Exception('ID utilisateur manquant.');
		}
		$repository = new UtilisateurRepository();
		$utilisateur = $repository->findById($id);

		if ($utilisateur === null) {
			throw new Exception('Utilisateur non trouvé.');
		}

		$data = array_merge([
			'mail' => $utilisateur->getMail(),
			'mdp' => $utilisateur->getMdp(),
			'permission' => $utilisateur->getPermission(),
			'nom' => $utilisateur->getNom(),
			'prenom' => $utilisateur->getPrenom()
		], $this->getAllPostParams());
		$errors = [];

		if (!empty($this->getAllPostParams())) {
			try {
				$errors = [];

				if (empty($data['mail']) || !filter_var($data['mail'], FILTER_VALIDATE_EMAIL)) {
					$errors[] = 'L\'email est invalide.';
				}
				if (empty($data['mdp']) || strlen($data['mdp']) < 6) {
					$errors[] = 'Le mot de passe doit contenir au moins 6 caractères.';
				}
				if (empty($data['nom'])) {
					$errors[] = 'Le nom est requis.';
				}
				if (empty($data['prenom'])) {
					$errors[] = 'Le prénom est requis.';
				}

				if (!empty($errors)) {
					throw new Exception(implode(', ', $errors));
				}

				$utilisateur->setMail($data['mail']);
				$utilisateur->setMdp($this->hash($data['mdp']));
				$utilisateur->setPermission($data['permission']);
				$utilisateur->setNom($data['nom']);
				$utilisateur->setPrenom($data['prenom']);

				if (!empty($data['mdp'])) {
					$hashedPassword = $this->hash($data['mdp']);
					$utilisateur->setMdp($hashedPassword);
				}

				if (!$repository->update($utilisateur)) {
					throw new Exception('Erreur lors de la mise à jour de l\'utilisateur.');
				}

				$this->redirectTo('users.php');
			} catch (Exception $e) {
				$errors = explode(', ', $e->getMessage());
			}
		}

		$this->view('/utilisateur/form.html.twig', [
			'errors' => $errors,
			'data' => $data,
			'id' => $id
		]);
	}
}