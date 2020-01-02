<?php

namespace App\Controllers;

use Tonight\MVC\Router;
use Tonight\MVC\Controller;

class LoginController extends Controller
{
	public function index()
	{
		$this->setVariable('title', "Login");
		$this->setVariable('action', Router::getLink('login', 'action'));
		$this->render('login', 'main-template');
	}

	public function action($request)
	{
		Router::redirect();
	}
}