<!-- Modal Eliminar pago -->
<div class="modal fade" id="modal_delete_pago" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h1 class="modal-title fs-5" id="exampleModalLabel">¿Está seguro de eliminar el pago?</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="idPago_delete" id="idPago_delete" value="0">
        <div class="mb-2">
          <div id="aviso_modal_pago">
            <div class="alert alert-warning" role="alert">
              Para eliminar, debe ingresar su <b>contraseña</b>
            </div>
          </div>
          <div class="form-floating mb-3">
            <input type="password" class="form-control" placeholder="Contraseña" id="pass_modal_pago" autocomplete="off">
            <label for="">Contraseña</label>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" onclick="deletePago()">Eliminar</button>
      </div>
    </div>
  </div>
</div>