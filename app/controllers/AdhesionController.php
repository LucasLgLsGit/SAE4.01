<?php
require_once './app/core/Controller.php';
require_once './vendor/autoload.php'; // Charger PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class AdhesionController extends Controller
{
	public function index()
	{
		$data = [];
		$config = require_once './config/mail.php'; // Charger la configuration SMTP

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			// Récupérer les données du formulaire
			$prenom = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_STRING);
			$nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
			$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

			if ($prenom && $nom && $email) {
				$mail = new PHPMailer(true);
				try {
					// Configuration SMTP depuis config/mail.php
					$mail->isSMTP();
					$mail->Host = $config['host'];
					$mail->SMTPAuth = true;
					$mail->Username = $config['username'];
					$mail->Password = $config['password'];
					$mail->SMTPSecure = $config['encryption'];
					$mail->Port = $config['port'];

					// Expéditeur et destinataire
					$mail->setFrom($email, "$prenom $nom"); // Expéditeur = utilisateur
					$mail->addAddress($config['to']); // Destinataire = bdeinformatiquesae401@gmail.com
					$mail->addReplyTo($email, "$prenom $nom"); // Répondre à l'utilisateur

					// Contenu prédéfini de l'email
					$mail->Subject = "Demande d'adhésion - $prenom $nom";
					$mail->Body = "Bonjour,\n\n" .
								  "Je soussigné(e), $prenom $nom, souhaite adhérer au BDE.\n" .
								  "Email : $email\n\n" .
								  "Merci de traiter ma demande.\n" .
								  "Cordialement,\n$prenom $nom";

					// Envoyer l'email
					$mail->send();
					$data['success'] = "Votre demande d'adhésion a été envoyée avec succès !";
				} catch (Exception $e) {
					$data['error'] = "Erreur lors de l'envoi de la demande : " . $mail->ErrorInfo;
				}
			} else {
				$data['error'] = "Erreur : informations manquantes.";
			}
		}
		// Afficher la vue avec les données
		$this->view('index.html.twig', [
			'data' => $data,
			'isLoggedIn' => $this->isLoggedIn(),
			'isAdmin' => $this->isAdmin()
		]);
	}
}