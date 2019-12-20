<?php

namespace App\Core;

use Tonight\View\Template;

class Controller {

	private $template;

	public function __construct($template = NULL) {
		if($template == NULL) {
			$template = new MainTemplate();
		}
		$this->setTemplate($template);
	}

	public function setTemplate(Template $template) {
		$this->template = $template;
	}

	public function setVariable(string $key, $value) {
		$this->template->setVariable($key, $value);
	}

	public function renderView(string $page) {
		$this->template->require('pages/'.$page.'.php');
	}
}