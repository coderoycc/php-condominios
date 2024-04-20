<!-- Modal nuevo ingreso -->
<div class="modal fade" id="modal_usuario_nuevo" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Nuevo usuario</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-primary" role="alert">
          La contraseña será el valor de <strong> usuario </strong> .
        </div>
        <form id="form_nuevo_user">
          <div class="form-floating mb-3">
            <input type="text" class="form-control" id="nombre_u_nuevo" placeholder="Nombre" value="" name="nombre">
            <label for="nombre_u_nuevo">Nombre</label>
          </div>
          <div class="form-floating mb-3">
            <input type="text" class="form-control" id="user_u_nuevo" placeholder="usuario" value="" name="usuario">
            <label for="user_u_nuevo">Usuario</label>
          </div>
          <div class="mb-2">
            <div class="form-floating mb-3">
              <select class="form-select" name="rol" id="rol_u_nuevo">
                <option value="ADMIN">ADMIN</option>
                <option value="VENTANILLA">VENTANILLA</option>
                <option value="ALMACEN">ALMACEN</option>
              </select>
              <label for="rol_u_nuevo">Rol</label>
            </div>
          </div>
          <div class="mb-2">
            <div class="form-floating mb-3">
              <select class="form-select" name="idLugar" id="lugar_user">
                <?php foreach ($lugares as $lugar) : ?>
                  <option value="<?= $lugar['idLugar'] ?>"><?= $lugar['lugar'] ?></option>
                <?php endforeach; ?>
              </select>
              <label for="lugar_user">Lugar Asignado</label>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btn_modal_user">Registrar suario</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal editar usuario -->
<div class="modal fade" id="modal_usuario_edit" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5">Editar Usuario</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="form_edit_user">
          <input type="hidden" name="idUsuario" value="" id="id_usuario_edit">
          <div class="form-floating mb-3">
            <input type="text" class="form-control" id="nombre_u_edit" placeholder="Nombre" value="" name="nombre">
            <label for="nombre_u_edit">Nombre</label>
          </div>
          <div class="form-floating mb-3">
            <input type="text" class="form-control" id="user_u_edit" placeholder="usuario" value="" name="usuario">
            <label for="user_u_edit">Usuario</label>
          </div>
          <div class="mb-2">
            <div class="form-floating mb-3">
              <select class="form-select" name="rol" id="rol_u_edit">
                <option value="ADMIN">ADMIN</option>
                <option value="VENTANILLA">VENTANILLA</option>
                <option value="ALMACEN">ALMACEN</option>
              </select>
              <label for="rol_u_edit">Rol</label>
            </div>
          </div>
          <div class="mb-2">
            <div class="form-floating mb-3">
              <select class="form-select" name="idLugar" id="lugar_user_edit">
                <?php foreach ($lugares as $lugar) : ?>
                  <option value="<?= $lugar['idLugar'] ?>"><?= $lugar['lugar'] ?></option>
                <?php endforeach; ?>
              </select>
              <label for="lugar_user_edit">Lugar Asignado</label>
            </div>
          </div>
        </form>
      </div>
  
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="updateUser()">Guardar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Eliminar usuario -->
<div class="modal fade" id="modal_delete_usuario" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h1 class="modal-title fs-5">Eliminar usuario</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="idUsuario_delete" value="0">
        <div class="mb-2">
          <p class="fs-4">
            ¿Está seguro que desea eliminar al usuario?
          </p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="deleteUser()">Sí, eliminar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal reset password  usuario -->
<div class="modal fade" id="modal_reset_pass" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-secondary text-white">
        <h1 class="modal-title fs-5">Restablecer password</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="idUsuario_reset_pass" value="0">
        <div class="mb-2">
          <div class="alert alert-warning text-center fs-5" role="alert">
            Si acepta la nueva contraseña será el mismo usuario.
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="user_reset_pass()">Aceptar</button>
      </div>
    </div>
  </div>
</div>