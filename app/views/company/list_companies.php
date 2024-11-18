<div class="row m-2">
  <p class="text-muted fw-semibold fs-4"><i class="fa-solid fa-handshake"></i> Lista de empresas</p>
</div>
<div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>Nombre compania</th>
        <th>Descripción</th>
        <th>Creado en</th>
        <th>Contacto</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($companies as $com): ?>
        <tr>
          <td><?= $com['id_company'] ?></td>
          <td><?= $com['company'] ?></td>
          <td><?= $com['descripcion'] ?></td>
          <td><?= date('d/m/Y', strtotime($com['created_at'])) ?></td>
          <td><?= $com['phone'] ?></td>
          <td class="text-center">
            <div class="btn-group" role="group" aria-label="Basic example">
              <button type="button" class="btn btn-info text-white" onclick="form_edit_ad(<?= $ad['id_ad'] ?>)"><i class="fa fa-lg fa-solid fa-pen"></i></button>
              <button type="button" class="btn btn-danger text-white" data-bs-toggle="modal" data-bs-target="#delete_confirm_company" data-id="<?= $ad['id_company'] ?>"><i class="fa fa-lg fa-solid fa-trash"></i></button>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>