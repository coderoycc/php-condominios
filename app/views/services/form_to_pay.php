<style>
  input[type=file] {
    display: none;
  }

  .choose-btn {
    border-radius: 2px;
    margin: 10px;
    float: left;
    background: #555;
    color: #fff;
    padding: 10px;
    text-align: center;
    cursor: pointer;
    font-family: Arial;
    font-size: 13px;
    border-radius: var(--bs-border-radius);
  }

  .choose-btn:hover {
    background: var(--bs-info);
  }
</style>
<div class="card shadow">
  <div class="card-header" style="display: flex;justify-content: space-between;align-items: center;">
    <div class="col-md-6 fw-semibold">DETALLES PAGO MES DE <?= $month ?> DE <?= $year ?> </div>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
  </div>
  <div class="card-body">
    <div class="row">
      <form id="pay_form">
        <input type="hidden" name="id" value="<?= $subscription->id_subscription ?>">
        <input type="hidden" name="key" value="<?= $key ?>">
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
            $x = 1;
            foreach ($services as $service) :
              $total += $service['amount'] ?? 0;
            ?>
              <input type="hidden" name="payment_id" value="<?= $service['payment_id'] ?>">
              <tr>
                <td><?= $service['code'] ?></td>
                <td><?= $service['service_name'] ?></td>
                <td>
                  <input type="hidden" name="id_detail[]" value="<?= $service['id_service_detail'] ?? '' ?>" />
                  <input type="hidden" name="ids[]" value="<?= $service['id_service'] ?>">
                  <input type="number" disabled name="amounts[]" class="form-control service_amount text-end" placeholder="0.0" step="any" value="<?= $service['amount'] != null ? number_format($service['amount'], 2) : '' ?>">
                </td>
                <td>
                  <label for="hiddenBtn<?= $x ?>" class="choose-btn">Seleccionar</label>
                  <input type="file" class="hiddenBtn" name="files[]" id="hiddenBtn<?= $x ?>" accept="image/*">
                </td>
              </tr>
            <?php
              $x++;
            endforeach; ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="2" class="text-end fw-bold">Total: </td>
              <td class="text-end pe-3" id="total_services"><?= number_format($total, 2) ?></td>
            </tr>
          </tfoot>
        </table>
        <div class="row d-flex justify-content-center">
          <div style="width:100%;display:flex;justify-content:space-between;">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">CANCELAR</button>
            <button type="submit" class="btn btn-success text-white"><i class="fa fa-solid fa-save"></i> GUARAR COMO PAGADO</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
  //funcion anonima de jquery
  (function($) {
    // $('.hiddenBtn').off('change');
    $('.hiddenBtn').on('change', function() {
      var fileName = $(this).prop('files').length > 0 ? $(this).prop('files')[0].name : 'Seleccionar';
      $(this).prev('.choose-btn').text(fileName);
    });
  })(jQuery);
</script>