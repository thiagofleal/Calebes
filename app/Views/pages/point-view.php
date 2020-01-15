<div class="container">
	<h1><?= $title ?></h1>
	<hr>
	<h5><?= $point->getAddress() ?></h5>
	<hr>
	<h3>Líderes</h3>
<?php foreach ($point->getLeaders() as $leader): ?>
	<?= $leader->getName() ?><br>
<?php endforeach; ?>
</div>