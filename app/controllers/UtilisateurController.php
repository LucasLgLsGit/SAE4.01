<?php

require_once './app/core/Controller.php';
require_once './app/services/UtilisateurService.php';
require_once './app/services/AuthService.php';
require_once './app/trait/FormTrait.php';
require_once './app/trait/AuthTrait.php';

class UtilisateurController extends Controller {

	use FormTrait;
	use AuthTrait;

	public function index()
	{
		$isLoggedIn = $this->isLoggedIn();
		$user = $this->getCurrentUser();
		$isAdmin = $user && $user->isAdmin();

		if ($isLoggedIn && $user) {
			$repository = new UtilisateurRepository();
			$utilisateur = $repository->findById($user->getId());
		} else {
			$utilisateur = null;
		}

		$this->view('/user/profile.html.twig', [
			'utilisateur' => $utilisateur,
			'isLoggedIn' => $isLoggedIn,
			'isAdmin' => $isAdmin
		]);
	}

	public function create() {
		$data = $this->getAllPostParams();
		$errors = [];

		if (!empty($data)) {
			try {
				$userService = new UtilisateurService();
				$userService->create($data);
				$this->redirectTo('index.php');
			} catch (Exception $e) {
				$errors = explode(', ', $e->getMessage());
			}
		}

		$this->view('/user/signUp.html.twig', ['errors' => $errors, 'data' => $data]);
	}

	public function update()
	{
		$id = $this->getPostParam('id_user');
		$nom = $this->getPostParam('nom');
		$prenom = $this->getPostParam('prenom');
		$mail = $this->getPostParam('mail');

		if (empty($id) || empty($nom) || empty($prenom) || empty($mail)) {
			echo json_encode(['success' => false, 'message' => 'Tous les champs sont requis.']);
			http_response_code(400);
			return;
		}

		try {
			$userRepository = new UtilisateurRepository();
			$userRepository->updateById($id, [
				'nom' => $nom,
				'prenom' => $prenom,
				'mail' => $mail,
			]);
			echo json_encode(['success' => true, 'message' => 'Utilisateur mis à jour avec succès.']);
		} catch (Exception $e) {
			http_response_code(500);
			echo json_encode(['success' => false, 'message' => $e->getMessage()]);
		}
	}

	public function deleteUser()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$id = filter_input(INPUT_POST, 'id_user', FILTER_VALIDATE_INT);

			if ($id === null || $id === false) {
				echo json_encode(['success' => false, 'message' => "L'identifiant utilisateur est requis !"]);
				http_response_code(400);
				return;
			}

			try {
				$userRepository = new UtilisateurRepository();
				$userRepository->deleteById($id); // Supprime l'utilisateur de la base de données
				echo json_encode(['success' => true, 'message' => 'Utilisateur supprimé avec succès.']);
			} catch (Exception $e) {
				http_response_code(500);
				echo json_encode(['success' => false, 'message' => $e->getMessage()]);
			}
		} else {
			http_response_code(405);
			echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
		}
	}

	public function updateMail() {
		$id = $this->getPostParam('id_user');
		$newMail = $this->getPostParam('new_mail');
	
		if (empty($id) || empty($newMail)) {
			throw new Exception("L'identifiant utilisateur et le nouvel email sont requis !");
		}
	
		$isLoggedIn = $this->isLoggedIn();

		try {
			$userService = new UtilisateurService();
			$userService->updateEmail($id, $newMail);
			
			$authService = new AuthService();
			if ($authService->getUser()->getId() == $id) {
				$user = $authService->getUser();
				$user->setMail($newMail);
				$authService->setUser($user);
			}
			

			$this->view('/user/profile.html.twig', [
                'utilisateur' => $authService->getUser(),
                'success' => 'Email modifié avec succès',
				'isLoggedIn' => $isLoggedIn
            ]);
		} catch (Exception $e) {
			$this->view('/user/profile.html.twig', [
				'errors' => [$e->getMessage()],
				'utilisateur' => (new AuthService())->getUser(),
				'isLoggedIn' => $isLoggedIn
			]);
		}
	}

	public function updateMdp()
	{
		$id = $this->getPostParam('id_user');
		$newPassword = $this->getPostParam('new_mdp');
		$confirmPassword = $this->getPostParam('confirm_new_mdp');

		$isLoggedIn = $this->isLoggedIn();

		try {
			if ($newPassword !== $confirmPassword) {
				throw new Exception("Les mots de passe ne correspondent pas");
			}

			


			$userService = new UtilisateurService();
			if ($userService->updateMdp($id, $newPassword)) {
				// Succès
				$this->view('/user/profile.html.twig', [
					'utilisateur' => (new AuthService())->getUser(),
					'success' => 'Mot de passe modifié avec succès',
					'isLoggedIn' => $isLoggedIn
				]);
				return;
			}
			
		} catch (Exception $e) {
			$this->view('/user/profile.html.twig', [
				'utilisateur' => (new AuthService())->getUser(),
				'error' => $e->getMessage(),
				'isLoggedIn' => $isLoggedIn
			]);
		}
	}
}