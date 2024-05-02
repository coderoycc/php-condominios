<?php
if (count($data_subs) > 0) :
  foreach ($data_subs as $sub) : ?>
    <div class="col-md-3 col-sm-2">
      <div class="card text-bg-secondary mb-3" style="max-width: 18rem;">
        <div class="card-header fw-bold text-center fs-5"><?= strtoupper($sub->name) ?></div>
        <div class="card-body">
          <p class="card-text"><?= $sub->description ?></p>
          <div class="d-flex justify-content-between">
            <span class="fw-bold">Precio: </span>
            <span>Bs. <?= number_format($sub->price, 2) ?></span>
          </div>
        </div>
        <div class="card-footer text-center">
          <button type="button" class="btn btn-sm btn-primary text-white" title="EDITAR" data-id="<?= $sub->id_subscription_type ?>"><i class="fa fa-solid fa-pencil"></i></button>
          <button type="button" class="btn btn-sm btn-danger text-white" title="ELIMINAR" data-bs-toggle="modal" data-bs-target="#modal_delete_type" data-id="<?= $sub->id_subscription_type ?>" data-name="<?= $sub->name ?>"><i class="fa fa-solid fa-trash"></i></button>
        </div>
      </div>
    </div>
  <?php endforeach;
else :
  ?>
  <div class="col-12 mt-2 text-center" style="color:var(--bs-verde)">
    <p class="fs-1 mb-0">No hay tipos de suscripciones </p>
    <p class="text-muted mt-0">Agrega nuevos 😎</p>
  </div>
<?php endif; ?>