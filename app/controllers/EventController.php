<?php

require_once './app/core/Controller.php';

class EventController extends Controller
{
	public function index()
	{
		$this->view('events.html.twig');
	}
}