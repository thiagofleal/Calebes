<?php

namespace App\Controllers;

use Tonight\MVC\Controller;
use Tonight\MVC\Router;
use Tonight\Tools\Session;
use App\Models\Point;

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
		$this->checkLeader();
		
		$user = Session::get('user');
		$user_point = $user->getPoint();
		
		if ($user_point !== false) {
			$user_point = $user_point->getId();
		}

		if ($point instanceof Point) {
			$point = $point->getId();
		}

		if ($user_point != $point) {
			Router::redirect();
			exit;
		}
	}
}