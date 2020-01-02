<?php

namespace App\Controllers;

use Tonight\MVC\Router;
use Tonight\MVC\Controller;

class HomeController extends Controller
{
	public function index()
	{
		$this->setVariable('title', "Home");
		$this->setVariable('images', Router::getLink('assets', 'images'));
		$this->render('home', 'main-template');
	}
}