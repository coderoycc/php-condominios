<div class="card shadow">
  <div class="card-header" style="display: flex;justify-content: space-between;align-items: center;">
    <div class="col-md-6 fw-semibold">DETALLES PAGO MES DE <?= $month ?> DE <?= $year ?> </div>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
  </div>
  <div class="card-body">
    <div class="row">
      <table class="table table-striped">
        <thead>
          <tr class="text-center">
            <th>Código</th>
            <th>Servicio</th>
            <th>Monto</th>
            <th>Comprobante</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $total = 0;
          foreach ($services as $service) :
            $total += $service['amount'] ?? 0;
          ?>
            <tr>
              <td><?= $service['code'] ?></td>
              <td><?= $service['service_name'] ?></td>
              <td class="text-end"><?= number_format($service['amount'], 2) ?></td>
              <?php if ($service['filename'] == null || $service['filename'] == ''): ?>
                <td class="text-center">Sin comprobante asociado</td>
              <?php else: ?>
                <td class="text-center"><a href="<?= $urlbase . $service['filename'] ?>" target="_blank" type="button" class="btn btn-link">Ver archivo</a></td>
              <?php endif; ?>
            </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="2" class="text-end fw-bold">Total: </td>
            <td class="text-end" id="total_services"><?= number_format($total, 2) ?></td>
            <td></td>
          </tr>
        </tfoot>
      </table>
      <div class="d-flex justify-content-center">

        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cerrar</button>

      </div>
    </div>
  </div>
</div>