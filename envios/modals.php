<!-- Modal ver capturas -->
<div class="modal fade" id="modal_ver_capturas" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title ">Capturas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="body_capturas">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Eliminar envio -->
<div class="modal fade" id="modal_eliminar_envio" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title ">Eliminar envio</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="id_envio_delete">
        <div class="alert alert-danger text-center" role="alert">
          ¿Está seguro? La eliminación es <b>irreversible</b>.
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" onclick="eliminar_envio()" class="btn btn-danger" data-bs-dismiss="modal">Eliminar</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>