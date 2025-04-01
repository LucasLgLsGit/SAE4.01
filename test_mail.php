<?php

$to = "bdeinformatiquesae401@gmail.com";
$subject = "Test Email";
$message = "Ceci est un test pour vérifier la fonction mail() en PHP.";
$headers = "From: test@example.com";

if (mail($to, $subject, $message, $headers)) {
    echo "Email envoyé avec succès.";
} else {
    echo "Échec de l'envoi de l'email.";
}