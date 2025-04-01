<?php

require_once './app/core/Controller.php';
require_once './app/services/EvenementService.php';
require_once './app/trait/FormTrait.php';
require_once './app/trait/AuthTrait.php';

class EvenementController extends Controller {

	use FormTrait;
	use AuthTrait;

	public function index()
	{
		$repository = new EvenementRepository();
		$evenements = $repository->findAll();

		$this->view('/event/index.html.twig', ['evenements' => $evenements]);
	}

	public function create() {
		$data = $this->getAllPostParams();
		$errors = [];

		if (!empty($data)) {
			try {
				$eventService = new EvenementService();
				$eventService->create($data);
				$this->redirectTo('evenements.php');
			} catch (Exception $e) {
				$errors = explode(', ', $e->getMessage());
			}
		}

		$this->view('/event/create.html.twig', ['errors' => $errors, 'data' => $data]);
	}

	public function update() {
		$id = $this->getQueryParam('id_event');

		if ($id === null) {
			throw new Exception("L'identifiant évenement est requis !");
		}

		$data = $this->getAllPostParams();
		$errors = [];

		if (!empty($data)) {
			try {
				$eventService = new EvenementService();
				$eventService->update($id, $data);
				$this->redirectTo('evenements.php');
			} catch (Exception $e) {
				$errors = explode(', ', $e->getMessage());
			}
		}

		$this->view('/event/update.html.twig', 'Modification d\'un évenement', ['errors' => $errors, 'data' => $data, 'id_event' => $id]);
	}

	public function getUpcomingEvents() {
		$repository = new EvenementRepository();
		return $repository->findUpcomingEvents(3);
	}

	public function getAllUpcomingEvents() {
        $repository = new EvenementRepository();
        return $repository->findUpcomingEvents(); 
    }
}