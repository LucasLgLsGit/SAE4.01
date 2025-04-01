<?php

require_once './app/core/Controller.php';

class ShopController extends Controller
{
	public function index()
	{
		$articles = [];
		$this->view('shop.html.twig',  ['articles' => $articles]);
	}
}