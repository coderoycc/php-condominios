<div class="card border-0 mt-2">
  <div class="card-body">
    <table class="table table-striped" style="width:100%;" id="table_subscription">
      <thead>
        <tr class="text-center">
          <th>Nombre Condominio</th>
          <th>ID SUB.</th>
          <th>Valido hasta</th>
          <th>Tipo de suscripción</th>
          <th>Nro. Departamento</th>
          <th>Opciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($subscriptions as $sub) : ?>
          <tr>
            <td><?= $sub['Condominio'] ?></td>
            <td><?= $sub['id_subscription'] ?></td>
            <td><?= date('d/m/Y H:i', strtotime($sub['expires_in'])) ?></td>
            <td><?= $sub['tipo_sub'] ?></td>
            <td><?= $sub['dep_number'] ?></td>
            <td class="text-center">
              <button type="button" data-bs-toggle="modal" data-bs-target="#modal_register_payment" data-key="<?= $sub['key'] ?>" data-id="<?= $sub['id_subscription'] ?>" title="REGISTRAR" class="btn btn-info text-white"><i class="fa-solid fa-calendar"></i> Registra pago</button>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>