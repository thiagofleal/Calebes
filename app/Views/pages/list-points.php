<?php use Tonight\MVC\Router; ?>
<div class="container">
	<h1>Pontos</h1>
	<hr>
	<table class="table table-borderless table-responsive d-md-table">
		<thead>
			<tr>
				<th>Nome</th>
				<th>Endereço</th>
				<th>Ações</th>
			</tr>
		</thead>
	<?php foreach ($points as $point): ?>
		<tr>
			<td><?= $point->getName() ?></td>
			<td><?= $point->getAddress() ?></td>
			<td>
				<a href="<?= Router::getLink('ponto', $point->getId(), 'editar') ?>" class="btn btn-warning">
					Editar
				</a>
				<a href="<?= Router::getLink('ponto', $point->getId(), 'excluir') ?>" class="btn btn-danger">
					Excluir
				</a>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
</div>