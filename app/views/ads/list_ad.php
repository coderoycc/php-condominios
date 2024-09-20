<div class="row m-2">
  <div class="d-flex align-items-center justify-content-between flex-wrap">
    <p class="text-muted fw-semibold fs-4 m-0"><i class="fa-solid fa-clipboard-list"></i> Todas las publicidades</p>
    <div class="align-items-center">
      <select class="form-select">
        <option>TODOS</option>
        <option value="">EMPRESA 1</option>
        <option value="">EMPRESA 2</option>
      </select>
    </div>
  </div>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>Descripción</th>
        <th>Empresa</th>
        <th>Registrado en</th>
        <th>Tipo</th>
        <th>Inicio publicado</th>
        <th>Fin publicado</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($ads as $ad): ?>
        <tr>
          <td><?= $ad['id_ad'] ?></td>
          <td><?= $ad['description'] ?></td>
          <td><?= $ad['company'] ?></td>
          <td><?= date('d/m/Y', strtotime($ad['created'])) ?></td>
          <td><?= $ad['type'] ?></td>
          <td><?= date('d/m/Y', strtotime($ad['start_date'])) ?></td>
          <td><?= date('d/m/Y', strtotime($ad['end_date'])) ?></td>
          <td></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  </h1>