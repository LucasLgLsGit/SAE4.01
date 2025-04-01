<?php

require_once './app/core/Controller.php';

class ContactController extends Controller
{
	public function index()
	{
		$this->view('contact.html.twig');
	}
}