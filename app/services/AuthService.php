<?php
require_once './app/trait/AuthTrait.php';
require_once './app/repositories/UtilisateurRepository.php';
class AuthService {

	use AuthTrait;

	public function getUser():?Utilisateur
	{
		if(session_status() == PHP_SESSION_NONE)
			session_start();
		return unserialize($_SESSION['user']);
	}

	public function setUser(Utilisateur $user): void
	{
		if(session_status() == PHP_SESSION_NONE)
			session_start();
		$_SESSION['user'] = serialize($user);
	}

	public function logout(): void
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    // Efface toutes les variables de session
    $_SESSION = array();
    
    // Supprime le cookie de session
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // Détruit la session
    session_destroy();
}

	public function isLoggedIn(): bool {
		if(session_status() == PHP_SESSION_NONE)
			session_start();
		return isset($_SESSION['user']);
	}
}
