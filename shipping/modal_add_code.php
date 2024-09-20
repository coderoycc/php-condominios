<!-- Modal ADD CODE TRACKING ID -->
<div class="modal fade" id="modal_add_tracking" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-info">
        <h1 class="modal-title fs-5 text-white fw-semibold">Agregar detalles de seguimiento</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="id_shipping_modal_tracking">
        <div class="row">
          <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">Codigo de envio</label>
            <input type="text" class="form-control" id="tracking_id_value" placeholder="XXXXXX">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-info" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-info text-white" onclick="send_tracking_id()">Guardar</button>
      </div>
    </div>
  </div>
</div>