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
		<button type="button" class="btn btn-secondary">
			Adicionar pergunta
		</button>
		<table class="table table-borderless table-responsive d-md-table">
			<tr>
				<th>#</th>
				<th>Pergunta</th>
				<th>Ordenar</th>
				<th>Ações</th>
			</tr>
<?php foreach ($questions as $question): ?>
			<td><?= $question->getNumber() ?></td>
			<td><?= $question->getText() ?></td>
			<td>
				<a href=""></a>
				<a href=""></a>
			</td>
			<td>
				<a href="" class="btn btn-warning">Editar</a>
				<a href="" class="btn btn-danger">Excluir</a>
			</td>
<?php endforeach; ?>
		</table>
<?php endif; ?>
		<hr>
		<button type="submit" class="btn btn-primary">
			Salvar
		</button>
		<button type="button" class="btn btn-danger btn-clear-form">
			Cancelar
		</button>
	</form>
</div>