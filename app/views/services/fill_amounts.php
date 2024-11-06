<div class="card shadow">
  <div class="card-header" style="display: flex;justify-content: space-between;align-items: center;">
    <?php if ($nuevo) : ?>
      <p class="fw-bold fs-4 my-0">Agregar pagos departamento <i><?= $department->dep_number ?></i></p>
    <?php else : ?>
      <p class="fw-bold fs-4 my-0">Editar pagos departamento <i><?= $department->dep_number ?></i></p>
    <?php endif; ?>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
  </div>
  <div class="card-body">
    <div class="row">
      <div class="col-md-6 mx-auto">
        <?php if ($nuevo) : ?>
          <label for="mes">Selecciona un mes:</label>
          <input type="month" id="mes" name="mes" class="form-control" value="<?= date("Y-m") ?>">
        <?php else : ?>
          <label for="mes">Mes:</label>
          <input type="hidden" id="mes" name="mes" value="<?= $fecha ?>">
          <?php $fecha = explode('-', $fecha); ?>
          <input type="month" class="form-control" disabled value="<?= $fecha[0] . '-' . $fecha[1] ?>">
        <?php endif; ?>
      </div>
    </div>
    <div class="row">
      <form id="<?= $nuevo ? 'fill_amounts_form' : 'update_amounts' ?>">
        <input type="hidden" name="id_department" value="<?= $department->id_department ?>">
        <table class="table table-striped">
          <thead>
            <tr class="text-center">
              <th>Código</th>
              <th>Servicio</th>
              <th>Monto</th>
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
                <td>
                  <input type="hidden" name="id_detail[]" value="<?= $service['id_service_detail'] ?? '' ?>" />
                  <input type="hidden" name="ids[]" value="<?= $service['id_service'] ?>">
                  <input type="number" name="amounts[]" class="form-control service_amount text-end" placeholder="0.0" step="any" value="<?= $service['amount'] ?? '' ?>">
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="2" class="text-end fw-bold">Total: </td>
              <td class="text-end pe-3" id="total_services"><?= number_format($total, 2) ?></td>
            </tr>
          </tfoot>
        </table>
        <div class="row d-flex justify-content-center">
          <?php if ($nuevo) : ?>
            <div style="width:140px">
              <button type="submit" class="btn btn-primary text-white" <?= $services ? '' : 'disabled' ?>><i class="fa fa-solid fa-save"></i> Guardar</button>
            </div>
          <?php else : ?>
            <div style="width:100%;display:flex;justify-content:space-between;">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancelar</button>
              <button type="submit" class="btn btn-info text-white"><i class="fa fa-solid fa-save"></i> Actualizar</button>
            </div>
          <?php endif; ?>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
  (function() {
    $(document).off('input', '.service_amount');
    $(document).on('input', '.service_amount', (e) => {
      console.log(e.target.value);
      let total = 0;
      $(".service_amount").each((index, element) => {
        const val = $(element).val() == '' ? 0 : parseFloat($(element).val());
        total += val;
      })
      $("#total_services").text(total.toFixed(2));
    })
  }())
</script>