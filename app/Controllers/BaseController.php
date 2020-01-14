<?php

namespace App\Controllers;

use Tonight\MVC\Controller;
use Tonight\MVC\Router;
use Tonight\Tools\Session;
use App\Models\Point;

class BaseController extends Controller
{
	protected function checkLogged()
	{
		$user = Session::get('user');

		if ($user === false) {
			Router::redirect();
			exit;
		}

		return $user;
	}

	protected function checkLeader()
	{
		$user = $this->checkLogged();

		if (!$user->isLeader()) {
			Router::redirect();
			exit;
		}

		return $user;
	}

	protected function checkLeaderOrSelf($id)
	{
		$user = $this->checkLogged();

		if (!$user->isLeader() && $user->getId() != $id) {
			Router::redirect();
			exit;
		}
	}

	protected function checkPoint($point)
	{
		$user = $this->checkLogged();
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

	protected function checkLeaderAndPoint($point)
	{
		$this->checkLeader();
		$this->checkPoint($point);
	}
}