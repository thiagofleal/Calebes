<?php use Tonight\MVC\Router; ?>
<div class="container">
	<h1 class="mb-4"><?= $title ?></h1>
	<h5>
		Pesquisa realizada por 
		<strong><?= $answer->getUser()->getName() ?></strong>
		às <?= date("H:i", strtotime($answer->getTime())) ?> 
		do dia <?= date("d/m/Y", strtotime($answer->getTime())) ?>
	</h5>
	<hr>
<?php foreach ($answer->getOptions() as $selected): ?>
	<div class="card mb-3">
		<div class="card-header">
			<h5><?= $selected->getOption()->getQuestion()->getText() ?></h5>
		</div>
		<div class="card-body">
			<h6><?= $selected->getOption()->getText() ?></h6>
			<p><?= $selected->getText() ?></p>
		</div>
	</div>
<?php endforeach; ?>
</div>