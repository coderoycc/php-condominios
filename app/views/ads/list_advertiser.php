<div class="row m-2">
  <p class="text-muted fw-semibold fs-4"><i class="fa-solid fa-handshake"></i> Lista de anunciantes</p>
  <div class="table-responsive">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre compania</th>
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
            <td><?= date('d/m/Y', strtotime($com['created_at'])) ?></td>
            <td><?= $com['phone'] ?></td>
            <td class="d-flex justify-content-between">
              <button class="btn btn-sm btn-outline-danger" type="button"><i class="fa fa-fw fa-trash"></i></button>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

</div>