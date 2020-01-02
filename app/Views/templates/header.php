<?php
use Tonight\MVC\Router;

$link = new stdClass;
$link->registerPoint = Router::getLink('ponto', 'cadastrar');
$link->registerUser = Router::getLink('calebe', 'cadastrar');
?>
<nav class="navbar navbar-expand-md bg-light navbar-light fixed-top">
	<div class="container">
    <a href="<?= $public ?>" class="navbar-brand">
    	<img src="<?= $public ?>/assets/images/logo-calebe.min.png" />
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#menu">
    	<span class="navbar-toggler-icon"></span>
    </button>
    <div id="menu" class="collapse navbar-collapse">
    	<ul class="navbar-nav mr-auto">
        <li class="navbar-item dropdown">
          <a href="#" class="nav-link dropdown-toggle" id="point-dropdown" data-toggle="dropdown">
            Ponto
          </a>
          <div class="dropdown-menu" aria-labelledby="point-dropdown">
            <a href="<?= $link->registerPoint ?>" class="dropdown-item">Cadastrar</a>
            <a href="" class="dropdown-item">Gerenciar</a>
          </div>
        </li>
        <li class="navbar-item dropdown">
          <a href="#" class="nav-link dropdown-toggle" id="user-dropdown" data-toggle="dropdown">
            Calebe
          </a>
          <div class="dropdown-menu" aria-labelledby="user-dropdown">
            <a href="<?= $link->registerUser ?>" class="dropdown-item">Cadastrar</a>
            <a href="" class="dropdown-item">Gerenciar</a>
          </div>
        </li>
        <li class="navbar-item dropdown">
          <a href="#" class="nav-link dropdown-toggle" id="search-dropdown" data-toggle="dropdown">
            Pesquisa
          </a>
          <div class="dropdown-menu" aria-labelledby="search-dropdown">
            <a href="" class="dropdown-item">Cadastrar</a>
            <a href="" class="dropdown-item">Gerenciar</a>
          </div>
        </li>
    	</ul>
      <ul class="navbar-nav mx-right">
        <li class="navbar-item">
          <a href="<?= $login ?>" class="nav-link">
            Entrar
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>