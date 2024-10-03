<?php
if (count($data_subs) > 0) :
  foreach ($data_subs as $sub) : ?>
    <div class="col-md-4 col-sm-6">
      <div class="card text-bg-secondary mb-3">
        <div class="card-header fw-bold text-center fs-5" style="color:var(--bs-verde)"><?= strtoupper($sub['name']) ?></div>
        <div class="card-body">
          <div class="d-flex justify-content-center w-100">
            <div style="width:80%;color:var(--bs-verde)">
              <p class="m-0"><?= $sub['tag'] ?></p>
              <p class="m-0 d-flex justify-content-between"><b>Precio <?= $sub['months_duration'] ?> meses</b> <span><?= $sub['price'] == 0 ? 'Gratis' : $sub['price'] ?></span></p>
              <p class="m-0 d-flex justify-content-between"><b>Precio 1 año</b> <span><?= $sub['annual_price'] == 0 ? 'No válido' : $sub['annual_price'] ?></span></p>
            </div>
          </div>
          <ul>
            <?php $details = json_decode($sub['details']);
            foreach ($details as $detail) : ?>
              <li><?= $detail ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
        <!-- <div class="card-footer text-center">
          <button type="button" class="btn btn-sm btn-primary" style="color:var(--bs-verde)" title="EDITAR" data-id="<?= $sub->id_subscription_type ?>"><i class="fa fa-solid fa-pencil"></i> Editar</button> -->
        <!-- <button type="button" class="btn btn-sm btn-danger text-white" title="ELIMINAR" data-bs-toggle="modal" data-bs-target="#modal_delete_type" data-id="<?= $sub->id_subscription_type ?>" data-name="<?= $sub->name ?>"><i class="fa fa-solid fa-trash"></i></button> -->
        <!-- </div> -->
      </div>
    </div>
  <?php endforeach;
else :
  ?>
  <div class="col-12 mt-2 text-center" style="color:var(--bs-verde)">
    <p class="fs-1 mb-0">No hay tipos de suscripciones </p>
  </div>
<?php endif; ?>