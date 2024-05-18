  <table class="table table-striped" id="table_residentes" width="100%">
    <thead>
      <tr class="text-center">
        <th>#</th>
        <th>Nombre</th>
        <th>Apellido</th>
        <th>Celular</th>
        <th>Nº Depa</th>
        <th>Genero</th>
        <th>Estado</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $i = 1;
      foreach ($residents as $resident) : ?>
        <tr>
          <td><?= $i ?></td>
          <td><?= $resident['first_name'] ?></td>
          <td><?= $resident['last_name'] ?></td>
          <td><?= $resident['phone'] ?></td>
          <td class="text-center"><?= $resident['dep_number'] ?></td>
          <td class="text-center"><?= $resident['gender'] ?? 'O' ?></td>
          <td class="text-center"><?= $resident['status'] == 1 ? '<span class="badge text-bg-success text-white">HABILITADO</span>' : '<span class="badge text-bg-danger text-white">INHABILITADO</span>' ?></td>
          <td></td>
        </tr>
      <?php
        $i++;
      endforeach; ?>
    </tbody>
  </table>