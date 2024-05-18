<?php
if (count($lockers) > 0) :
  foreach ($lockers as $locker) : ?>
    <div class="col-md-3 col-sm-2">
      <div class="card text-bg-secondary mb-3" style="max-width: 18rem;">
        <div class="card-header fw-bold text-center fs-5">Casillero Nº <?= $locker['locker_number'] ?></div>
        <div class="card-body">
          <p class="card-text"><?= strtoupper($locker['type']) ?></p>
        </div>
        <div class="card-footer text-center">
          <button type="button" class="btn btn-sm btn-primary text-white" title="EDITAR" data-id="<?= $locker['id_locker'] ?>"><i class="fa fa-solid fa-pencil"></i></button>
          <button type="button" class="btn btn-sm btn-danger text-white" title="ELIMINAR" data-bs-toggle="modal" data-bs-target="#modal_delete_locker" data-id="<?= $locker['id_locker'] ?>" data-nro="<?= $locker['locker_number'] ?>"><i class="fa fa-solid fa-trash"></i></button>
        </div>
      </div>
    </div>
  <?php endforeach;
else :
  ?>
  <div class="col-12 mt-2 text-center" style="color:var(--bs-verde)">
    <p class="fs-1 mb-0">No hay casilleros </p>
    <p class="text-muted mt-0">Agrega nuevos casilleros 😎</p>
  </div>
<?php endif; ?>