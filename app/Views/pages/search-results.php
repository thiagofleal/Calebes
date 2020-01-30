<?php use Tonight\MVC\Router; ?>
<div class="container">
	<h1><?= $title ?></h1>
	<hr>
	<div class="jumbotron">
		<h4><?= $search->getName() ?></h4>
		<hr>
		<h6>Respostas: <?= $count ?></h6>
	</div>
<?php foreach ($search->getQuestions() as $question): ?>
	<h4>Questão <?= $question->getNumber() ?></h4>
	<strong><?= $question->getText() ?></strong>
	<hr>
	<?php foreach ($question->getOptions() as $option): ?>
		<li class="dropdown">
			<strong><?= $option->getText() ?></strong>: 
			<button class="dropdown-toggle open-content btn btn-link" data-target="#q_<?= $question->getNumber() ?>_o_<?= $option->getNumber() ?>">
<?php $count_answer = $search->countAnswerOption($option);?>
					<?= $count_answer ?> <?= $count_answer == 1 ? 'seleção' : 'seleções' ?>
					(<?= $count_answer / $count * 100 ?>%)
			</button>
			<ul class="collapse" id="q_<?= $question->getNumber() ?>_o_<?= $option->getNumber() ?>">
				<?php foreach ($option->getSelected() as $option): ?>
					<li>
						<a href="<?= Router::getLink('pesquisas', $search->getId(), 'resultados', $option->getAnswer()->getId(), 'exibir') ?>" target="_blank">
							[<?= $option->getAnswer()->getUser()->getName() ?>]
					<?php if (!empty($option->getText())): ?>
						<?= $option->getText() ?>
					<?php endif; ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</li>
	<?php endforeach; ?>
	<br>
<?php endforeach; ?>
</div>