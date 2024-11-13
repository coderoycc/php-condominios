<div class="row m-2">
  <p class="text-muted fw-semibold fs-4"><i class="fa-solid fa-handshake"></i> Lista de Empresas</p>
  <div class="table-responsive">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre compania</th>
          <th>Descripción</th>
          <th>Página WEB|FACEBOOK</th>
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
            <td><?= $com['description'] ?></td>
            <td class="text-center"><?= $com['url'] == '' || $com['url'] == null ? 'No disponible' : '<a class="link" href="' . $com['url'] . '" target="_blank">Ver</a>' ?></td>
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