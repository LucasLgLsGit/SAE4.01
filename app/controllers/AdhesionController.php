<?php
require_once './app/core/Controller.php';
require_once './vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class AdhesionController extends Controller
{
	public function index()
	{
		$data = [];
		$config = require_once './config/mail.php';

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$prenom = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_STRING);
			$nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
			$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

			if ($prenom && $nom && $email) {
				$mail = new PHPMailer(true);
				try {
					$mail->isSMTP();
					$mail->Host = $config['host'];
					$mail->SMTPAuth = true;
					$mail->Username = $config['username'];
					$mail->Password = $config['password'];
					$mail->SMTPSecure = $config['encryption'];
					$mail->Port = $config['port'];

					$mail->setFrom($email, "$prenom $nom");
					$mail->addAddress($config['to']);
					$mail->addReplyTo($email, "$prenom $nom");

					$mail->Subject = "Demande d'adhésion - $prenom $nom";
					$mail->Body = "Bonjour,\n\n" .
								  "Je soussigné(e), $prenom $nom, souhaite adhérer au BDE.\n" .
								  "Email : $email\n\n" .
								  "Merci de traiter ma demande.\n" .
								  "Cordialement,\n$prenom $nom";

					$mail->send();
					$data['success'] = "Votre demande d'adhésion a été envoyée avec succès !";
				} catch (Exception $e) {
					$data['error'] = "Erreur lors de l'envoi de la demande : " . $mail->ErrorInfo;
				}
			} else {
				$data['error'] = "Erreur : informations manquantes.";
			}
		}
		$this->view('index.html.twig', [
			'title' => 'Accueil',
			'data' => $data,
			'isLoggedIn' => $this->isLoggedIn(),
			'isAdmin' => $this->isAdmin()
		]);
	}
}