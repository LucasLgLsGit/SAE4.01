<?php

require_once './app/core/Controller.php';
require_once './app/repositories/FaqRepository.php';
require_once './app/trait/AuthTrait.php';
require_once './app/services/AuthService.php';
require_once './app/trait/FormTrait.php';

class FaqController extends Controller
{
	use AuthTrait;
	use FormTrait;

	public function index()
	{
		$isLoggedIn = $this->isLoggedIn();
		$user = $this->getCurrentUser();
		$faqsRepo = new FaqRepository();
		$faqs = $faqsRepo->findAll();
		$isAdmin = $user && $user->isAdmin();

		$this->view('FAQ.html.twig', [
			'title' => 'FAQ',
			'isLoggedIn' => $isLoggedIn,
			'isAdmin' => $isAdmin,
			'faqs' => $faqs
		]);
	}

	public function create()
	{
		$data = $this->getAllPostParams();
		$errors = [];

		if (!empty($data)) {
			try {
				$faqRepo = new FaqRepository();
				$faqRepo->create($data);
				$this->redirectTo('faqs_admin.php');
			} catch (Exception $e) {
				$errors = explode(', ', $e->getMessage());
			}
		}
	}

	public function update()
	{
		$id = $this->getPostParam('id_faq');
		$question = $this->getPostParam('question');
		$reponse = $this->getPostParam('reponse');

		if (empty($id) || empty($question) || empty($reponse)) {
			throw new Exception('Tous les champs sont requis.');
			$this->redirectTo('faqs_admin.php');
			return;
		}

		$faqRepository = new FaqRepository();
		$faqRepository->update($id, $question, $reponse);

		$this->redirectTo('faqs_admin.php');
	}

	public function delete()
	{
		$id = $this->getPostParam('id_faq');

		if (empty($id)) {
			throw new Exception('L\'identifiant de la FAQ est requis.');
			$this->redirectTo('faqs_admin.php');
			return;
		}

		$faqRepository = new FaqRepository();
		$faqRepository->deleteById($id);

		$this->redirectTo('faqs_admin.php');
	}
}