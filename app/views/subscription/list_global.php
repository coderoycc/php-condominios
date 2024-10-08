<form id="formSubs" onsubmit="return null">
  <div class="d-flex justify-content-end align-items-center flex-wrap gap-3 m-0">
    <div class="form-floating">
      <input type="search" class="form-control" name="q" placeholder="Buscar..." value="<?= $search ?>">
      <label for="q">Buscar residente</label>
    </div>


    <div class="form-floating">
      <select class="form-select" name="type">
        <option value="">TODOS</option>
        <?php foreach ($types_sub as $type): ?>
          <option value="<?= $type ?>" <?= $type_selected == $type ? 'selected' : '' ?>><?= strtoupper($type) ?></option>
        <?php endforeach; ?>
      </select>
      <label for="option">Tipo de suscripción</label>
    </div>
    <button type="submit" class="btn btn-info "><i class="fa fa-fw fa-search"></i></button>
  </div>
</form>
<div class="row mb-3">
  <div class="table-responsive">
    <table class="table table-striped" style="width:100%" id="table_residents">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Condominio</th>
          <th scope="col">Nombre Completo</th>
          <th scope="col">Celular</th>
          <th scope="col">Departamento</th>
          <th scope="col">Suscrito</th>
          <th scope="col">Tipo</th>
          <th scope="col">Fecha suscripción</th>
          <th scope="col">Fecha vencimiento</th>
          <th scope="col">Acciones</th>
        </tr>
      </thead>
      <tbody class="table-group-divider">
        <?php
        $i = 1;
        foreach ($residents as $resident): ?>
          <tr>
            <td><?= $i ?></td>
            <td><?= $resident['Condominio'] ?></td>
            <td><?= $resident['first_name'] . ' ' . $resident['last_name'] ?></td>
            <td><?= $resident['cellphone'] ?></td>
            <td><?= $resident['dep_number'] ?></td>
            <?php if ($resident['id_subscription']): ?>
              <td class="text-center"><span class="badge text-bg-success">SI</span></td>
              <td class="text-center"><?= strtoupper($resident['type_sub']) ?></td>
              <td class="text-center"><?= date('d/m/Y', strtotime($resident['subscribed_in'])) ?></td>
              <td class="text-center"><?= date('d/m/Y', strtotime($resident['expires_in'])) ?></td>
              </td>
            <?php else: ?>
              <td class="text-center"><span class="badge text-bg-warning">NO</span></td>
              <td class="text-center"></td>
              <td class="text-center">Sin suscripción</td>
              <td class="text-center">Sin suscripción</td>
            <?php endif; ?>
            <td class="text-center">
              <?php if ($resident['id_subscription']): ?>
                <button type="button" data-bs-toggle="modal" data-bs-target="#modal_change_subscription" data-key="<?= $resident['key'] ?>" data-depa="<?= $resident['id_department'] ?>" data-user="<?= $resident['id_user'] ?>" data-idsub="<?= $resident['id_subscription'] ?>" data-typeid="<?= $resident['type_id'] ?>" class="btn btn-sm btn-outline-info" title="Cambiar suscripción"><i class="fa-lg fa-solid fa-arrow-down-up-across-line"></i> Cambiar</button>
                <button type="button" data-bs-toggle="modal" data-bs-target="#modal_suspend" data-key="<?= $resident['key'] ?>" data-depa="<?= $resident['id_department'] ?>" data-user="<?= $resident['id_user'] ?>" data-idsub="<?= $resident['id_subscription'] ?>" data-depnumber="<?= $resident['dep_number'] ?>" class="btn btn-sm btn-outline-danger mt-2" title="Supender suscripción"><i class="fa-lg fa-solid fa-circle-arrow-down"></i> Suspender</button>
              <?php else: ?>
                <button type="button" data-bs-toggle="modal" data-bs-target="#modal_add_subscription" data-key="<?= $resident['key'] ?>" data-depa="<?= $resident['id_department'] ?>" data-user="<?= $resident['id_user'] ?>" class="btn btn-sm btn-outline-info" title="Agregar una suscripción"><i class="fa-lg fa-solid fa-square-caret-up"></i> Nuevo</button>
              <?php endif; ?>

            </td>
          </tr>
        <?php
          $i++;
        endforeach; ?>
      </tbody>
    </table>
  </div>
</div>