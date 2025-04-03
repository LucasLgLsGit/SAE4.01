<?php

require_once './app/core/Controller.php';
require_once './app/repositories/UtilisateurRepository.php';
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
			'title' => 'Mon profil',
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
				$userRepo = new UtilisateurRepository();
				$userRepo->create($data);
				$this->redirectTo('index.php');
			} catch (Exception $e) {
				$errors = explode(', ', $e->getMessage());
			}
		}

		$this->view('/user/signUp.html.twig', ['errors' => $errors, 'data' => $data, 'title' => 'Inscription']);
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

			if (!isset($_POST['id_user'])) {
				throw new Exception("L'identifiant utilisateur est requis !");
				$this->redirectTo('users_admin.php');
			}

			$id = (int)$_POST['id_user'];
			$userRepository = new UtilisateurRepository();
			$userRepository->deleteById($id);
			$this->redirectTo('users_admin.php');
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
			$userRepo = new UtilisateurRepository();
			$userRepo->updateEmail($id, $newMail);
			
			$authService = new AuthService();
			if ($authService->getUser()->getId() == $id) {
				$user = $authService->getUser();
				$user->setMail($newMail);
				$authService->setUser($user);
			}
			

			$this->view('/user/profile.html.twig', [
				'title' => 'Mon profil',
                'utilisateur' => $authService->getUser(),
                'success' => 'Email modifié avec succès',
				'isLoggedIn' => $isLoggedIn
            ]);
		} catch (Exception $e) {
			$this->view('/user/profile.html.twig', [
				'title' => 'Mon profil',
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

			


			$userRepo = new UtilisateurRepository();
			if ($userRepo->updateMdp($id, $newPassword)) {
				// Succès
				$this->view('/user/profile.html.twig', [
					'title' => 'Mon profil',
					'utilisateur' => (new AuthService())->getUser(),
					'success' => 'Mot de passe modifié avec succès',
					'isLoggedIn' => $isLoggedIn
				]);
				return;
			}
			
		} catch (Exception $e) {
			$this->view('/user/profile.html.twig', [
				'title' => 'Mon profil',
				'utilisateur' => (new AuthService())->getUser(),
				'error' => $e->getMessage(),
				'isLoggedIn' => $isLoggedIn
			]);
		}
	}

	public function updatePermission()
	{
		$id = $this->getPostParam('id_user');
		$permission = $this->getPostParam('permission');
		$value = $this->getPostParam('value');

		if (empty($id) || empty($permission) || !isset($value)) {
			echo json_encode(['success' => false, 'message' => 'Paramètres manquants.']);
			http_response_code(400);
			return;
		}

		try {
			$userRepository = new UtilisateurRepository();
			$user = $userRepository->findById($id);

			if (!$user) {
				throw new Exception("Utilisateur non trouvé.");
			}

			if ($permission === 'admin') {
				if ($value) {
					$user->addPermission(IS_ADMIN);
				} else {
					$user->removePermission(IS_ADMIN);
				}
			} elseif ($permission === 'adherent') {
				if ($value) {
					$user->addPermission(IS_ADHERENT);
				} else {
					$user->removePermission(IS_ADHERENT);
				}
			} else {
				throw new Exception("Type de permission non valide.");
			}

			$userRepository->updateById($id, [
				'nom' => $user->getNom(),
				'prenom' => $user->getPrenom(),
				'mail' => $user->getMail(),
				'permission' => $user->getPermission(),
			]);

			echo json_encode(['success' => true, 'message' => 'Permission mise à jour avec succès.']);
		} catch (Exception $e) {
			http_response_code(500);
			echo json_encode(['success' => false, 'message' => $e->getMessage()]);
		}
	}
}