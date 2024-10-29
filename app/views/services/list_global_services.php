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
          <th>Opciones</th>
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
            <td class="text-center">
              <button type="button" title="REGISTRAR" class="btn btn-info text-white"><i class="fa-solid fa-calendar"></i> Registrar pagos del mes</button>
              <button class="btn btn-success" type="button"><i class="fa-solid fa-eye"></i></button>
            </td>
          </tr>
        <?php endforeach; ?>

      </tbody>
    </table>
  </div>
</div>