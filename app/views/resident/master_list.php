<form id="formFilter" onsubmit="return null">
  <div class="d-flex justify-content-end align-items-center flex-wrap gap-3 m-0">
    <div class="form-floating">
      <input type="search" class="form-control" name="q" placeholder="Buscar..." value="<?= $search ?>">
      <label for="q">Buscar residente</label>
    </div>


    <div class="form-floating">
      <select class="form-select" name="option">
        <option value="">TODO</option>
        <option <?= $option == "nombres" ? 'selected' : '' ?> value="nombres">NOMBRES</option>
        <option <?= $option == "celular" ? 'selected' : '' ?> value="celular">CELULAR</option>
        <option <?= $option == "dpto" ? 'selected' : '' ?> value="dpto">N. DPTO</option>
      </select>
      <label for="option">Buscar por</label>
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
          <th scope="col">Creado en</th>
          <th scope="col">Suscrito</th>
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
            <td><?= date('d/m/Y', strtotime($resident['created_at'])) ?></td>
            <?php if ($resident['id_subscription']): ?>
              <td class="text-center"><span class="badge text-bg-success">SI</span></td>
              <td class="text-center"><?= date('d/m/Y', strtotime($resident['subscribed_in'])) ?></td>
              <td class="text-center"><?= date('d/m/Y', strtotime($resident['expires_in'])) ?></td>
              </td>
            <?php else: ?>
              <td class="text-center"><span class="badge text-bg-warning">NO</span></td>
              <td class="text-center">Sin suscripción</td>
              <td class="text-center">Sin suscripción</td>
            <?php endif; ?>
            <td class="d-flex gap-2">
              <button type="button" data-bs-toggle="modal" data-bs-target="#modal_content_lockers" data-key="<?= $resident['key'] ?>" data-depa="<?= $resident['id_department'] ?>" data-user="<?= $resident['id_user'] ?>" class="btn btn-sm btn-outline-info" title="Pedidos y envios"><i class="fa fa-lg fa-door-closed"></i></button>
              <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modal_enable_new_session" data-id="<?= $resident['id_user'] ?>" data-name="<?= $resident['first_name'] . ' ' . $resident['last_name'] ?>" data-key="<?= $resident['key'] ?>" title="Nuevo inicio de sesión"><i class="fa fa-lg fa-mobile"></i></button>
            </td>
          </tr>
        <?php
          $i++;
        endforeach; ?>
      </tbody>
    </table>
  </div>
</div>