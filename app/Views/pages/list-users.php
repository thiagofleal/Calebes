<?php use Tonight\MVC\Router; ?>
<div class="container">
	<h1>Calebes</h1>
	<hr>
	<table class="table table-borderless table-responsive d-md-table">
		<thead>
			<tr>
				<th>Nome</th>
				<th>Documento</th>
				<th>Ações de registro</th>
				<th>Ações gerenciais</th>
			</tr>
		</thead>
	<?php foreach ($members as $member): ?>
		<tr>
			<td><?= $member->getName() ?></td>
			<td><?= $member->getDocument() ?></td>
			<td>
				<a href="<?= Router::getLink('membros', $member->getId(), 'editar') ?>" class="btn btn-warning">
					Editar
				</a>
				<a href="<?= Router::getLink('membros', $member->getId(), 'excluir') ?>" class="btn btn-danger">
					Excluir
				</a>
			</td>
			<td>
<?php if (empty($member->getPoint())): ?>
				<a href="<?= Router::getLink('pontos/membros', $member->getId(), 'alocar') ?>" class="btn btn-primary">
					Alocar em ponto
				</a>
<?php else: ?>
				<a href="<?= Router::getLink('pontos/membros', $member->getId(), 'desalocar') ?>" class="btn btn-secondary">
					Remover do ponto
				</a>
<?php endif; ?>
<?php if ($member->isLeader()): ?>
				<a href="<?= Router::getLink('lideres', $member->getId(), 'remover') ?>" class="btn btn-danger">Remover como líder</a>
<?php else: ?>
				<a href="<?= Router::getLink('lideres', $member->getId(), 'adicionar') ?>" class="btn btn-success">Adicionar como líder</a>
<?php endif; ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
</div>