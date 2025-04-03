<?php

require_once './app/core/Controller.php';
require_once './app/repositories/EvenementRepository.php';
require_once './app/repositories/ParticipationRepository.php';
require_once './app/repositories/CommentaireRepository.php';
require_once './app/repositories/UtilisateurRepository.php';
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
			'title' => 'Événements',
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
				$eventRepo = new EvenementRepository();
				$eventRepo->create($data);
				$this->redirectTo('evenements.php');
			} catch (Exception $e) {
				$errors = explode(', ', $e->getMessage());
			}
		}

		$this->view('/event/create.html.twig', ['errors' => $errors, 'data' => $data, 'title' => 'Création d\'un évenement']);
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
				$eventRepo = new EvenementRepository();
				$eventRepo->update($id, $data);
				$this->redirectTo('evenements.php');
			} catch (Exception $e) {
				$errors = explode(', ', $e->getMessage());
			}
		}

		$this->view('/event/update.html.twig', 'Modification d\'un évenement', ['title'=> 'Modification evenement','errors' => $errors, 'data' => $data, 'id_event' => $id]);
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
		$utilisateur = $this->getCurrentUser();
		$isAdmin = $utilisateur && $utilisateur->isAdmin();


		$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

		if (!$id) {
			throw new Exception("Identifiant de l'événement invalide !");
		}

		$repository = new EvenementRepository();
		$event = $repository->findById($id);

		if (!$event) {
			throw new Exception("Événement introuvable !");
		}

		$commentaireRepository = new CommentaireRepository();
		$utilisateurRepository = new UtilisateurRepository();
		$commentaires = $commentaireRepository->findByEventId($event->getId());

		foreach ($commentaires as $commentaire) {
			$commentaire->utilisateur = $utilisateurRepository->findById($commentaire->getId_user());
		}

		$isRegistered = false;
		if ($isLoggedIn && $utilisateur) {
			$participationRepo = new ParticipationRepository();
			$isRegistered = $participationRepo->findById($utilisateur->getId(), $id) !== null;
		}

		$this->view('event.html.twig', [
			'title' => 'Événement',
			'event' => $event, 
			'isLoggedIn' => $isLoggedIn,
			'isAdmin' => $isAdmin,
			'isRegistered' => $isRegistered,
			'utilisateur' => $utilisateur,
			'commentaires' => $commentaires
		]);
	}
}