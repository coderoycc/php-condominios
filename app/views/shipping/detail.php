<div class="row">
  <div class="col-md-6 mb-2">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between">
          <h5 class="mb-1">Datos remitente</h5>
          <small class="text-body-secondary"><b>Nro. Depa:</b> <?= $shipping->department->dep_number ?? 'NN' ?></small>
        </div>
        <small class="fw-semibold">Persona</small>
        <p class="m-0"><?= $shipping->name_origin ?></p>
        <p class="m-0"><small>Código postal: </small><?= $shipping->postal_code_origin ?? 'S/C' ?></p>
        <small class="fw-semibold">Ubicación</small>
        <div class="d-flex justify-content-between mb-0">
          <p class="m-0"><small>País: </small><?= $shipping->country_origin ?></p>
          <p class="m-0"><small>Ciudad:</small> <?= $shipping->city_origin ?></p>
        </div>
        <p class="mt-0 mb-1"><small class="fw-semibold">Dirección: </small> <?= $shipping->address_origin ?></p>
      </div>
    </div>
  </div>
  <div class="col-md-6 mb-2">
    <div class="card">
      <div class=" card-body">
        <div class="d-flex justify-content-between">
          <h5 class="mb-1">Datos consignatario</h5>
        </div>
        <small class="fw-semibold">Persona</small>
        <p class="m-0"><?= $shipping->name_destiny ?></p>
        <p class="m-0"><small>Codigo Postal:</small> <?= $shipping->postal_code_destiny ?? 'S/C' ?></p>
        <small class="fw-semibold">Ubicación</small>
        <div class="d-flex justify-content-between">
          <p class="m-0"><small>País: </small> <?= $shipping->country_destiny ?></p>
          <p class="m-0"><small>Ciudad:</small> <?= $shipping->city_destiny ?></p>
        </div>
        <p class="mt-0 mb-1"><small class="fw-semibold">Dirección: </small> <?= $shipping->address_destiny ?></p>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card">
      <div class="card-body">
        <div class="d-flex w-100 justify-content-between">
          <h5 class="mb-1">Detalles del envio</h5>
          <small class="text-body-secondary">ID: <?= $shipping->id ?></small>
        </div>
        <div class="d-flex justify-content-between mb-2">
          <p><span class="fw-semibold">Creado en: </span><?= date('d/m/Y', strtotime($shipping->created_at)) ?></p>
          <p><span class="fw-semibold">Estado: </span> <?= $shipping->status ?></p>
        </div>
        <small class="fw-semibold">Dimensiones (cm):</small>
        <div class="d-flex justify-content-between">
          <p class="mb-1"><span class="fw-semibold">Alto: </span><?= $shipping->h ?? '' ?></p>
          <p class="mb-1"><span class="fw-semibold">Ancho:</span> <?= $shipping->w ?? '' ?></p>
          <p class="mb-1"><span class="fw-semibold">Largo:</span> <?= $shipping->l ?? '' ?></p>
        </div>
        <p><span class="fw-semibold">Peso: </span><?= $shipping->weight ?> kg.</p>
        <p><span class="fw-semibold">Costo envio: </span><?= number_format($shipping->price ?? 0, 2) ?> </p>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <?php if ($shipping->payment != null) : ?>
      <div class="card">
        <div class="card-body">
          <div class="d-flex w-100 justify-content-between">
            <h5 class="mb-1">Detalles del pago</h5>
            <small class="text-body-secondary">ID: </small>
          </div>
          <p class="mb-1">Some placeholder content in a paragraph.</p>
          <small class="text-body-secondary">And some muted small print.</small>
        </div>
      </div>
    <?php else : ?>
      <div class="card">
        <div class="card-body">
          <div class="d-flex w-100 justify-content-between">
            <h5 class="mb-1">Detalles del pago</h5>
          </div>
          <div class="alert alert-warning" role="alert">
            Este envio no tiene registrado un pago aún.
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- <pre>
    <?= var_dump($shipping) ?>
  </pre> -->