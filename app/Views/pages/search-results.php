<div class="container">
	<h1><?= $title ?></h1>
	<hr>
	<div class="jumbotron">
		<h4><?= $search->getName() ?></h4>
		<hr>
		<h6>Respostas: <?= $count ?></h6>
	</div>
<?php foreach ($results as $question_number => $question): ?>
	<h4>Questão <?= $question_number ?></h4>
	<strong><?= $question['text'] ?></strong>
	<hr>
	<?php foreach ($question['options'] as $option_number => $option): ?>
		<li><strong><?= $option['text'] ?></strong>: <?= $option['count'] ?> (<?= $option['statistics'] ?>%)</li>
		<ul>
		<?php foreach ($option['data'] as $option_data): ?>
			<?php if (!empty($option_data->getText())): ?>
				<li><?= $option_data->getText() ?></li>
			<?php endif; ?>
		<?php endforeach; ?>
		</ul>
	<?php endforeach; ?>
	<br>
<?php endforeach; ?>
</div>