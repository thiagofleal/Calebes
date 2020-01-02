<div class="container">
	<?php if ($flash): ?>
		<div class="alert <?= $alert['type'] ?>">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<?= $alert['text'] ?>
		</div>
	<?php endif; ?>
	<h1>Cadastrar ponto</h1>
	<hr>
	<form method="post" action="<?= $action ?>">
		<div class="input-group mb-3">
			<div class="input-group-prepend">
				<label for="name" class="input-group-text">
					Nome
				</label>
			</div>
			<input type="text" name="name" id="name" class="form-control" required="required" />
		</div>
		<div class="input-group mb-3">
			<div class="input-group-prepend">
				<label for="address" class="input-group-text">
					Endereço
				</label>
			</div>
			<input type="search" name="address" id="address" class="form-control" required="required" />
		</div>
		<hr>
		<button type="submit" class="btn btn-primary">Cadastrar</button>
		<button type="button" class="btn btn-danger btn-clear-form">Cancelar</button>
	</form>
</div>