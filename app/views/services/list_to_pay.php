<div class="card border-0">
  <div class="card-body">
    <table class="table table-striped" style="width:100%;" id="table_services_content">
      <thead>
        <tr class="text-center">
          <th>Nombre Condominio</th>
          <th>Nro. Departamento</th>
          <th>Mes</th>
          <th>Monto Total</th>
          <th>Estado</th>
          <th class="text-center">Opciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($services as $serv) : ?>
          <tr>
            <td><?= $serv['Condominio'] ?></td>
            <td><?= $serv['dep_number'] ?></td>
            <td><?= $months[$serv['month']] ?></td>
            <td><?= number_format($serv['totalmes'], 2) ?></td>
            <td class="text-center"><?= $serv['status'] ?? 'SIN PAGAR' ?></td>
            <td class="text-center ">
              <button type="button" title="Detalles" data-bs-toggle="modal" data-bs-target="#modal_detail_payment" data-id="<?= $serv['subscription_id'] ?>" data-key="<?= $serv['key'] ?>" data-month="<?= $serv['month'] ?>" data-year="<?= $year ?>" class="my-2 btn btn-info text-white"><i class="fa-solid fa-eye"></i> Ver detalles</button>
              <button class="btn btn-success my-2 text-white" data-id="<?= $serv['subscription_id'] ?>" data-key="<?= $serv['key'] ?>" data-month="<?= $serv['month'] ?>" data-year="<?= $year ?>" type="button" data-bs-toggle="modal" data-bs-target="#modal_pay_voucher"><i class="fa-solid fa-money-bill-1-wave"></i> Pagar y registrar comprobante</button>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>