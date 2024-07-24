<div class="table-responsive">
  <table class="table table-bordered table-hover">
    <thead>
      <tr class="text-center">
        <th>ID</th>
        <?php if ($estado == 'ENVIADO') : ?>
          <th>Código</th>
        <?php endif; ?>
        <th>Nro. Departamento</th>
        <th>Nombre Origen</th>
        <th>Nombre destino</th>
        <th>País destino</th>
        <th>Dirección destino</th>
        <th>Creado en</th>
        <th>Precio</th>
        <th>Peso (k)</th>
        <th>Dimensiones (cm)</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($shippings as $shipp) : ?>
        <tr>
          <td><?= $shipp['id'] ?></td>
          <?php if ($estado == 'ENVIADO') : ?>
            <td><?= $shipp['tracking_id'] ?? 'S/C' ?></td>
          <?php endif; ?>
          <td><?= $shipp['dep_number'] ?></td>
          <td><?= $shipp['name_origin'] ?></td>
          <td><?= $shipp['name_destiny'] ?></td>
          <td><?= $shipp['country_destiny'] ?></td>
          <td><?= $shipp['address_destiny'] ?></td>
          <td><?= date('d/m/Y', strtotime($shipp['created_at'])) ?></td>
          <td><?= number_format($shipp['price'] ?? 0, 2) ?></td>
          <td><?= $shipp['weight'] ?></td>
          <td><?= $shipp['h'] . ' x ' . $shipp['l'] . ' x ' . $shipp['w'] ?></td>
          <td class="text-center">
            <div class="d-flex justify-content-between flex-wrap gap-3">
              <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#modal_show_details" data-id="<?= $shipp['id'] ?>"><i class="fa fa-solid fa-eye"></i></button>
              <?php if ($estado == 'EN PROCESO') : ?>
                <button type="button" class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#modal_add_price" data-id="<?= $shipp['id'] ?>">Agregar Precio</button>
              <?php elseif ($estado == 'PARA ENVIAR') : ?>
                <button type="button" class="btn btn-info">Detalles</button>
              <?php elseif ($estado == 'ENVIADO') : ?>
                <button type="button" class="btn btn-info">Agregar Codigo seguimiento</button>
              <?php endif; ?>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>