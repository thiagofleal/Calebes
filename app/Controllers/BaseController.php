<?php

namespace App\Controllers;

use Tonight\MVC\Controller;
use Tonight\MVC\Router;
use Tonight\Tools\Session;

class BaseController extends Controller
{
	protected function checkLeader()
	{
		$user = Session::get('user');

		if ($user === false) {
			Router::redirect();
			exit;
		}
		if (!$user->isLeader()) {
			Router::redirect();
			exit;
		}
	}

	protected function checkLeaderOrSelf($id)
	{
		$user = Session::get('user');

		if ($user === false) {
			Router::redirect();
			exit;
		}
		if (!$user->isLeader() && $user->getId() != $id) {
			Router::redirect();
			exit;
		}
	}

	protected function checkLeaderAndPoint($point)
	{
		$user = Session::get('user');

		if ($user === false) {
			Router::redirect();
			exit;
		}
		if (!$user->isLeader() && $user->getId() != $id) {
			Router::redirect();
			exit;
		}
		if ($user->getPoint() != $point) {
			Router::redirect();
			exit;
		}
	}
}