<?php

namespace App\Core;

use Tonight\View\Template;

class MainTemplate extends Template {

	public function __construct() {
		parent::__construct('templates/main-template.php');
	}
}