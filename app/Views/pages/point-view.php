<div class="container">
	<div class="jumbotron text-primary">
		<h1><?= $title ?></h1>
		<hr>
		<h5><?= $point->getAddress() ?></h5>
	</div>
	<div class="card">
		<div class="card-header">
			<h3>Líderes</h3>
		</div>
		<div class="card-body">
<?php foreach ($point->getLeaders() as $leader): ?>
			<?= $leader->getName() ?><br>
<?php endforeach; ?>
		</div>
	</div>
</div>