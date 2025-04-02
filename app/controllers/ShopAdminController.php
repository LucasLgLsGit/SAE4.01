<?php

require_once './app/core/Controller.php';
require_once './app/services/AuthService.php';
require_once './app/trait/FormTrait.php';
require_once './app/repositories/ProduitRepository.php';


class ShopAdminController extends Controller
{
	use AuthTrait;
	use FormTrait;

	public function index()
	{
		$productRepo = new ProduitRepository();
		$products = $productRepo->findAll();
		
		$isLoggedIn = $this->isLoggedIn();
		$user = $this->getCurrentUser();
		$isAdmin = $user && $user->isAdmin();
		
		if($isAdmin) {
			$this->view('/admin/shopAdmin.html.twig', [
				'products' => $products,
				'isLoggedIn' => $isLoggedIn,
				'isAdmin' => $isAdmin,
				'userId' => $user->getId()
			]);
		} else {
			$this->view('index.html.twig', [
				'isLoggedIn' => $isLoggedIn,
				'isAdmin' => $isAdmin
			]);
		}
	}

	public function createProduct() {
		$data = $this->getAllPostParams();
		$errors = [];

		if (!empty($data)) {
			try {
				$productRepo = new ProduitRepository();
				$productRepo->create($data);
				$this->redirectTo('index.php');
			} catch (Exception $e) {
				$errors = explode(', ', $e->getMessage());
			}
		}

		$this->view('/admin/shopAdmin.html.twig', ['errors' => $errors, 'data' => $data]);
	}
}