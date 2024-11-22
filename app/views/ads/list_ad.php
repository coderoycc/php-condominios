<div class="m-2">
  <div class="d-flex align-items-center justify-content-between flex-wrap">
    <p class="text-muted fw-semibold fs-4 m-0"><i class="fa-solid fa-clipboard-list"></i> Todas las publicidades</p>
    <!-- <div class="align-items-center">
      <select class="form-select">
        <option>TODOS</option>
        <option value="">EMPRESA 1</option>
        <option value="">EMPRESA 2</option>
      </select>
    </div> -->
  </div>
</div>
<div class="table-responsive">
  <table id="table_ads" style="width:100%" class="table table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>Descripción</th>
        <th>Empresa</th>
        <th>Registrado en</th>
        <th>Tipo</th>
        <th>Objetivo</th>
        <th>Inicio publicado</th>
        <th>Fin publicado</th>
        <th>Opciones</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $targets = ['F' => 'FEMENINO', 'M' => 'MASCULINO', 'O' => 'TODOS'];
      foreach ($ads as $ad): ?>
        <tr>
          <td><?= $ad['id_ad'] ?></td>
          <td><?= $ad['description'] ?></td>
          <td><?= $ad['company'] ?></td>
          <td><?= date('d/m/Y', strtotime($ad['created'])) ?></td>
          <td><?= $ad['type'] ?></td>
          <td><?= isset($ad['target']) ? $targets[$ad['target']] : 'TODOS' ?></td>
          <td><?= date('d/m/Y', strtotime($ad['start_date'])) ?></td>
          <td><?= date('d/m/Y', strtotime($ad['end_date'])) ?></td>
          <td class="text-center">
            <div class="btn-group" role="group" aria-label="Basic example">
              <button type="button" class="btn btn-info text-white" onclick="form_edit_ad(<?= $ad['id_ad'] ?>)"><i class="fa fa-lg fa-solid fa-pen"></i></button>
              <button type="button" class="btn btn-danger text-white" data-bs-toggle="modal" data-bs-target="#delete_confirm_ad" data-id="<?= $ad['id_ad'] ?>"><i class="fa fa-lg fa-solid fa-trash"></i></button>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>