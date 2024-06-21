<div class="card shadow">
  <div class="card-body">
    <p class="fw-bold fs-4">Agregar pagos departamento <i><?= $department->dep_number ?></i></p>
    <div class="row">
      <div class="col-md-6 mx-auto">
        <label for="mes">Selecciona un mes:</label>
        <input type="month" id="mes" name="mes" class="form-control" value="<?= date("Y-m") ?>">
      </div>
    </div>
    <div class="row">
      <form id="fill_amounts_form">
        <table class="table table-striped">
          <thead>
            <tr class="text-center">
              <th>Código</th>
              <th>Servicio</th>
              <th>Monto</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($services as $service) : ?>
              <tr>
                <td><?= $service['code'] ?></td>
                <td><?= $service['service_name'] ?></td>
                <td>
                  <input type="hidden" name="ids[]" value="<?= $service['id_service'] ?>">
                  <input type="number" name="amounts[]" class="form-control service_amount text-end" placeholder="0.0" step="any" value="">
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="2" class="text-end fw-bold">Total: </td>
              <td class="text-end pe-3" id="total_services">0.0</td>
            </tr>
          </tfoot>
        </table>
        <div class="row d-flex justify-content-center">
          <div style="width:140px">
            <button type="submit" class="btn btn-primary text-white" <?= $services ? '' : 'disabled' ?>><i class="fa fa-solid fa-save"></i> Guardar</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
  $(document).on('input', '.service_amount', (e) => {
    let total = 0;
    $(".service_amount").each((index, element) => {
      const val = $(element).val() == '' ? 0 : parseFloat($(element).val());
      total += val;
    })
    $("#total_services").text(total.toFixed(2));
  })
</script>