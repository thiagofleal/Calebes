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
				<th>Alocar</th>
			</tr>
		</thead>
		<tbody>
<?php foreach ($points as $point): ?>
			<tr>
				<td><?= $point->getName() ?></td>
				<td><?= $point->getAddress() ?></td>
				<td>
					<a href="<?= Router::getLink('pontos', $point->getId(), 'editar') ?>" class="btn btn-warning">
						Editar
					</a>
					<a href="<?= Router::getLink('pontos', $point->getId(), 'excluir') ?>" class="btn btn-danger">
						Excluir
					</a>
				</td>
				<td>
<?php if ($point->getId() == $current_point): ?>
					<a href="" class="btn btn-primary">
						Alocado
					</a>
<?php else: ?>
					<a href="<?= Router::getLink('membros/pontos', $point->getId(), 'alocar') ?>" class="btn btn-secondary">
						Alocar-se
					</a>
<?php endif; ?>
				</td>
			</tr>
<?php endforeach; ?>
		</tbody>
	</table>
</div>