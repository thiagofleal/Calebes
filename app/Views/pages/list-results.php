<?php use Tonight\MVC\Router; ?>
<div class="container">
	<h1><?= $title ?></h1>
	<hr>
	<a href="<?= $general_link ?>" class="btn btn-info mb-3" target="_blank">
		Resultados gerais
	</a>
	<table class="table table-inverse table-hover table-borderless table-responsive d-md-table">
		<thead>
			<tr>
				<th>Usuário</th>
				<th>Data</th>
				<th>Ação</th>
			</tr>
			<form method="get">
				<tr>
					<th>
						<div class="input-group">
							<input type="search" name="user" class="form-control" value="<?= $filter->user ?? '' ?>" />
						</div>
					</th>
					<th>
						<div class="input-group">
							<input type="date" name="date" class="form-control" value="<?= $filter->date ?? '' ?>" />
						</div>
					</th>
					<th>
						<button type="submit" class="btn btn-secondary">
							Filtrar
						</button>
					</th>
				</tr>
			</form>
		</thead>
		<tbody>
<?php foreach ($answers as $answer): ?>
			<tr>
				<td><?= $answer->getUser()->getName() ?></td>
				<td><?= date("d/m/Y H:i",	 strtotime($answer->getTime())) ?></td>
				<td>
					<a href="<?= Router::getLink(
						'pesquisas', $search->getId(),
						'resultados', $answer->getId(),
						'exibir'
					) ?>" class="btn btn-primary" target="_blank">
						Abrir
					</a>
					<a href="<?= Router::getLink(
						'pesquisas', $search->getId(),
						'resultados', $answer->getId(),
						'excluir'
					) ?>" class="btn btn-danger btn-conf">
						Excluir
					</a>
				</td>
			</tr>
<?php endforeach; ?>
		</tbody>
	</table>
</div>