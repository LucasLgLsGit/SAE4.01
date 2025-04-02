<?php

require_once './app/core/Controller.php';
require_once './app/repositories/CommentaireRepository.php';

class CommentaireController extends Controller
{
    private CommentaireRepository $commentaireRepo;

    public function __construct()
    {
        $this->commentaireRepo = new CommentaireRepository();
    }

    // Méthode pour créer un commentaire
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'texte' => $_POST['texte'] ?? null,
                    'date_commentaire' => date('Y-m-d H:i:s'), // Génère la date actuelle
                    'id_user' => $_POST['id_user'] ?? null,
                    'id_event' => $_POST['id_event'] ?? null,
                ];

                // Appel au repository pour créer le commentaire
                $commentaire = $this->commentaireRepo->create($data);

                // Redirection après succès
                $this->redirectTo("/event.php?id=" . $data['id_event']);
            } catch (Exception $e) {
                http_response_code(400);
                echo "Erreur : " . $e->getMessage();
            }
        }
    }

    // Méthode pour supprimer un commentaire
    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $idCommentaire = $_POST['id_commentaire'] ?? null;

                if (!$idCommentaire) {
                    throw new Exception("L'identifiant du commentaire est requis !");
                }

                // Appel au repository pour supprimer le commentaire
                $this->commentaireRepo->delete($idCommentaire);

                // Redirection après succès
                $this->redirectTo('/events.php');
            } catch (Exception $e) {
                http_response_code(400);
                echo "Erreur : " . $e->getMessage();
            }
        }
    }
}