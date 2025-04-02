<?php

require_once './app/core/Controller.php';
require_once './app/services/AuthService.php';
require_once './app/repositories/ProduitRepository.php';


class ShopAdminController extends Controller
{
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
				'isAdmin' => $isAdmin
			]);
		} else {
			$this->view('index.html.twig', [
				'isLoggedIn' => $isLoggedIn,
				'isAdmin' => $isAdmin
			]);
		}
	}
}