<div class="container">
  <fieldset>
    <h1>Login</h1>
    <hr>
    <form method="post" action="<?= $action ?>">
      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <label for="user" class="input-group-text">
            Usuário
          </label>
        </div>
        <input type="text" name="user" id="user" class="form-control" required="required" />
      </div>
      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <label for="password" class="input-group-text">
            Senha
          </label>
        </div>
        <input type="password" name="password" id="password" class="form-control" required="required" />
      </div>
      <hr>
      <button type="submit" class="btn btn-primary">
        Entrar
      </button>
      <button type="button" class="btn btn-danger btn-clear-form">
        Cancelar
      </button>
    </form>
  </fieldset>
  <br>
</div>