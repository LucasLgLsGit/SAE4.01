<?php
require_once './app/core/Controller.php';
require_once './vendor/autoload.php'; // Charger PHPMailer via Composer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ContactController extends Controller
{
	public function index()
	{
		$data = [];
		$config = require_once './config/mail.php'; // Charger la configuration

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
			$sujet = filter_input(INPUT_POST, 'sujet', FILTER_SANITIZE_STRING);
			$message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

			if ($email && $sujet && $message) {
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
					$mail->setFrom($email); // Email de l'utilisateur comme expéditeur
					$mail->addAddress($config['to']); // Destinataire par défaut
					$mail->addReplyTo($email); // Répondre à l'utilisateur

					// Contenu de l'email
					$mail->Subject = "Nouveau message : $sujet";
					$mail->Body = "De : $email\n\nSujet : $sujet\n\nMessage :\n$message";

					// Envoyer l'email
					$mail->send();
					$data['success'] = "Votre message a été envoyé avec succès !";
				} catch (Exception $e) {
					$data['error'] = "Erreur lors de l'envoi : " . $mail->ErrorInfo;
				}
			} else {
				$data['error'] = "Tous les champs sont obligatoires.";
			}
		}

		// Afficher la vue avec les données
		$this->view('contact.html.twig', $data);
	}
}