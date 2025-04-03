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

        $this->view('/admin/eventsAdmin.html.twig', [
            'evenements' => $evenements,
            'isLoggedIn' => $isLoggedIn,
            'isAdmin' => $isAdmin,
			'user' => $user
        ]);
    }

    public function create() {
        $data = $this->getAllPostParams();
        $errors = [];

        if (!empty($data)) {
            try {
                $eventRepo = new EvenementRepository();
                $eventRepo->create($data);
                $this->redirectTo('events_admin.php');
            } catch (Exception $e) {
                $errors = explode(', ', $e->getMessage());
            }
        }

        $this->view('/admin/eventsAdmin.html.twig', ['errors' => $errors, 'data' => $data]);
    }

    public function update() {
		$id = $this->getPostParam('id_event');
	
		if ($id === null) {
			throw new Exception("L'identifiant événement est requis !");
		}
	
		$data = $this->getAllPostParams();
		$errors = [];
	
		$eventRepo = new EvenementRepository();
		$user = $this->getCurrentUser(); 
	
		if (!$user) {
			throw new Exception("Vous devez être connecté pour modifier un événement !");
		}
	
		if (!empty($data)) {
			try {
				$evenement = $eventRepo->findById($id);
				if (!$evenement) {
					throw new Exception("Événement introuvable !");
				}
	
				$evenement->setTitreEvent($data['titre']);
				$evenement->setDateDebut(new DateTime($data['date_debut']));
				$evenement->setDateFin(new DateTime($data['date_fin']));
				$evenement->setAdresse($data['adresse']);
				$evenement->setDescription($data['description']);
				$evenement->setPrix((float)$data['prix']);
				$evenement->setIdUser($user->getId()); 
	
				$eventRepo->update($evenement);
				$this->redirectTo('events_admin.php');
			} catch (Exception $e) {
				$errors = explode(', ', $e->getMessage());
			}
		}
	
		$this->view('admin/eventsAdmin.html.twig', ['errors' => $errors, 'data' => $data, 'id_event' => $id]);
	}

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
            $this->redirectTo('events_admin.php?error=' . urlencode($e->getMessage()));
        }
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
            'event' => $event, 
            'isLoggedIn' => $isLoggedIn,
            'isAdmin' => $isAdmin,
            'isRegistered' => $isRegistered,
            'utilisateur' => $utilisateur,
            'commentaires' => $commentaires
        ]);
    }
}