  <table class="table table-striped" id="table_departments" style="width:100%">
    <thead>
      <tr>
        <th class="text-center">#</th>
        <th class="text-center">Nº de Departamento</th>
        <th class="text-center">Cuartos</th>
        <th class="text-center">Descripción</th>
        <th class="text-center">Suscripción activa</th>
        <th class="text-center">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $i = 1;
      foreach ($departments as $department) : ?>
        <tr>
          <td class="text-end"><?= $i ?></td>
          <td class="text-center"><?= $department['dep_number'] ?></td>
          <td class="text-center"><?= $department['bedrooms'] ?></td>
          <td><?= $department['description'] ?></td>
          <td class="text-center"><?= $department['id_subscription'] ? '<span class="badge text-bg-success text-white">SI</span>' : '<span class="badge text-bg-danger text-white">NO</span>' ?></td>
          <td class="text-center">
            <div class="btn-group" role="group" aria-label="Basic mixed styles example">
              <?php if ($department['status']): ?>
                <button type="button" class="btn btn-danger text-white" title="DAR BAJA" data-bs-toggle="modal" data-bs-target="#depa_delete" data-id="<?= $department['id_department'] ?>" data-message="BAJA"><i class="fa-solid fa-circle-arrow-down"></i></button>
              <?php else: ?>
                <button type="button" class="btn btn-success text-white" title="DAR ALTA" data-bs-toggle="modal" data-bs-target="#depa_delete" data-id="<?= $department['id_department'] ?>" data-message="ALTA"><i class="fa-solid fa-circle-arrow-up"></i></button>
              <?php endif; ?>
              <button type="button" class="btn btn-info text-white" title="EDITAR" data-bs-toggle="modal" data-bs-target="#depa_edit" data-id="<?= $department['id_department'] ?>"><i class="fa fa-solid fa-pen"></i></button>
              <button type="button" class="btn btn-success text-white" title="SUSCRIPCIONES" data-bs-toggle="modal" data-bs-target="#subscription_depa" data-id="<?= $department['id_department'] ?>"><i class="fa fa-users-between-lines"></i></button>
            </div>
          </td>
        </tr>
      <?php
        $i++;
      endforeach; ?>
    </tbody>
  </table>