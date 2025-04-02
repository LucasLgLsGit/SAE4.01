<?php

require_once './app/core/Controller.php';
require_once './app/services/EvenementRepository.php';
require_once './app/trait/FormTrait.php';
require_once './app/trait/AuthTrait.php';

class EvenementController extends Controller {

	use FormTrait;
	use AuthTrait;

	public function index()
	{
		$repository = new EvenementRepository();
		$evenements = $repository->findAll();

		$isLoggedIn = $this->isLoggedIn();
		$user = $this->getCurrentUser();
		$isAdmin = $user && $user->isAdmin();

		$this->view('/event/index.html.twig', [
			'evenements' => $evenements,
			'isLoggedIn' => $isLoggedIn,
			'isAdmin' => $isAdmin
		]);
	}

	public function create() {
		$data = $this->getAllPostParams();
		$errors = [];

		if (!empty($data)) {
			try {
				$eventService = new EvenementRepository();
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
				$eventService = new EvenementRepository();
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

	public function show()
	{
		$isLoggedIn = $this->isLoggedIn();

		$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

		if (!$id) {
			throw new Exception("Identifiant de l'événement invalide !");
		}

		$repository = new EvenementRepository();
		$event = $repository->findById($id);

		if (!$event) {
			throw new Exception("Événement introuvable !");
		}

		$this->view('event.html.twig', ['event' => $event, 'isLoggedIn' => $isLoggedIn]);
	}
}