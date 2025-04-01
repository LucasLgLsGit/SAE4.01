<?php

require_once './app/core/Controller.php';
require_once './app/services/AuthService.php';
require_once './app/trait/FormTrait.php';
require_once './app/trait/AuthTrait.php';

class AuthController extends Controller {
	use FormTrait;
	use AuthTrait;


    public function login() {
        $authService = new AuthService();

        // Récupérer les données POST nettoyées
        $postData = $this->getAllPostParams();
        $data = [];

        if (!empty($postData)) {
            $utilisateurRepository = new UtilisateurRepository();

            $user = $utilisateurRepository->findByEmail($this->getPostParam('mail'));

            if ($user !== null) {
                $password = $this->getPostParam('mdp');
                $hashedPassword = $user->getMdp(); // Debugging

                if (!$this->verify($password, $hashedPassword)) {
                    $data = ['error' => 'Mot de passe incorrect.'];
                } else {
                    $authService->setUser($user);
                    $this->redirectTo('index.php');
                }
            } else {
                $data = ['error' => 'Utilisateur non trouvé.'];
            }
        }

        $this->view('/user/login.html.twig', $data);
    }

    public function logout() {
        $authService = new AuthService();
        $authService->logout();
        
        header('Location: index.php');
        exit();
    }
}
