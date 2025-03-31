<?php

require_once './app/core/Controller.php';

class FaqController extends Controller
{
	public function index()
	{
		$this->view('FAQ.html.twig');
	}
}