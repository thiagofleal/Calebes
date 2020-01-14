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
				<label for="title" class="input-group-text">
					Título
				</label>
			</div>
			<input type="text" id="title" name="title" class="form-control" maxlength="50" value="<?= $form->title ?? '' ?>" />
		</div>
		<textarea name="text" class="form-control mb-3" maxlength="100" rows="3" required="required"><?= $form->text ?? '' ?></textarea>
		<div class="input-group mb-3">
			<div class="input-group-prepend">
				<label for="type" class="input-group-text">
					Tipo
				</label>
			</div>
			<select name="type" id="type" class="form-control">
				<option value="1" <?= ($form->type ?? 1) == 1 ? 'selected="selected"' : '' ?>>
					Texto
				</option>
				<option value="2" <?= ($form->type ?? 1) == 2 ? 'selected="selected"' : '' ?>>
					Alternativas
				</option>
				<option value="3" <?= ($form->type ?? 1) == 3 ? 'selected="selected"' : '' ?>>
					Caixas de seleção
				</option>
			</select>
		</div>
		<hr>
		<button type="submit" class="btn btn-primary">
			Salvar
		</button>
		<button type="button" class="btn btn-danger btn-clear-form">
			Cancelar
		</button>
		<a href="<?= $link_questions ?>" class="btn btn-secondary">
			Editar pesquisa
		</a>
	</form>
<?php if ($add_options): ?>
	<hr>
	<h1>Adicionar respostas</h1>
	<table class="table table-borderless table-responsive d-md-table">
		<thead>
			<tr>
				<th>Número</th>
				<th>Texto</th>
				<th>Permite inserção</th>
				<th>Ordenar</th>
				<th>Ações</th>
			</tr>
		</thead>
		<tbody>
<?php foreach ($options as $option): ?>
			<tr>
				<td><?= $option->getNumber() ?></td>
				<td><?= $option->getText() ?></td>
				<td>
					<input type="checkbox" disabled="disabled" <?= $option->getInsert() ? 'checked="checked"' : '' ?>>
				</td>
				<td>
					<a href="<?=
						Router::getLink(
							'pesquisa', $option->getQuestion()->getSearch()->getId(),
							'pergunta', $option->getQuestion()->getNumber(),
							'resposta', $option->getNumber(),
							'acao/subir'
						)
					?>">
						<img src="<?= $images ?>/arrow-up.png">
					</a> | <a href="<?=
						Router::getLink(
							'pesquisa', $option->getQuestion()->getSearch()->getId(),
							'pergunta', $option->getQuestion()->getNumber(),
							'resposta', $option->getNumber(),
							'acao/descer'
						)
					?>">
						<img src="<?= $images ?>/arrow-down.png">
					</a>
				</td>
				<td>
					<a href="<?=
						Router::getLink(
							'pesquisa', $option->getQuestion()->getSearch()->getId(),
							'pergunta', $option->getQuestion()->getNumber(),
							'resposta', $option->getNumber(),
							'editar'
						)
					?>" class="btn btn-warning">Editar</a>
					<a href="<?=
						Router::getLink(
							'pesquisa', $option->getQuestion()->getSearch()->getId(),
							'pergunta', $option->getQuestion()->getNumber(),
							'resposta', $option->getNumber(),
							'acao/remover'
						)
					?>" class="btn btn-danger">Excluir</a>
				</td>
			</tr>
<?php endforeach; ?>
			<tr>
				<td colspan="5">
					<hr>
				</td>
			</tr>
			<form method="post" action="<?= $action_option ?>">
				<tr class="jumbotron">
					<td>#</td>
					<td colspan="2">
						<textarea name="text" class="form-control" rows="1"><?= $opt->text ?? '' ?></textarea>
					</td>
					<td>
						<select name="insert" class="form-control">
							<option value="0" <?= ($opt->insert ?? 0) == 0 ? 'selected="selected"' : '' ?>>
								Não permitir inserção do usuário
							</option>
							<option value="1" <?= ($opt->insert ?? 0) == 1 ? 'selected="selected"' : '' ?>>
								Permitir inserção do usuário
							</option>
						</select>
					</td>
					<td>
						<button type="submit" class="btn btn-success">Salvar</button>
					</td>
				</tr>
			</form>
		</tbody>
	</table>
<?php endif; ?>
</div>