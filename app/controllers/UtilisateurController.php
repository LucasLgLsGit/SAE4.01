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

	public function update() {
		$id = $this->getQueryParam('id_user');

		if ($id === null) {
			throw new Exception("L'identifiant utilisateur est requis !");
		}

		$data = $this->getAllPostParams();
		$errors = [];

		if (!empty($data)) {
			try {
				$userService = new UtilisateurService();
				$userService->update($id, $data);
				$this->redirectTo('utilisateurs.php');
			} catch (Exception $e) {
				$errors = explode(', ', $e->getMessage());
			}
		}

		$this->view('/user/profile.html.twig', 'Modification d\'un utilisateur', ['errors' => $errors, 'data' => $data, 'id_user' => $id]);
	}

	public function updateMail() {
		$id = $this->getPostParam('id_user');
		$newMail = $this->getPostParam('new_mail');
	
		if (empty($id) || empty($newMail)) {
			throw new Exception("L'identifiant utilisateur et le nouvel email sont requis !");
		}
	
		try {
			$userService = new UtilisateurService();
			$userService->updateEmail($id, $newMail);
			
			$authService = new AuthService();
			if ($authService->getUser()->getId() == $id) {
				$user = $authService->getUser();
				$user->setMail($newMail);
				$authService->setUser($user);
			}
			
			$this->redirectTo('profile.php');
		} catch (Exception $e) {
			$this->view('/user/profile.html.twig', [
				'errors' => [$e->getMessage()],
				'utilisateur' => (new AuthService())->getUser()
			]);
		}
	}
}