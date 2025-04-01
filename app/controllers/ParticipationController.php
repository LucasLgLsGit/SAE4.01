<?php

require_once '../services/ParticipationService.php';

class ParticipationController extends Controller
{
	private ParticipationService $participationService;

	public function __construct()
	{
		$this->participationService = new ParticipationService();
	}

	public function createParticipation()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			try {
				$this->participationService->create($_POST);
				$this->redirectTo('/evenements.php');
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
				$this->participationService->update($data);
				$this->redirectTo('/evenements.php');
			} catch (Exception $e) {
				http_response_code(400);
				echo "Erreur : " . $e->getMessage();
			}
		}
	}

	public function deleteParticipation(int $id_user, int $id_event)
	{
		try {
			$this->participationService->delete($id_user, $id_event);
			$this->redirectTo('/evenements.php');
		} catch (Exception $e) {
			http_response_code(400);
			echo "Erreur : " . $e->getMessage();
		}
	}
}

?>