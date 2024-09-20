<div class="row">
  <div class="table-responsive">
    <table class="table table-striped">
      <thead>
        <tr class="table-warning">
          <td colspan="3" class="text-center fw-semibold">RECIBIDOS</td>
        </tr>
        <tr>
          <th>Nro. Casillero</th>
          <th>Contenido</th>
          <th>Recibido en</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($entrada) <= 0): ?>
          <tr>
            <td colspan="3" class="text-center">No hay registros</td>
          </tr>
        <?php endif; ?>
        <?php foreach ($entrada as $en): ?>
          <tr>
            <td class="text-center"><?= $en['locker_number'] ?></td>
            <td><?= $en['content'] ?></td>
            <td><?= date('d/m/Y', strtotime($en['received_at'])) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <div class="table-responsive mt-4">
    <table class="table table-striped">
      <thead>
        <tr class="table-primary">
          <td colspan="3" class="text-center fw-semibold">ENVIADOS</td>
        </tr>
        <tr>
          <th>Nro. Casillero</th>
          <th>Contenido</th>
          <th>Enviado en</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($salida) <= 0): ?>
          <tr>
            <td colspan="3" class="text-center">No hay registros</td>
          </tr>
        <?php endif; ?>
        <?php foreach ($salida as $sa): ?>
          <tr>
            <td class="text-center"><?= $sa['locker_number'] ?></td>
            <td><?= $sa['content'] ?></td>
            <td><?= date('d/m/Y', strtotime($sa['received_at'])) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>