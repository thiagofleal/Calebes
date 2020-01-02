<div class="container">
	<?php if ($flash): ?>
		<div class="alert <?= $alert['type'] ?>">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<?= $alert['text'] ?>
		</div>
	<?php endif; ?>
	<h1>Cadastrar calebe</h1>
	<hr>
	<form method="post" action="<?= $action ?>">
		<div class="input-group mb-3">
			<div class="input-group-prepend">
				<label for="name" class="input-group-text">
					Nome <small>*</small>
				</label>
			</div>
			<input type="text" name="name" id="name" class="form-control" required="required" />
		</div>
		<div class="input-group mb-3">
			<div class="input-group-prepend">
				<label for="birth" class="input-group-text">
					Data de nascimento
				</label>
			</div>
			<input type="date" name="birth" id="birth" class="form-control" />
		</div>
		<div class="input-group mb-3">
			<div class="input-group-prepend">
				<label for="address" class="input-group-text">
					Endereço
				</label>
			</div>
			<input type="search" name="address" id="address" class="form-control" />
		</div>
		<div class="input-group mb-3">
			<div class="input-group-prepend">
				<label for="phone" class="input-group-text">
					Telefone
				</label>
			</div>
			<input type="text" name="phone" id="phone" class="form-control" />
		</div>
		<div class="input-group mb-3">
			<div class="input-group-prepend">
				<label for="email" class="input-group-text">
					Email
				</label>
			</div>
			<input type="email" name="email" id="email" class="form-control" />
		</div>
		<hr>
		<div class="row">
			<div class="col-md-8">
				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<label for="document" class="input-group-text">
							Documento <small>*</small>
						</label>
					</div>
					<input type="text" name="document" id="document" class="form-control" required="required" />
				</div>
			</div>
			<div class="col-md-4">
				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<label for="document_type" class="input-group-text">
							Tipo <small>*</small>
						</label>
					</div>
					<input type="text" name="document_type" id="document_type" class="form-control" required="required" />
				</div>
			</div>
		</div>
		<div class="input-group mb-3">
			<div class="input-group-prepend">
				<label for="password" class="input-group-text">
					Senha <small>*</small>
				</label>
			</div>
			<input type="password" name="password" id="password" class="form-control" required="required" />
		</div>
		<hr>
		<button type="submit" class="btn btn-primary">Cadastrar</button>
		<button type="button" class="btn btn-danger btn-clear-form">Cancelar</button>
	</form>
</div>