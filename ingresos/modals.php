<!-- Modal nuevo ingreso -->
<div class="modal fade" id="modal_ingreso_nuevo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Proyecto de tipo ingreso</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="form_ingreso">
          <input type="hidden" name="idProyecto" id="idProyecto_egreso" value="0">
          <div class="form-floating mb-3">
            <input type="text" name="proyecto" class="form-control" id="descripcion_e" placeholder="Buscar elementos" value="" autocomplete="off">
            <label for="descripcion_e">Descripción</label>
          </div>
          <div class="form-floating mb-3">
            <input type="number" class="form-control" id="monto_e" name="montoRef" placeholder="" value="" step="any">
            <label for="monto_e">Monto Ref:</label>
          </div>
          <div class="form-floating">
            <select class="form-select" id="estado_e" name="estado">
              <option value=""></option>
              <option value="PENDIENTE">PENDIENTE</option>
              <option value="SALDADO">SALDADO</option>
            </select>
            <label for="estado">Estado</label>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" onclick="ingresoUp()">Guardar</button>
      </div>
    </div>
  </div>
</div>

<!-- MODAL COMPROBANTE PAGO -->
<div class="modal fade" id="modal_egreso_comprobante" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5">Registrar comprobante de pago</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h6>Selecciona un tipo de comprobante</h6>
        <div class="d-flex justify-content-around">
          <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-primary type-comp" data-tipo="img"><i class="fa fa-image"></i></button>
            <button type="button" class="btn btn-outline-dark type-comp" data-tipo="firma"><i class="fa fa-signature"></i></button>
            <button type="button" class="btn btn-outline-secondary type-comp" data-tipo="audio"><i class="fa fa-microphone"></i></button>
          </div>
        </div>
        <form id="form_comprobante">
          <div id="cont_comprobante">

          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" id="btn_adjArchivo_modal" class="btn btn-primary" data-bs-dismiss="modal" onclick="adjuntarArch()">Adjuntar archivo</button>
      </div>
    </div>
  </div>
</div>