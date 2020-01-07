<?php

namespace App\Controllers;

use Tonight\MVC\Router;

class HomeController extends BaseController
{
	public function index()
	{
		$this->setVariable('title', "Home");
		$this->setVariable('images', Router::getLink('assets', 'images'));
		$this->render('home', 'main-template');
	}
}