<div class="card shadow">
  <input type="hidden" id="depa_id_codes" value="<?= $department->id_department ?>">
  <div class="card-header d-flex justify-content-center gap-2">
    <!-- <button type="button" class="btn btn-primary text-white" data-bs-toggle="collapse" data-bs-target="#list_general" aria-expanded="false" aria-controls="collapseExample">GENERAL</button> -->
    <!-- <button type="button" class="btn btn-info text-white" data-bs-toggle="collapse" data-bs-target="#list_details" aria-expanded="false" aria-controls="collapseExample">DETALLADO</button> -->
    <div class="w-50">
      <label class="form-label" for="year_codes">Año</label>
      <select id="year_codes" class="form-select">
        <?php for ($i = $year - 2; $i < $year; $i++) :  ?>
          <option value="<?= $i ?>"><?= $i ?></option>
        <?php endfor; ?>
        <option value="<?= $year ?>" selected><?= $year ?></option>
        <option value="<?= $year + 1 ?>"><?= $year + 1 ?></option>
      </select>
    </div>

  </div>
  <div class="card-body">
    <!-- <div class="collapse accordion-collapse show" id="list_general"> -->
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <th>Nro.</th>
          <th>MES</th>
          <th>MONTO</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $i = 1;
        foreach ($sums as $e) :  ?>
          <tr>
            <td><?= $i ?></td>
            <td><?= $months[$e['mes']] ?></td>
            <td class="text-end"><?= number_format($e['total'], 2) ?></td>
          </tr>
        <?php
          $i++;
        endforeach; ?>
      </tbody>
    </table>
    <!-- </div>
    <div class="collapse accordion-collapse" id="list_details">
      <p>sadsdfadsfadsf</p>
    </div> -->
  </div>
</div>
<script>
  $('.collapse').on('show.bs.collapse', function() {
    $('.collapse').not(this).collapse('hide');
  });
</script>