<?php use Tonight\MVC\Router; ?>
<div class="container">
	<h1><?= $title ?></h1>
	<hr>
	<table class="table table-borderless table-responsive d-md-table">
		<thead>
			<tr>
				<th>Pesquisa</th>
				<th>Ação</th>
			</tr>
		</thead>
		<tbody>
<?php foreach ($researches as $search): ?>
			<tr>
				<td><?= $search->getName() ?></td>
				<td>
					<a href="<?= Router::getLink('pesquisas', $search->getId(), 'abrir') ?>"
					class="btn btn-primary">
						Responder
					</a>
				</td>
			</tr>
<?php endforeach; ?>
		</tbody>
	</table>
</div>