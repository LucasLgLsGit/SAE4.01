<?php

require_once './app/core/Controller.php';
require_once './app/repositories/EvenementRepository.php';
require_once './app/repositories/ParticipationRepository.php';
require_once './app/repositories/CommentaireRepository.php';
require_once './app/repositories/UtilisateurRepository.php';
require_once './app/entities/Evenement.php';
require_once './app/trait/FormTrait.php';
require_once './app/trait/AuthTrait.php';

class EvenementController extends Controller
{
	use FormTrait;
	use AuthTrait;

<<<<<<< HEAD
	use FormTrait;
	use AuthTrait;

	public function index()
	{
		setlocale(LC_TIME, 'fr_FR.UTF-8', 'fr_FR', 'french');

		$upcomingEvents = $this->getAllUpcomingEvents(); 
=======
	public function index()
	{
		$repository = new EvenementRepository();
		$evenements = $repository->findAll();

		$isLoggedIn = $this->isLoggedIn();
		$user = $this->getCurrentUser();
		$isAdmin = $user && $user->isAdmin();
		$isAdherent = $user && $user->isAdherent();
>>>>>>> 91371610a996036315484ab159cc1962de2adffc

		$isLoggedIn = $this->isLoggedIn();
		$user = $this->getCurrentUser();
		$isAdmin = $user && $user->isAdmin();

		$eventsByYear = [];
		foreach ($upcomingEvents as $event) {
			$year = $event->getDateDebut()->format('Y');
			if (!isset($eventsByYear[$year])) {
				$eventsByYear[$year] = [];
			}
			$eventsByYear[$year][] = $event;
		}
		krsort($eventsByYear);

		$this->view('events.html.twig', [
			'title' => 'Événements',
			'eventsByYear' => $eventsByYear,
			'isLoggedIn' => $isLoggedIn,
<<<<<<< HEAD
			'isAdmin' => $isAdmin
		]);
	}

	public function create() {
		$data = $this->getAllPostParams();
		$errors = [];
		$user = $this->getCurrentUser();
	
		if (!$user) {
			throw new Exception("Vous devez être connecté pour créer un événement !");
		}
	
		if (empty($data['date_debut'])) {
			$errors[] = "La date de début est requise.";
		}
	
		if (!empty($data)) {
			try {
				$data['id_user'] = $user->getId();
	
				$eventRepo = new EvenementRepository();
				$eventRepo->create($data);
				$this->redirectTo('events_admin.php');
			} catch (Exception $e) {
				$errors = explode(', ', $e->getMessage());
			}
		}
	
		$this->view('/event/create.html.twig', ['errors' => $errors, 'data' => $data, 'title' => 'Création d\'un évenement']);
	}

	public function update() {
=======
			'isAdmin' => $isAdmin,
			'isAdherent' => $isAdherent,
			'user' => $user
		]);
	}

	public function create()
	{
		$data = $this->getAllPostParams();
		$errors = [];
		$user = $this->getCurrentUser();

		if (!$user)
		{
			throw new Exception("Vous devez être connecté pour créer un événement !");
		}

		if (!empty($data))
		{
			try
			{
				$data['id_user'] = $user->getId();

				$eventRepo = new EvenementRepository();
				$eventRepo->create($data);
				$this->redirectTo('events_admin.php');
			}
			catch (Exception $e)
			{
				$errors = explode(', ', $e->getMessage());
			}
		}

		$this->view('/event/create.html.twig', [
			'errors' => $errors,
			'data' => $data,
			'title' => 'Création d\'un évenement'
		]);
	}

	public function update()
	{
>>>>>>> 91371610a996036315484ab159cc1962de2adffc
		$id = $this->getPostParam('id_event');

		if ($id === null)
		{
			throw new Exception("L'identifiant événement est requis !");
		}

		$data = $this->getAllPostParams();
		$errors = [];
		$eventRepo = new EvenementRepository();
		$user = $this->getCurrentUser();

		if (!$user)
		{
			throw new Exception("Vous devez être connecté pour modifier un événement !");
		}

		if (!empty($data))
		{
			try
			{
				$evenement = $eventRepo->findById($id);
				if (!$evenement)
				{
					throw new Exception("Événement introuvable !");
				}

				$evenement->setTitreEvent($data['titre']);
				$evenement->setDateDebut(new DateTime($data['date_debut']));
				$evenement->setDateFin(new DateTime($data['date_fin']));
				$evenement->setAdresse($data['adresse']);
				$evenement->setDescription($data['description']);
				$evenement->setPrix((float)$data['prix']);
				$evenement->setIdUser(!empty($data['id_user']) ? (int)$data['id_user'] : $user->getId());

				$eventRepo->update($evenement);
				$this->redirectTo('events_admin.php');
			}
			catch (Exception $e)
			{
				$errors = explode(', ', $e->getMessage());
			}
		}

		$this->view('/event/update.html.twig', 'Modification d\'un évenement', [
			'title' => 'Modification evenement',
			'errors' => $errors,
			'data' => $data,
			'id_event' => $id
		]);
	}

<<<<<<< HEAD
	public function delete() {
		$id = $this->getPostParam('id_event');

		if ($id === null) {
			throw new Exception("L'identifiant de l'événement est requis !");
		}

		try {
			$eventRepo = new EvenementRepository();
			$eventRepo->delete($id);
			$this->redirectTo('events_admin.php');
		} catch (Exception $e) {
=======
	public function delete()
	{
		$id = $this->getPostParam('id_event');

		if ($id === null)
		{
			throw new Exception("L'identifiant de l'événement est requis !");
		}

		try
		{
			$eventRepo = new EvenementRepository();
			$eventRepo->delete($id);
			$this->redirectTo('events_admin.php');
		}
		catch (Exception $e)
		{
>>>>>>> 91371610a996036315484ab159cc1962de2adffc
			$this->redirectTo('events.php?error=' . urlencode($e->getMessage()));
		}
	}

<<<<<<< HEAD
	public function getUpcomingEvents() {
=======
	public function getUpcomingEvents()
	{
>>>>>>> 91371610a996036315484ab159cc1962de2adffc
		$repository = new EvenementRepository();
		return $repository->findUpcomingEvents(3);
	}

<<<<<<< HEAD
	public function getAllUpcomingEvents() {
=======
	public function getAllUpcomingEvents()
	{
>>>>>>> 91371610a996036315484ab159cc1962de2adffc
		$repository = new EvenementRepository();
		return $repository->findUpcomingEvents();
	}

	public function show()
	{
		$isLoggedIn = $this->isLoggedIn();
		$utilisateur = $this->getCurrentUser();
		$isAdmin = $utilisateur && $utilisateur->isAdmin();

		$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

<<<<<<< HEAD
		if (!$id) {
=======
		if (!$id)
		{
>>>>>>> 91371610a996036315484ab159cc1962de2adffc
			throw new Exception("Identifiant de l'événement invalide !");
		}

		$repository = new EvenementRepository();
		$event = $repository->findById($id);

<<<<<<< HEAD
		if (!$event) {
=======
		if (!$event)
		{
>>>>>>> 91371610a996036315484ab159cc1962de2adffc
			throw new Exception("Événement introuvable !");
		}

		$commentaireRepository = new CommentaireRepository();
		$utilisateurRepository = new UtilisateurRepository();
		$commentaires = $commentaireRepository->findByEventId($event->getId());

<<<<<<< HEAD
		foreach ($commentaires as $commentaire) {
=======
		foreach ($commentaires as $commentaire)
		{
>>>>>>> 91371610a996036315484ab159cc1962de2adffc
			$commentaire->utilisateur = $utilisateurRepository->findById($commentaire->getId_user());
		}

		$isRegistered = false;
<<<<<<< HEAD
		if ($isLoggedIn && $utilisateur) {
=======
		if ($isLoggedIn && $utilisateur)
		{
>>>>>>> 91371610a996036315484ab159cc1962de2adffc
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