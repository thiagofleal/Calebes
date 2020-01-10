<?php use Tonight\MVC\Router; ?>
<div class="container">
	<?php if ($flash): ?>
		<div class="alert <?= $alert['type'] ?>">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<?= $alert['text'] ?>
		</div>
	<?php endif; ?>
	<h1><?= $title ?></h1>
	<hr>
	<form method="post" action="<?= $action ?>">
		<div class="input-group mb-3">
			<div class="input-group-prepend">
				<label for="name" class="input-group-text">
					Nome
				</label>
			</div>
			<input type="text" name="name" id="name" class="form-control" value="<?= $form->name ?? '' ?>" />
		</div>
<?php if ($add_questions): ?>
		<a href="<?= $add_question_link ?>" class="btn btn-success">
			Adicionar pergunta
		</a>
		<a href="<?= $view_link ?>" class="btn btn-info">
			Visualizar
		</a>
		<hr>
		<table class="table table-borderless table-responsive d-md-table">
			<thead>
				<tr>
					<th>#</th>
					<th>Pergunta</th>
					<th>Ordenar</th>
					<th>Ações</th>
				</tr>
			</thead>
			<tbody>
<?php foreach ($questions as $question): ?>
				<tr>
					<td><?= $question->getNumber() ?></td>
					<td><?= $question->getTitle() ?></td>
					<td>
						<a href="<?=
							Router::getLink(
								'pesquisa', $question->getSearch()->getId(),
								'pergunta', $question->getNumber(),
								'acao/subir'
							)
						?>">
							<img src="<?= $images ?>/arrow-up.png">
						</a> | <a href="<?=
							Router::getLink(
								'pesquisa', $question->getSearch()->getId(),
								'pergunta', $question->getNumber(),
								'acao/descer'
							)
						?>">
							<img src="<?= $images ?>/arrow-down.png">
						</a>
					</td>
					<td>
						<a href="<?= Router::getLink(
								'pesquisa',
								$question->getSearch()->getId(),
								'pergunta',
								$question->getNumber(),
								'editar'
							) ?>" class="btn btn-warning">Editar</a>
						<a href="<?= Router::getLink(
								'pesquisa',
								$question->getSearch()->getId(),
								'pergunta',
								$question->getNumber(),
								'excluir'
							) ?>" class="btn btn-danger">Excluir</a>
					</td>
				</tr>
<?php endforeach; ?>
			</tbody>
		</table>
<?php endif; ?>
		<hr>
		<button type="submit" class="btn btn-primary">
			Salvar
		</button>
		<button type="button" class="btn btn-danger btn-clear-form">
			Cancelar
		</button>
		<a href="<?= Router::getLink('pesquisas') ?>" class="btn btn-secondary">
			Gerenciar pesquisas
		</a>
	</form>
</div>