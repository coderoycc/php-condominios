<!-- Modal -->
<div class="modal fade" id="modal_add_price" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-info">
        <h1 class="modal-title fs-5 text-white fw-semibold">Agregar precios</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="id_shipping_modal">
        <div class="row">
          <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">Costo de envio</label>
            <input type="number" step="any" class="form-control" id="price_modal" placeholder="0.00">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-info" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-info text-white" onclick="sendPrice()">Guardar</button>
      </div>
    </div>
  </div>
</div>