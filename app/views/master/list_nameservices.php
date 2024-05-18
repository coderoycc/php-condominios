  <table class="table table-striped" id="table_nameservices" style="width:100%">
    <thead>
      <tr>
        <th class="text-center">#</th>
        <th class="text-center">Nombre</th>
        <th class="text-center">Acrónimo</th>
        <th class="text-center">Creado en</th>
        <th class="text-center">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $i = 1;
      foreach ($services as $service) : ?>
        <tr>
          <td class="text-end"><?= $i ?></td>
          <td class="text-center"><?= $service['servicio'] ?></td>
          <td class="text-center"><?= $service['acronimo'] ?></td>
          <td class="text-center"><?= date('d/m/Y', strtotime($service['creado_en'])) ?></td>
          <td class="text-center">
            <div class="btn-group" role="group" aria-label="Basic mixed styles example">
              <button type="button" class="btn btn-danger text-white" title="ELIMINAR" data-bs-toggle="modal" data-bs-target="#service_delete" data-id="<?= $service['id_servicio'] ?>"><i class="fa fa-solid fa-trash"></i></button>
              <button type="button" class="btn btn-info text-white" title="EDITAR" data-bs-toggle="modal" data-bs-target="#service_edit" data-id="<?= $service['id_servicio'] ?>" data-service="<?= $service['servicio'] ?>" data-acronimo="<?= $service['acronimo'] ?>"><i class="fa fa-solid fa-pen"></i></button>
            </div>
          </td>
        </tr>
      <?php
        $i++;
      endforeach; ?>
    </tbody>
  </table>