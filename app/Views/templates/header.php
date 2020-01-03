<?php
use Tonight\MVC\Router;

$login = Router::getLink('login');
$link = new stdClass;
$link->registerPoint = Router::getLink('ponto/cadastrar');
$link->managePoints = Router::getLink('pontos');
$link->registerUser = Router::getLink('calebe/cadastrar');
$link->manageUsers = Router::getLink('calebes');
$leader = false;

if ($user !== false) {
  if ($user->isLeader()) {
    $leader = true;
  }
  $link->edit = Router::getLink('calebe', $user->getId(), 'editar');
  $link->logout = Router::getLink('logout/acao');
}
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
<?php if($leader): ?>
        <li class="navbar-item dropdown">
          <a href="#" class="nav-link dropdown-toggle" id="point-dropdown" data-toggle="dropdown">
            Ponto
          </a>
          <div class="dropdown-menu">
            <a href="<?= $link->registerPoint ?>" class="dropdown-item">Cadastrar</a>
            <a href="<?= $link->managePoints ?>" class="dropdown-item">Gerenciar</a>
          </div>
        </li>
        <li class="navbar-item dropdown">
          <a href="#" class="nav-link dropdown-toggle" id="user-dropdown" data-toggle="dropdown">
            Calebe
          </a>
          <div class="dropdown-menu">
            <a href="<?= $link->registerUser ?>" class="dropdown-item">Cadastrar</a>
            <a href="<?= $link->manageUsers ?>" class="dropdown-item">Gerenciar</a>
          </div>
        </li>
        <li class="navbar-item dropdown">
          <a href="#" class="nav-link dropdown-toggle" id="search-dropdown" data-toggle="dropdown">
            Pesquisa
          </a>
          <div class="dropdown-menu">
            <a href="" class="dropdown-item">Cadastrar</a>
            <a href="" class="dropdown-item">Gerenciar</a>
          </div>
        </li>
<?php endif; ?>
      </ul>
      <ul class="navbar-nav mx-right">
<?php if ($user === false): ?>
        <li class="navbar-item">
          <a href="<?= $login ?>" class="nav-link">
            Entrar
          </a>
        </li>
<?php else: ?>
        <li class="navbar-item dropdown">
          <a href="" class="nav-link dropdown-toggle" id="user-menu" data-toggle="dropdown">
            <?= $user->getName() ?>
          </a>
          <div class="dropdown-menu">
            <a href="<?= $link->edit ?>" class="dropdown-item">Editar informações</a>
            <a href="<?= $link->logout ?>" class="dropdown-item">Sair</a>
          </div>
        </li>
<?php endif; ?>
        </li>
      </ul>
    </div>
  </div>
</nav>