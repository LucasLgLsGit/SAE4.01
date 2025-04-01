<?php

require_once './app/core/Controller.php';
require_once './app/services/ActualiteService.php';
require_once './app/trait/FormTrait.php';
require_once './app/trait/AuthTrait.php';

class ActualiteController extends Controller {

	use FormTrait;
	use AuthTrait;

	public function index()
	{
		$repository = new ActualiteRepository();
		$actualites = $repository->findAll();

		$this->view('/news/index.html.twig', ['actualites' => $actualites]);
	}

	public function create() {
		$data = $this->getAllPostParams();
		$errors = [];

		if (!empty($data)) {
			try {
				$newsService = new ActualiteService();
				$newsService->create($data);
				$this->redirectTo('actualites.php');
			} catch (Exception $e) {
				$errors = explode(', ', $e->getMessage());
			}
		}

		$this->view('/news/create.html.twig', ['errors' => $errors, 'data' => $data]);
	}

	public function update() {
		$id = $this->getQueryParam('id_event');

		if ($id === null) {
			throw new Exception("L'identifiant actualité est requis !");
		}

		$data = $this->getAllPostParams();
		$errors = [];

		if (!empty($data)) {
			try {
				$newsService = new ActualiteService();
				$newsService->update($id, $data);
				$this->redirectTo('actualites.php');
			} catch (Exception $e) {
				$errors = explode(', ', $e->getMessage());
			}
		}

		$this->view('/news/update.html.twig', 'Modification d\'une actualité', ['errors' => $errors, 'data' => $data, 'id_event' => $id]);
	}
}