<?php use Tonight\MVC\Router; ?>
<div class="container">
	<h1>Pesquisas</h1>
	<hr>
	<table class="table table-borderless table-responsive d-md-table">
		<tr>
			<th>#</th>
			<th>Nome</th>
			<th>Criação</th>
			<th>Ações</th>
		</tr>
<?php foreach ($researches as $search): ?>
		<tr>
			<td><?= $search->getId() ?></td>
			<td><?= $search->getName() ?></td>
			<td><?= date("d/m/Y H:i", strtotime($search->getCreation())) ?></td>
			<td>
				<a href="<?= Router::getLink('pesquisa', $search->getId(), 'editar') ?>" class="btn btn-warning">Editar</a>
				<a href="<?= Router::getLink('pesquisa', $search->getId(), 'excluir') ?>" class="btn btn-danger">Excluir</a>
			</td>
		</tr>
<?php endforeach; ?>
	</table>
</div>