<?php

require_once './app/core/Controller.php';
require_once './app/repositories/ActualiteRepository.php';
require_once './app/trait/FormTrait.php';
require_once './app/trait/AuthTrait.php';

class ActualiteController extends Controller {

	use FormTrait;
	use AuthTrait;

	public function index()
	{
		$repository = new ActualiteRepository();
		$actualites = $repository->findAll();
		
		$isLoggedIn = $this->isLoggedIn();
		$user = $this->getCurrentUser();
		$isAdmin = $user && $user->isAdmin();

		$this->view('/news/index.html.twig', [
			'actualites' => $actualites,
			'isLoggedIn' => $isLoggedIn,
			'isAdmin' => $isAdmin
		]);
	}

	public function create() {
		$data = $this->getAllPostParams();
		$errors = [];

		if (!empty($data)) {
			try {
				$newsRepo = new ActualiteRepository();
				$newsRepo->create($data);
				$this->redirectTo('actualites.php');
			} catch (Exception $e) {
				$errors = explode(', ', $e->getMessage());
			}
		}

		$this->view('/news/create.html.twig', ['errors' => $errors, 'data' => $data]);
	}

	public function update()
	{
		$id = $this->getPostParam('id_article');
		$titre = $this->getPostParam('titre');
		$contenu = $this->getPostParam('contenu');

		if (empty($id) || empty($titre) || empty($contenu)) {
			echo json_encode(['success' => false, 'message' => 'Tous les champs sont requis.']);
			http_response_code(400);
			return;
		}

		try {
			$newsRepository = new ActualiteRepository();
			$newsRepository->updateById($id, [
				'titre' => $titre,
				'contenu' => $contenu,
			]);
			echo json_encode(['success' => true, 'message' => 'Actualité mise à jour avec succès.']);
		} catch (Exception $e) {
			http_response_code(500);
			echo json_encode(['success' => false, 'message' => $e->getMessage()]);
		}
}

	public function delete()
	{
		$id = $this->getPostParam('id_article');

		if (empty($id)) {
			echo json_encode(['success' => false, 'message' => 'ID requis.']);
			return;
		}

		try {
			$newsRepository = new ActualiteRepository();
			$newsRepository->deleteById($id);
			echo json_encode(['success' => true, 'message' => 'Actualité supprimée avec succès.']);
		} catch (Exception $e) {
			echo json_encode(['success' => false, 'message' => $e->getMessage()]);
		}
	}

	public function getLastActualites(int $limit = 10)
	{
		$repository = new ActualiteRepository();
		return $repository->findLastActualites($limit);
	}
}