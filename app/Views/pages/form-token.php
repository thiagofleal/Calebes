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
				<label for="token" class="input-group-text">
					Token <small>*</small>
				</label>
			</div>
			<input type="text" name="token" id="token" class="form-control" required="required" />
		</div>
		<hr>
		<button type="submit" class="btn btn-primary">
			Verificar
		</button>
		<button type="button" class="btn btn-danger btn-clear-form">
			Limpar
		</button>
	</form>
</div>