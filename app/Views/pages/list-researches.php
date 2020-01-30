<?php use Tonight\MVC\Router; ?>
<div class="container">
	<h1>Pesquisas</h1>
	<hr>
	<table class="table table-borderless table-responsive d-md-table">
		<thead>
			<tr>
				<th>Nome</th>
				<th>Criação</th>
				<th>Respostas</th>
				<th>Token</th>
				<th>Ações</th>
			</tr>
		</thead>
		<tbody>
<?php foreach ($researches as $search): ?>
			<tr>
				<td><?= $search->getName() ?></td>
				<td><?= date("d/m/Y H:i", strtotime($search->getCreation())) ?></td>
				<td>
					<a href="<?= Router::getLink('pesquisas', $search->getId(), 'resultados') ?>" class="btn btn-info">
						Resultados
					</a>
				</td>
				<td>
					<?= $search->getToken() ?>
				</td>
				<td>
					<a href="<?= Router::getLink('pesquisas', $search->getId(), 'editar') ?>" class="btn btn-warning">Editar</a>
					<a href="<?= Router::getLink('pesquisas', $search->getId(), 'excluir') ?>" class="btn btn-danger btn-conf">Excluir</a>
				</td>
			</tr>
<?php endforeach; ?>
		</tbody>
	</table>
</div>