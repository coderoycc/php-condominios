<!-- MODAL NUEVO LUGAR -->
<div class="modal fade" id="modal_nuevo_lugar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5">Nuevo lugar</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="idlugar_form" value="0">
        <div class="form-floating mb-3">
          <input type="text" class="form-control" id="lugar_form" name="lugar" placeholder="" value="">
          <label for="lugar_form">Nombre del lugar</label>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="lugarUp()">Guardar</button>
      </div>
    </div>
  </div>
</div>

<!-- MODAL DELETE LUGAR -->
<div class="modal fade" id="modal_delete_lugar" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <input type="hidden" id="idlugar_delete">
      <div class="modal-header bg-danger">
        <h1 class="modal-title fs-5 text-white">¿Eliminar lugar?</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="deletelugar()">Eliminar</button>
      </div>
    </div>
  </div>
</div>