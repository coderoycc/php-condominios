<?php
$labels_status = array_keys($estados);
$values_status = array_values($estados);
$labels_months = array_keys($months_subs);
$months_qty = count($labels_months);
?>
<?php if ($qty > 0) : ?>
  <div class="row mt-3">
    <div class="col-md-12 d-flex justify-content-center">
      <button class="btn btn-primary text-white" type="button" onclick="export_excel(<?= $id ?>)"><i class="fa-regular fa-file-excel"></i> Reporte</button>
    </div>
  </div>
  <div class="row mt-2">
    <div class="col-md-6 mb-2">
      <div class="card">
        <div class="card-header bg-secondary">
          <p class="fs-5 text-center mb-0 fw-bold" style="color:var(--bs-verde)">Suscripciones válidas - vencidas</p>
        </div>
        <div class="card-body">
          <canvas id="chartPieStatus" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%; display: block; width: 575px;"></canvas>
        </div>
      </div>
    </div>
    <div class="col-md-6 mb-2">
      <div class="card">
        <div class="card-header bg-secondary">
          <p class="fs-5 text-center mb-0 fw-bold" style="color:var(--bs-verde)">Cantidades</p>
        </div>
        <div class="card-body">
          <canvas id="chartBarMonths_count" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%; display: block; width: 575px;"></canvas>
        </div>
      </div>
    </div>
    <div class="col-md-6  mb-2">
      <div class="card">
        <div class="card-header bg-secondary">
          <p class="fs-5 text-center mb-0 fw-bold" style="color:var(--bs-verde)">Pagos </p>
        </div>
        <div class="card-body">
          <canvas id="chartBarMonths_payments" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%; display: block; width: 575px;"></canvas>
        </div>
      </div>
    </div>
    <div class="col-md-6 mb-2">
      <div class="card">
        <div class="card-header bg-secondary">
          <p class="fs-5 text-center mb-0 fw-bold" style="color:var(--bs-verde)">Resumen</p>
        </div>
        <div class="card-body">
          <table class="table table-bordered table-hover">
            <thead>
              <tr class="text-center">
                <th>MES</th>
                <th>CANTIDAD SUSCRITOS</th>
                <th>MONTO X MES</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $counts = [];
              $amounts = [];
              foreach ($months_subs as $month => $value) :
                $counts[] = $value['count'];
                $amounts[] = $value['amount'];
              ?>
                <tr>
                  <td><?= $month ?></td>
                  <td class="text-end"><?= $value['count'] ?></td>
                  <td class="text-end"><?= $value['amount'] ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
<?php else : ?>
  <div class="row mt-3">
    <div class="col-md-12">
      <div class="alert alert-info" role="alert">
        <h4 class="alert-heading">Sin registros para el rango de fechas</h4>
        <p>Considere poner otro rango de fechas</p>
      </div>
    </div>
  </div>
<?php endif; ?>
<?php if ($qty > 0) : ?>
  <script>
    if (typeof colors === 'undefined') {
      colors = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#ffc107', '#28a745'];
    }
    data_id = {
      labels: <?= json_encode($labels_status) ?>,
      datasets: [{
        label: 'Suscripciones',
        data: <?= json_encode($values_status) ?>,
        backgroundColor: colors.slice(0, 2),
      }],
      hoverOffset: 2
    }
    config_id = {
      type: 'pie',
      data: data_id,
    }

    pieChart_id = new Chart(
      document.getElementById('chartPieStatus'),
      config_id
    );


    // BARS COUNT
    data_count = {
      labels: <?= json_encode($labels_months) ?>,
      datasets: [{
        label: 'Suscripciones',
        data: <?= json_encode($counts) ?>,
        backgroundColor: colors.slice(0, <?= $months_qty ?>),
        borderColor: colors.slice(0, <?= $months_qty ?>),
      }]
    }
    barChart_count = new Chart(
      document.getElementById('chartBarMonths_count'), {
        type: 'bar',
        data: data_count,
      }
    )

    //BARS AMOUNTS
    data_amount = {
      labels: <?= json_encode($labels_months) ?>,
      datasets: [{
        label: 'Bs: ',
        data: <?= json_encode($amounts) ?>,
        backgroundColor: colors.slice(0, <?= $months_qty ?>),
        borderColor: colors.slice(0, <?= $months_qty ?>),
      }]
    }
    barChart_amount = new Chart(
      document.getElementById('chartBarMonths_payments'), {
        type: 'bar',
        data: data_amount,
      }
    )

    function export_excel(id) {
      var form = document.createElement("form");
      form.setAttribute("method", "POST");
      form.setAttribute("action", "../reports/excels/subs_by_id.php");
      form.innerHTML = `
      <input type="hidden" name="id" value="${id}">
      <input type="hidden" name="inicio" value="<?= $start ?>">
      <input type="hidden" name="fin" value="<?= $end ?>">
      `;
      form.setAttribute("target", "_blank");
      document.body.appendChild(form);
      form.submit();
      form.remove();
    }
  </script>
<?php endif; ?>