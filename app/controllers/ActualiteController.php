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
			'title' => 'Actualités',
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
				$this->redirectTo('news_admin.php');
			} catch (Exception $e) {
				$errors = explode(', ', $e->getMessage());
			}
		}

	}

	public function update()
	{
		$id = $this->getPostParam('id_article');
		$titre = $this->getPostParam('titre');
		$contenu = $this->getPostParam('contenu');

		if (empty($id) || empty($titre) || empty($contenu)) {
			throw new Exception('Tous les champs sont requis.');
			$this->redirectTo('news_admin.php');
			return;
		}

		$newsRepository = new ActualiteRepository();
		$newsRepository->updateById($id, [
			'titre' => $titre,
			'contenu' => $contenu,
		]);
		$this->redirectTo('news_admin.php');
}

	public function delete()
	{
		$id = $this->getPostParam('id_article');

		if (empty($id)) {
			throw new Exception('L\'ID de l\'actualité est requis.');
			$this->redirectTo('news_admin.php');
			return;
		}

		$newsRepository = new ActualiteRepository();
		$newsRepository->deleteById($id);
		$this->redirectTo('news_admin.php');
	}

	public function getLastActualites(int $limit = 10)
	{
		$repository = new ActualiteRepository();
		return $repository->findLastActualites($limit);
	}
}