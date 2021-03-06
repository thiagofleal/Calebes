<?php use Tonight\MVC\Router; ?>
<div class="container">
	<h1>Pontos</h1>
	<hr>
	<table class="table table-borderless table-responsive d-md-table">
		<thead>
			<tr>
				<th>Nome</th>
				<th>Endereço</th>
				<th>Alocar</th>
				<th>Ações</th>
			</tr>
		</thead>
		<tbody>
<?php foreach ($points as $point): ?>
			<tr>
				<td><?= $point->getName() ?></td>
				<td><?= $point->getAddress() ?></td>
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
				<td>
					<a href="<?= Router::getLink('pontos', $point->getId(), 'editar') ?>" class="btn btn-warning">
						Editar
					</a>
					<a href="<?= Router::getLink('pontos', $point->getId(), 'excluir') ?>" class="btn btn-danger btn-conf">
						Excluir
					</a>
				</td>
			</tr>
<?php endforeach; ?>
		</tbody>
	</table>
</div>