<?php

require_once './app/repositories/ParticipationRepository.php';
require_once './app/core/Controller.php';

class ParticipationController extends Controller
{
	private ParticipationRepository $participationRepository;

	public function __construct()
	{
		$this->participationRepository = new ParticipationRepository();
	}

	public function createParticipation()
	{
		$this->participationRepository = new ParticipationRepository();

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			try {
				$idEvent = $_POST['id_event'] ?? null;
	
				if (!$idEvent) {
					throw new Exception("L'identifiant de l'événement est manquant !");
				}
	
				$this->participationRepository->create($_POST);
	
				$this->redirectTo("/event.php?id=" . $idEvent);
			} catch (Exception $e) {

				http_response_code(400);
				echo "Erreur : " . $e->getMessage();
			}
		}
	}

	public function updateParticipation(int $id_user, int $id_event)
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			try {
				$data = [
					'id_user' => $id_user,
					'id_event' => $id_event,
					'date_inscription' => $_POST['date_inscription'] ?? null
				];
				$this->participationRepository->update($data);
				$this->redirectTo('/evenements.php');
			} catch (Exception $e) {
				http_response_code(400);
				echo "Erreur : " . $e->getMessage();
			}
		}
	}

	public function deleteParticipation(int $id_user, int $id_event)
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $idEvent = $_POST['id_event'] ?? null;
                $idUser = $_POST['id_user'] ?? null;
    
                if (!$idEvent) {
                    throw new Exception("L'identifiant de l'événement est manquant !");
                }
    
                if (!$idUser) {
                    throw new Exception("L'identifiant utilisateur est manquant !");
                }
    
                $this->participationRepository->delete($idUser, $idEvent);
    
                $this->redirectTo("/event.php?id=" . $idEvent);
            } catch (Exception $e) {
                http_response_code(400);
                echo "Erreur : " . $e->getMessage();
            }
        }
	}
}

?>