<?php
$etiquetas = array_keys($types);
$cant_etiquetas = count($etiquetas);
?>
<?php if ($total > 0) :  ?>
  <div class="row mt-4">
    <div class="col-md-6 d-flex justify-content-center align-items-center">
      <canvas id="pieChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%; display: block; width: 575px;"></canvas>
    </div>
    <div class="col-md-6">
      <table class="table table-striped">
        <thead>
          <tr class="text-center">
            <th>TIPO</th>
            <th>CANTIDAD</th>
            <th>PRECIO SUSCRIPCIÓN</th>
            <th>TOTAL x TIPO</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $totalAmount = 0;
          $cantidades = [];
          foreach ($types as $type => $content) : ?>
            <tr>
              <th class="text-start"><?= strtoupper($type) ?></th>
              <td class="text-center"><?= $content['count'] ?></td>
              <td class="text-end"><?= number_format($content['price'], 2) ?></td>
              <td class="text-end"><?= number_format($content['totalAmount'], 2) ?></td>
            </tr>
          <?php
            $cantidades[] = $content['count'];
            $totalAmount += $content['totalAmount'];
          endforeach; ?>
        </tbody>
        <tfoot>
          <tr class="text-end">
            <th colspan="3">TOTAL</th>
            <th><?= number_format($totalAmount, 2) ?></th>
          </tr>
        </tfoot>
      </table>
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
<?php if ($total > 0) : ?>
  <script>
    if (typeof colors === 'undefined') {
      colors = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#ffc107', '#28a745'];
    }
    data_general = {
      labels: <?= json_encode($etiquetas) ?>,
      datasets: [{
        label: 'Cantidad de suscripciones',
        data: <?= json_encode($cantidades) ?>,
        backgroundColor: colors.slice(0, <?= $cant_etiquetas ?>),
      }],
      hoverOffset: 2
    }
    config_general = {
      type: 'pie',
      data: data_general
    }

    pieChart_general = new Chart(
      document.getElementById('pieChart'),
      config_general
    );
  </script>
<?php endif; ?>